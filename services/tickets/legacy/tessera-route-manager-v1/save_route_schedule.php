<?php
require_once __DIR__ . '/../../includes/tessera_staff_boot.php';
require_once __DIR__ . '/../../includes/route_manager_helpers.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['error' => 'method_not_allowed'], JSON_UNESCAPED_UNICODE);
	exit;
}

if (!tessera_route_manager_tables_ready($db)) {
	http_response_code(503);
	echo json_encode(['error' => 'schema_pending'], JSON_UNESCAPED_UNICODE);
	exit;
}

$payload = json_decode(file_get_contents('php://input') ?: '{}', true);
if (!is_array($payload)) {
	$payload = $_POST;
}

$id = (int) ($payload['id'] ?? 0);
$routeKey = trim((string) ($payload['route_key'] ?? ''));
$vehicle = trim((string) ($payload['vehicle_name'] ?? ''));
$type = trim((string) ($payload['schedule_type'] ?? 'weekly'));
$day = trim((string) ($payload['day_of_week'] ?? ''));
$tripDate = trim((string) ($payload['trip_date'] ?? ''));
$departure = trim((string) ($payload['departure_time'] ?? ''));
$arrival = trim((string) ($payload['arrival_time'] ?? ''));
$reporting = trim((string) ($payload['reporting_time'] ?? ''));
$notes = trim((string) ($payload['notes'] ?? ''));
$status = trim((string) ($payload['status'] ?? 'active'));
$username = (string) ($_SESSION['username'] ?? 'staff');
$tenantId = (int) tessera_current_tenant_id();

if ($routeKey === '' || $vehicle === '') {
	http_response_code(400);
	echo json_encode(['error' => 'route_key and vehicle_name required'], JSON_UNESCAPED_UNICODE);
	exit;
}

if (!in_array($type, ['weekly', 'once'], true)) {
	$type = 'weekly';
}

if ($type === 'weekly' && $day === '') {
	http_response_code(400);
	echo json_encode(['error' => 'day_of_week required for weekly schedule'], JSON_UNESCAPED_UNICODE);
	exit;
}

if ($type === 'once' && $tripDate === '') {
	http_response_code(400);
	echo json_encode(['error' => 'trip_date required for one-off schedule'], JSON_UNESCAPED_UNICODE);
	exit;
}

$esc = static function (string $v) use ($db): string {
	return mysqli_real_escape_string($db, $v);
};

$daySql = $type === 'weekly' ? "'" . $esc($day) . "'" : 'NULL';
$dateSql = $type === 'once' ? "'" . $esc($tripDate) . "'" : 'NULL';
$depSql = $departure !== '' ? "'" . $esc($departure) . "'" : 'NULL';
$arrSql = $arrival !== '' ? "'" . $esc($arrival) . "'" : 'NULL';
$repSql = $reporting !== '' ? "'" . $esc($reporting) . "'" : 'NULL';

if ($id > 0) {
	$tw = tessera_sql_tenant_eq();
	$sql = "UPDATE route_schedules SET
		route_id='" . $esc($routeKey) . "',
		vehicle_name='" . $esc($vehicle) . "',
		schedule_type='" . $esc($type) . "',
		day_of_week=$daySql,
		trip_date=$dateSql,
		departure_time=$depSql,
		arrival_time=$arrSql,
		reporting_time=$repSql,
		notes='" . $esc($notes) . "',
		status='" . $esc($status) . "',
		uploader='" . $esc($username) . "'
		WHERE id=$id AND $tw";
} else {
	$sql = "INSERT INTO route_schedules
		(route_id, vehicle_name, schedule_type, day_of_week, trip_date,
		 departure_time, arrival_time, reporting_time, notes, status, company_id, uploader, tenant_id)
		VALUES (
			'" . $esc($routeKey) . "',
			'" . $esc($vehicle) . "',
			'" . $esc($type) . "',
			$daySql,
			$dateSql,
			$depSql,
			$arrSql,
			$repSql,
			'" . $esc($notes) . "',
			'" . $esc($status) . "',
			'1',
			'" . $esc($username) . "',
			$tenantId
		)";
}

$ok = mysqli_query($db, $sql);
if (!$ok) {
	http_response_code(500);
	echo json_encode(['error' => 'save_failed', 'detail' => mysqli_error($db)], JSON_UNESCAPED_UNICODE);
	exit;
}

$newId = $id > 0 ? $id : (int) mysqli_insert_id($db);
echo json_encode(['ok' => true, 'id' => $newId], JSON_UNESCAPED_UNICODE);
