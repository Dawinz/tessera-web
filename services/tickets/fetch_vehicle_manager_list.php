<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/vehicle_seat_layout.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

$tenantWhere = tessera_sql_tenant_eq();
$search = trim((string) ($_GET['q'] ?? ''));

$sql = "SELECT id, name, driver, conductor, seats, status, state";
if (tessera_vehicle_has_layout_column($db)) {
	$sql .= ", seat_layout_json";
}
$sql .= " FROM vehicle WHERE $tenantWhere";
if ($search !== '') {
	$esc = mysqli_real_escape_string($db, $search);
	$sql .= " AND (name LIKE '%$esc%' OR driver LIKE '%$esc%' OR conductor LIKE '%$esc%')";
}
$sql .= " ORDER BY name ASC LIMIT 500";

$rows = [];
$q = mysqli_query($db, $sql);
while ($q && ($row = mysqli_fetch_assoc($q))) {
	$patterns = tessera_vehicle_row_patterns($row);
	$extras = tessera_vehicle_layout_extras($row);
	$layoutSeats = tessera_count_seats_in_patterns($patterns, $extras);
	$hasCustom = !empty($row['seat_layout_json']);
	$rows[] = [
		'id' => (int) $row['id'],
		'name' => (string) $row['name'],
		'driver' => (string) $row['driver'],
		'conductor' => (string) $row['conductor'],
		'seats' => (int) $row['seats'],
		'status' => (string) $row['status'],
		'state' => (string) ($row['state'] ?? 'normal'),
		'layout_seats' => $layoutSeats,
		'layout_ok' => $layoutSeats === (int) $row['seats'],
		'has_custom_layout' => $hasCustom,
	];
}

echo json_encode(['vehicles' => $rows], JSON_UNESCAPED_UNICODE);
