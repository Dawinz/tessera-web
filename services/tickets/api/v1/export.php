<?php
/**
 * Partner reporting export. Scoped to the API key's operator.
 * GET ?kind=bookings|cargo&format=json|csv
 */
require_once __DIR__ . '/_bootstrap.php';

$kind = strtolower(trim((string) ($_GET['kind'] ?? 'bookings')));
$format = strtolower(trim((string) ($_GET['format'] ?? 'json')));
if (!in_array($kind, ['bookings', 'cargo'], true)) {
	v1_json(['ok' => false, 'error' => 'kind_invalid'], 400);
}
if (!in_array($format, ['json', 'csv'], true)) {
	v1_json(['ok' => false, 'error' => 'format_invalid'], 400);
}

$scope = $kind === 'cargo' ? 'cargo:read' : 'tickets:read';
$key = v1_require_key($db, $scope);
$tenantId = (int) $key['tenant_id'];
$rows = [];

if ($kind === 'bookings') {
	$stmt = $db->prepare(
		'SELECT id, trip_date, bus, route_id, cost, status, booker
		 FROM booking WHERE tenant_id = ? ORDER BY id DESC LIMIT 100'
	);
	if (!$stmt) {
		v1_json(['ok' => false, 'error' => 'export_query'], 500);
	}
	$stmt->bind_param('i', $tenantId);
	$stmt->execute();
	$res = $stmt->get_result();
	while ($res && ($row = $res->fetch_assoc())) {
		$rows[] = $row;
	}
} else {
	require_once dirname(__DIR__) . '/mobile_cargo_lib.php';
	$cargo = tessera_customer_cargo_db();
	if (!$cargo) {
		v1_json(['ok' => false, 'error' => 'cargo_db'], 503);
	}
	$tenant = tessera_customer_cargo_tenant_sql($cargo);
	$q = $cargo->query(
		"SELECT code, s_name, r_name, account, status, date, paid, to_be, bus
		 FROM parcel_info WHERE 1=1 {$tenant} ORDER BY date DESC LIMIT 100"
	);
	if (!$q) {
		v1_json(['ok' => false, 'error' => 'export_query'], 500);
	}
	while ($row = $q->fetch_assoc()) {
		$rows[] = $row;
	}
}

if ($format === 'csv') {
	header('Content-Type: text/csv; charset=utf-8');
	$out = fopen('php://output', 'w');
	if ($rows) {
		fputcsv($out, array_keys($rows[0]));
		foreach ($rows as $row) {
			fputcsv($out, $row);
		}
	}
	fclose($out);
	exit;
}

v1_json([
	'ok' => true,
	'kind' => $kind,
	'tenant_id' => $tenantId,
	'count' => count($rows),
	'rows' => $rows,
]);
