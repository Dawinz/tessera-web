<?php
require_once __DIR__ . '/_bootstrap.php';
v1_require_key($db, 'cargo:read');

$code = trim((string) ($_GET['code'] ?? ''));
if ($code === '') {
	v1_json(['ok' => false, 'error' => 'code_required'], 400);
}

$cargoDbName = getenv('SR_CARGO_DB') ?: 'cargo';
$cargo = function_exists('sr_mysqli') ? sr_mysqli($cargoDbName) : null;
if (!$cargo) {
	/* fallback same server */
	$cfg = sr_db_config($cargoDbName);
	$cargo = @new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
}
if (!$cargo || $cargo->connect_error) {
	v1_json(['ok' => false, 'error' => 'cargo_db'], 503);
}

$code_e = $cargo->real_escape_string($code);
$tid = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 0;
$tenantFilter = '';
$col = @$cargo->query("SHOW COLUMNS FROM parcel_info LIKE 'tenant_id'");
if ($col && $col->num_rows > 0 && $tid > 0) {
	$tenantFilter = ' AND tenant_id=' . $tid;
}
$q = $cargo->query(
	"SELECT code, s_name, r_name, s_no, r_no, account, status, date, paid, to_be, bus, trip_date
	 FROM parcel_info WHERE code='{$code_e}'{$tenantFilter} LIMIT 1"
);
if (!$q) {
	v1_json(['ok' => false, 'error' => 'cargo_query'], 500);
}
$row = $q->fetch_assoc();
if (!$row) {
	v1_json(['ok' => false, 'error' => 'not_found'], 404);
}
v1_json([
	'ok' => true,
	'cargo' => [
		'code' => $row['code'],
		'sender' => $row['s_name'],
		'receiver' => $row['r_name'],
		'sender_phone' => $row['s_no'],
		'receiver_phone' => $row['r_no'],
		'route' => $row['account'],
		'status' => $row['status'],
		'date' => $row['date'],
		'paid' => $row['paid'],
		'to_be' => $row['to_be'],
		'bus' => $row['bus'],
		'trip_date' => $row['trip_date'],
	],
]);
