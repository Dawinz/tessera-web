<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/route_manager_helpers.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

$routeKey = trim((string) ($_GET['route_key'] ?? ''));
$weekRef = trim((string) ($_GET['week'] ?? ''));

if ($routeKey === '') {
	http_response_code(400);
	echo json_encode(['error' => 'route_key required'], JSON_UNESCAPED_UNICODE);
	exit;
}

if (!tessera_route_manager_tables_ready($db)) {
	echo json_encode(['days' => [], 'ready' => false], JSON_UNESCAPED_UNICODE);
	exit;
}

[$monday, $sunday] = tessera_route_manager_week_bounds($weekRef !== '' ? $weekRef : null);
$tenantWhere = tessera_sql_tenant_eq();
$keyEsc = mysqli_real_escape_string($db, $routeKey);

$sql = "SELECT id, route_id, vehicle_name, schedule_type, day_of_week, trip_date,
               departure_time, arrival_time, reporting_time, notes, status
        FROM route_schedules
        WHERE $tenantWhere AND route_id = '$keyEsc'
          AND (
            (schedule_type = 'weekly')
            OR (schedule_type = 'once' AND trip_date BETWEEN '" . $monday->format('Y-m-d') . "' AND '" . $sunday->format('Y-m-d') . "')
          )
        ORDER BY departure_time ASC, vehicle_name ASC";

$items = [];
$q = mysqli_query($db, $sql);
while ($q && ($row = mysqli_fetch_assoc($q))) {
	$items[] = [
		'id' => (int) $row['id'],
		'route_key' => (string) $row['route_id'],
		'vehicle_name' => (string) $row['vehicle_name'],
		'schedule_type' => (string) $row['schedule_type'],
		'day_of_week' => (string) ($row['day_of_week'] ?? ''),
		'trip_date' => (string) ($row['trip_date'] ?? ''),
		'departure_time' => (string) ($row['departure_time'] ?? ''),
		'arrival_time' => (string) ($row['arrival_time'] ?? ''),
		'reporting_time' => (string) ($row['reporting_time'] ?? ''),
		'departure_label' => tessera_route_manager_format_time($row['departure_time'] ?? null),
		'arrival_label' => tessera_route_manager_format_time($row['arrival_time'] ?? null),
		'notes' => (string) ($row['notes'] ?? ''),
		'status' => (string) ($row['status'] ?? 'active'),
	];
}

$days = [];
$codes = tessera_route_manager_day_codes();
for ($i = 0; $i < 7; $i++) {
	$dayDate = $monday->modify('+' . $i . ' days');
	$code = $codes[$i];
	$dayItems = array_values(array_filter($items, function ($item) use ($code, $dayDate) {
		if ($item['schedule_type'] === 'weekly') {
			return strcasecmp($item['day_of_week'], $code) === 0;
		}
		return $item['trip_date'] === $dayDate->format('Y-m-d');
	}));
	$days[] = [
		'code' => $code,
		'date' => $dayDate->format('Y-m-d'),
		'date_label' => $dayDate->format('j M'),
		'is_today' => $dayDate->format('Y-m-d') === (new DateTimeImmutable('today'))->format('Y-m-d'),
		'trips' => $dayItems,
	];
}

echo json_encode([
	'route_key' => $routeKey,
	'week_start' => $monday->format('Y-m-d'),
	'week_end' => $sunday->format('Y-m-d'),
	'week_label' => $monday->format('j M') . ' - ' . $sunday->format('j M Y'),
	'days' => $days,
	'ready' => true,
], JSON_UNESCAPED_UNICODE);
