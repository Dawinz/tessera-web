<?php
/**
 * POST /cargo/api/console/luggage_delete.php
 * Body JSON: { id, password, reason }
 * Classic parity with delete/luggage.php (password + reason; block if on manifest).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_password.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';

[$db, $username] = tessera_cargo_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
$password = (string) ($payload['password'] ?? '');
$reason = trim((string) ($payload['reason'] ?? ''));

if ($id < 1) {
	tessera_console_error('validation_error', 'Luggage id is required', 422, [
		'fields' => ['id' => 'required'],
	]);
}
if ($password === '') {
	tessera_console_error('validation_error', 'Password is required', 422, [
		'fields' => ['password' => 'required'],
	]);
}
if ($reason === '') {
	tessera_console_error('validation_error', 'Reason is required', 422, [
		'fields' => ['reason' => 'required'],
	]);
}

$userEsc = $db->real_escape_string($username);
$uq = $db->query("SELECT username, password, role FROM users WHERE username='{$userEsc}' LIMIT 1");
$user = $uq ? $uq->fetch_assoc() : null;
if (!$user || !tessera_password_verify($password, (string) ($user['password'] ?? ''))) {
	tessera_console_error('forbidden', 'Password is incorrect', 403);
}

$role = (string) ($user['role'] ?? '');
$tenantSql = function_exists('tessera_sql_tenant_eq') ? (' AND ' . tessera_sql_tenant_eq()) : '';

$sel = $db->query("SELECT * FROM parcel_info WHERE id={$id}{$tenantSql} LIMIT 1");
$row = $sel ? $sel->fetch_assoc() : null;
if (!$row) {
	tessera_console_error('not_found', 'Luggage not found', 404);
}

$code = (string) ($row['code'] ?? '');
$codeEsc = $db->real_escape_string($code);
$onManifest = $db->query("SELECT id FROM manifest WHERE code='{$codeEsc}'{$tenantSql} LIMIT 1");
if ($onManifest && $onManifest->num_rows > 0) {
	tessera_console_error('conflict', 'This luggage is on a manifest. Remove it from the manifest before deleting.', 409);
}

$owner = (string) ($row['username'] ?? '');
$isAdmin = $role === 'Administrator' || $role === 'Super Administrator';
if ($owner !== $username && !$isAdmin) {
	tessera_console_error('forbidden', 'Only the uploader or an admin can delete this', 403);
}

date_default_timezone_set('Africa/Dar_es_Salaam');
$dateNow = date('Y-m-d');
$timeNow = date(' h:i A');
$sName = $db->real_escape_string((string) ($row['s_name'] ?? ''));
$rName = $db->real_escape_string((string) ($row['r_name'] ?? ''));
$dateInsert = $db->real_escape_string((string) ($row['date_insert'] ?? ''));
$uploader = $db->real_escape_string((string) ($row['uploader'] ?? ''));
$qty = $db->real_escape_string((string) ($row['question'] ?? '1'));
$toBe = $db->real_escape_string((string) ($row['to_be'] ?? '0'));
$paid = $db->real_escape_string((string) ($row['paid'] ?? '0'));
$amount = $db->real_escape_string((string) ($row['amount'] ?? '0'));
$actor = $db->real_escape_string($username);
$reasonEsc = $db->real_escape_string($reason);
$when = $db->real_escape_string($dateNow . ' at ' . trim($timeNow));

$db->begin_transaction();
try {
	if (!$db->query("DELETE FROM parcel_info WHERE id={$id}{$tenantSql} LIMIT 1")) {
		throw new RuntimeException('Could not delete luggage');
	}
	$db->query(
		"INSERT INTO notification VALUES(
			'','{$sName}','{$rName}','{$id}','{$dateInsert}','{$uploader}','Delete','{$codeEsc}',
			'{$qty}','{$toBe}','{$paid}','{$amount}','deleted','{$actor}','{$actor}','{$when}','','','','{$reasonEsc}'
		)"
	);

	$date_insert = (string) ($row['date_insert'] ?? '');
	$trip = (string) ($row['account'] ?? '');
	$username_original = $owner;
	$status = (string) ($row['status'] ?? '');
	$month = (string) ($row['month'] ?? '');
	$tobe = (string) ($row['to_be'] ?? '0');
	$paid = (string) ($row['paid'] ?? '0');
	$chargesFile = dirname(__DIR__, 2) . '/update/my_charges.php';
	if (is_file($chargesFile)) {
		include $chargesFile;
	}

	$db->commit();
} catch (Throwable $e) {
	$db->rollback();
	tessera_console_error('server_error', $e->getMessage() ?: 'Could not delete luggage', 500);
}

tessera_console_ok([
	'id' => $id,
	'code' => $code,
]);
