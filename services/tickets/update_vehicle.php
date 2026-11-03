<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
header('Content-Type: text/plain; charset=utf-8');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}

require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

$vehicle_name = trim((string) ($_POST['vehicle_name'] ?? ''));
$driver_name = trim((string) ($_POST['driver_name'] ?? ''));
$conductor_name = trim((string) ($_POST['conductor_name'] ?? ''));
$seat_no = (int) ($_POST['seat_no'] ?? 0);
$detail = base64_encode(trim((string) ($_POST['detail'] ?? '')));
$check = (string) ($_POST['check'] ?? '');
$id_no = (int) ($_POST['id_no'] ?? 0);
$tenant_id = (int) tessera_current_tenant_id();

if ($id_no < 1 || $vehicle_name === '' || $tenant_id < 1) {
	echo 'invalid';
	exit;
}

$status = ($check === 'on') ? 'active' : 'off';
$vehicle_state = trim((string) ($_POST['state'] ?? 'normal'));
if (!in_array($vehicle_state, ['normal', 'vip'], true)) {
	$vehicle_state = 'normal';
}
$dedicate_seat_price = trim((string) ($_POST['dedicate_seat_price'] ?? ''));
$stmt = $db->prepare('UPDATE vehicle SET name=?, driver=?, conductor=?, seats=?, detail=?, status=?, state=?, dedicate_seat_price=? WHERE id=? AND tenant_id=? LIMIT 1');
if (!$stmt) {
	echo 'error';
	exit;
}
$stmt->bind_param('sssissssii', $vehicle_name, $driver_name, $conductor_name, $seat_no, $detail, $status, $vehicle_state, $dedicate_seat_price, $id_no, $tenant_id);
$stmt->execute();
if ($stmt->affected_rows < 1) {
	echo 'forbidden';
	exit;
}
echo 'succesfull';
