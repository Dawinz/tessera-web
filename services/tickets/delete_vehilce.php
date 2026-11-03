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

$id = (int) ($_POST['id'] ?? 0);
$name = trim((string) ($_POST['name'] ?? ''));
$tenant_id = (int) tessera_current_tenant_id();

if ($id < 1 || $tenant_id < 1) {
	echo 'invalid';
	exit;
}

/* Confirm the vehicle belongs to this tenant before delete */
$own = $db->prepare('SELECT name FROM vehicle WHERE id=? AND tenant_id=? LIMIT 1');
if (!$own) {
	echo 'error';
	exit;
}
$own->bind_param('ii', $id, $tenant_id);
$own->execute();
$row = $own->get_result()->fetch_assoc();
$own->close();
if (!$row) {
	echo 'forbidden';
	exit;
}
$name = (string) ($row['name'] ?? $name);

$chk = $db->prepare('SELECT id FROM booking WHERE bus=? AND ' . tessera_sql_tenant_eq() . ' LIMIT 1');
if ($chk) {
	$chk->bind_param('s', $name);
	$chk->execute();
	$used = $chk->get_result();
	if ($used && $used->num_rows > 0) {
		echo 'cant';
		exit;
	}
	$chk->close();
}

$del = $db->prepare('DELETE FROM vehicle WHERE id=? AND tenant_id=? LIMIT 1');
if (!$del) {
	echo 'error';
	exit;
}
$del->bind_param('ii', $id, $tenant_id);
$del->execute();
echo 'ok';
