<?php
require_once __DIR__ . '/../../includes/tessera_staff_boot.php';
require_once __DIR__ . '/../../includes/route_manager_helpers.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

if (!tessera_route_manager_tables_ready($db)) {
	echo json_encode(['routes' => [], 'ready' => false, 'error' => 'schema_pending'], JSON_UNESCAPED_UNICODE);
	exit;
}

$tenantWhere = tessera_sql_tenant_eq('r');
$acronymCol = tessera_db_has_column($db, 'routes', 'route_acronym') ? 'r.route_acronym' : "'' AS route_acronym";
$fareCol = tessera_routes_fare_column($db);
$sql = "SELECT r.id, r.route_name, r.fullname, r.start_route, r.end_route, r.route_type, r.main_id,
               r.{$fareCol} AS fair_price, r.currency_symbol, $acronymCol
        FROM routes r
        WHERE $tenantWhere
        ORDER BY r.route_type DESC, r.fullname ASC, r.route_name ASC
        LIMIT 1000";

$rows = [];
$q = mysqli_query($db, $sql);
if (!$q) {
	echo json_encode([
		'routes' => [],
		'ready' => true,
		'error' => 'query_failed',
		'detail' => mysqli_error($db),
	], JSON_UNESCAPED_UNICODE);
	exit;
}
while ($row = mysqli_fetch_assoc($q)) {
	$key = tessera_route_manager_key($row);
	$row['route_key'] = $key;
	$row['label'] = tessera_route_manager_label($row);
	$row['path'] = tessera_route_manager_path($row);
	$row['is_main'] = (strcasecmp((string) ($row['route_type'] ?? ''), 'Main Route') === 0)
		|| trim((string) ($row['main_id'] ?? '0')) === '0'
		|| trim((string) ($row['main_id'] ?? '')) === '';
	$rows[] = $row;
}

$keyEsc = [];
foreach ($rows as $row) {
	$keyEsc[] = "'" . mysqli_real_escape_string($db, $row['route_key']) . "'";
}

$scheduleCounts = [];
$fareMins = [];
if ($keyEsc) {
	$in = implode(',', $keyEsc);
	$tw = tessera_sql_tenant_eq();
	$sq = mysqli_query(
		$db,
		"SELECT route_id, COUNT(*) AS c FROM route_schedules WHERE $tw AND route_id IN ($in) GROUP BY route_id"
	);
	while ($sq && ($s = mysqli_fetch_assoc($sq))) {
		$scheduleCounts[$s['route_id']] = (int) $s['c'];
	}
	$fq = mysqli_query(
		$db,
		"SELECT route_id, MIN(amount) AS min_amount, MAX(amount) AS max_amount
         FROM route_fair WHERE $tw AND route_id IN ($in) GROUP BY route_id"
	);
	while ($fq && ($f = mysqli_fetch_assoc($fq))) {
		$fareMins[$f['route_id']] = $f;
	}
}

$subCounts = [];
foreach ($rows as $row) {
	if (!empty($row['is_main'])) {
		$mainId = (string) $row['id'];
		$subCounts[$mainId] = 0;
	}
}
foreach ($rows as $row) {
	if (empty($row['is_main'])) {
		$parent = trim((string) ($row['main_id'] ?? ''));
		if ($parent !== '' && isset($subCounts[$parent])) {
			$subCounts[$parent]++;
		}
	}
}

$out = [];
foreach ($rows as $row) {
	$key = $row['route_key'];
	$fare = $fareMins[$key] ?? null;
	$out[] = [
		'id' => (int) $row['id'],
		'route_key' => $key,
		'label' => $row['label'],
		'path' => $row['path'],
		'route_name' => (string) ($row['route_name'] ?? ''),
		'route_type' => (string) ($row['route_type'] ?? ''),
		'is_main' => !empty($row['is_main']),
		'main_id' => (string) ($row['main_id'] ?? '0'),
		'schedule_count' => $scheduleCounts[$key] ?? 0,
		'sub_route_count' => $subCounts[(string) $row['id']] ?? 0,
		'fare_min' => $fare ? (float) $fare['min_amount'] : (float) ($row['fair_price'] ?? 0),
		'fare_max' => $fare ? (float) $fare['max_amount'] : (float) ($row['fair_price'] ?? 0),
		'currency_symbol' => (string) ($row['currency_symbol'] ?? '/='),
		'route_acronym' => (string) ($row['route_acronym'] ?? ''),
	];
}

echo json_encode(['routes' => $out, 'ready' => true], JSON_UNESCAPED_UNICODE);
