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
$class = trim((string) ($payload['class'] ?? ''));
$amount = (float) ($payload['amount'] ?? 0);
$currency = trim((string) ($payload['currency'] ?? '/='));
$state = trim((string) ($payload['state'] ?? 'default'));
$tenantId = (int) tessera_current_tenant_id();

if ($routeKey === '' || $class === '') {
	http_response_code(400);
	echo json_encode(['error' => 'route_key and class required'], JSON_UNESCAPED_UNICODE);
	exit;
}

$esc = static fn(string $v): string => mysqli_real_escape_string($db, $v);

if ($id > 0) {
	$tw = tessera_sql_tenant_eq();
	$ok = mysqli_query(
		$db,
		"UPDATE route_fair SET class='" . $esc($class) . "', amount=$amount, currency='" . $esc($currency) . "',
         state='" . $esc($state) . "' WHERE id=$id AND route_id='" . $esc($routeKey) . "' AND $tw"
	);
} else {
	$ok = mysqli_query(
		$db,
		"INSERT INTO route_fair (class, amount, currency, currency_id, route_id, company_id, state, class_id, tenant_id)
         VALUES ('" . $esc($class) . "', $amount, '" . $esc($currency) . "', '1', '" . $esc($routeKey) . "', '1', '" . $esc($state) . "', 0, $tenantId)"
	);
	$id = (int) mysqli_insert_id($db);
}

if (!$ok) {
	http_response_code(500);
	echo json_encode(['error' => 'save_failed', 'detail' => mysqli_error($db)], JSON_UNESCAPED_UNICODE);
	exit;
}

echo json_encode(['ok' => true, 'id' => $id], JSON_UNESCAPED_UNICODE);
