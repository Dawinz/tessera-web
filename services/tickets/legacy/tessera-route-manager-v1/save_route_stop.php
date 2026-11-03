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
$stopId = (int) ($payload['stop_id'] ?? 0);
$stopName = trim((string) ($payload['stop_name'] ?? ''));
$category = trim((string) ($payload['category'] ?? ''));
$location = trim((string) ($payload['location'] ?? ''));
$duration = trim((string) ($payload['duration'] ?? ''));
$tenantId = (int) tessera_current_tenant_id();

if ($routeKey === '') {
	http_response_code(400);
	echo json_encode(['error' => 'route_key required'], JSON_UNESCAPED_UNICODE);
	exit;
}

$esc = static fn(string $v): string => mysqli_real_escape_string($db, $v);

if ($stopId <= 0 && $stopName !== '') {
	mysqli_query(
		$db,
		"INSERT INTO stops (name, category, location, coordinates, detail, tenant_id)
		 VALUES ('" . $esc($stopName) . "', '" . $esc($category) . "', '" . $esc($location) . "', '', '', $tenantId)"
	);
	$stopId = (int) mysqli_insert_id($db);
}

if ($stopId <= 0) {
	http_response_code(400);
	echo json_encode(['error' => 'stop_id or stop_name required'], JSON_UNESCAPED_UNICODE);
	exit;
}

if ($id > 0) {
	$tw = tessera_sql_tenant_eq();
	$ok = mysqli_query(
		$db,
		"UPDATE route_stops SET stop_id=$stopId, duration='" . $esc($duration) . "'
         WHERE id=$id AND route_id='" . $esc($routeKey) . "' AND $tw"
	);
} else {
	$ok = mysqli_query(
		$db,
		"INSERT INTO route_stops (stop_id, route_id, company_id, duration, tenant_id)
         VALUES ($stopId, '" . $esc($routeKey) . "', '1', '" . $esc($duration) . "', $tenantId)"
	);
	$id = (int) mysqli_insert_id($db);
}

if (!$ok) {
	http_response_code(500);
	echo json_encode(['error' => 'save_failed', 'detail' => mysqli_error($db)], JSON_UNESCAPED_UNICODE);
	exit;
}

echo json_encode(['ok' => true, 'id' => $id, 'stop_id' => $stopId], JSON_UNESCAPED_UNICODE);
