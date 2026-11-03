<?php
require_once __DIR__ . '/../../includes/tessera_staff_boot.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['error' => 'method_not_allowed'], JSON_UNESCAPED_UNICODE);
	exit;
}

$payload = json_decode(file_get_contents('php://input') ?: '{}', true);
if (!is_array($payload)) {
	$payload = $_POST;
}

$id = (int) ($payload['id'] ?? 0);
if ($id <= 0) {
	http_response_code(400);
	echo json_encode(['error' => 'id required'], JSON_UNESCAPED_UNICODE);
	exit;
}

$tw = tessera_sql_tenant_eq();
$ok = mysqli_query($db, "DELETE FROM route_fair WHERE id=$id AND $tw");
echo json_encode(['ok' => (bool) $ok], JSON_UNESCAPED_UNICODE);
