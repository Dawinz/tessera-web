<?php
require_once __DIR__ . '/../../includes/tessera_staff_boot.php';
require_once __DIR__ . '/../../includes/route_manager_helpers.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

$routeKey = trim((string) ($_GET['route_key'] ?? ''));
$tenantWhere = tessera_sql_tenant_eq('r');

$vehicles = [];
$vq = mysqli_query($db, "SELECT name FROM vehicle WHERE " . tessera_sql_tenant_eq() . " ORDER BY name ASC LIMIT 500");
while ($vq && ($v = mysqli_fetch_assoc($vq))) {
	$vehicles[] = (string) $v['name'];
}

$stops = [];
$fares = [];
$route = null;

if (tessera_route_manager_tables_ready($db) && $routeKey !== '') {
	$keyEsc = mysqli_real_escape_string($db, $routeKey);
	$tw = tessera_sql_tenant_eq();

	$sq = mysqli_query(
		$db,
		"SELECT rs.id, rs.stop_id, rs.duration, s.name, s.category, s.location
         FROM route_stops rs
         INNER JOIN stops s ON s.id = rs.stop_id AND " . tessera_sql_tenant_eq('s') . "
         WHERE " . tessera_sql_tenant_eq('rs') . " AND rs.route_id = '$keyEsc'
         ORDER BY rs.id ASC"
	);
	while ($sq && ($s = mysqli_fetch_assoc($sq))) {
		$stops[] = [
			'id' => (int) $s['id'],
			'stop_id' => (int) $s['stop_id'],
			'name' => (string) $s['name'],
			'category' => (string) $s['category'],
			'location' => (string) $s['location'],
			'duration' => (string) $s['duration'],
		];
	}

	$fq = mysqli_query(
		$db,
		"SELECT id, class, amount, currency, state, class_id
         FROM route_fair WHERE $tw AND route_id = '$keyEsc' ORDER BY amount ASC"
	);
	while ($fq && ($f = mysqli_fetch_assoc($fq))) {
		$fares[] = [
			'id' => (int) $f['id'],
			'class' => (string) $f['class'],
			'amount' => (float) $f['amount'],
			'currency' => (string) $f['currency'],
			'state' => (string) $f['state'],
			'class_id' => (int) $f['class_id'],
		];
	}
}

$acronymCol = tessera_db_has_column($db, 'routes', 'route_acronym') ? 'r.route_acronym' : "'' AS route_acronym";
$fareCol = tessera_routes_fare_column($db);
$rq = mysqli_query(
	$db,
	"SELECT r.id, r.route_name, r.fullname, r.start_route, r.end_route, r.route_type, $acronymCol, r.{$fareCol} AS fair_price, r.currency_symbol
     FROM routes r WHERE $tenantWhere LIMIT 1000"
);
while ($rq && ($row = mysqli_fetch_assoc($rq))) {
	if (tessera_route_manager_key($row) === $routeKey || ($routeKey === '' && $route === null)) {
		if ($routeKey === '' || tessera_route_manager_key($row) === $routeKey) {
			$route = [
				'id' => (int) $row['id'],
				'route_key' => tessera_route_manager_key($row),
				'label' => tessera_route_manager_label($row),
				'path' => tessera_route_manager_path($row),
				'route_type' => (string) ($row['route_type'] ?? ''),
				'fair_price' => (float) ($row['fair_price'] ?? 0),
				'currency_symbol' => (string) ($row['currency_symbol'] ?? '/='),
				'route_acronym' => (string) ($row['route_acronym'] ?? ''),
			];
			if ($routeKey !== '') {
				break;
			}
		}
	}
}

echo json_encode([
	'route' => $route,
	'vehicles' => $vehicles,
	'stops' => $stops,
	'fares' => $fares,
	'ready' => tessera_route_manager_tables_ready($db),
], JSON_UNESCAPED_UNICODE);
