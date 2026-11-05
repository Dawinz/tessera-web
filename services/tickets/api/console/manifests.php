<?php
/**
 * GET /tickets/api/console/manifests.php — paginated Manifest list + summary (T-P0-04).
 *
 * Filters: q, route_id, bus, trip_date_from, trip_date_to
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_manifests_list.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$pager = tessera_console_pagination(25, 100);
$sort = tessera_console_sort(
	['trip_date', 'id', 'bus', 'seats', 'amount', 'road_collection'],
	'trip_date',
	'desc'
);

$filters = [
	'q' => (string) ($_GET['q'] ?? ''),
	'route_id' => (string) ($_GET['route_id'] ?? ''),
	'bus' => (string) ($_GET['bus'] ?? ''),
	'trip_date_from' => (string) ($_GET['trip_date_from'] ?? ''),
	'trip_date_to' => (string) ($_GET['trip_date_to'] ?? ''),
];

$built = tessera_manifests_list_query($db, $filters);
$where = $built['where'];
$sortCol = $sort['sort'];
$order = strtoupper($sort['order']);

$countRes = mysqli_query($db, "SELECT COUNT(*) AS c FROM manifest WHERE $where");
$total = 0;
if ($countRes && ($cr = mysqli_fetch_assoc($countRes))) {
	$total = (int) ($cr['c'] ?? 0);
}

$summary = [
	'trips' => $total,
	'buses' => 0,
	'seats' => 0,
	'sales' => 0.0,
	'road' => 0.0,
];
$sumRes = mysqli_query(
	$db,
	"SELECT COUNT(DISTINCT bus) AS buses, COALESCE(SUM(seats),0) AS seats,
	 COALESCE(SUM(amount),0) AS sales, COALESCE(SUM(road_collection),0) AS road
	 FROM manifest WHERE $where"
);
if ($sumRes && ($sr = mysqli_fetch_assoc($sumRes))) {
	$summary['buses'] = (int) ($sr['buses'] ?? 0);
	$summary['seats'] = (int) ($sr['seats'] ?? 0);
	$summary['sales'] = (float) ($sr['sales'] ?? 0);
	$summary['road'] = (float) ($sr['road'] ?? 0);
}

$offset = $pager['offset'];
$limit = $pager['per_page'];
$sql = "SELECT id, bus, trip_date, seats, amount, road_collection, canceled, start_location, end_location,
	start_time, route_day_id, driver, conductor, status, route_id, route_name
	FROM manifest WHERE $where
	ORDER BY `$sortCol` $order, id DESC
	LIMIT $offset, $limit";

$items = [];
$res = mysqli_query($db, $sql);
if ($res) {
	while ($row = mysqli_fetch_assoc($res)) {
		$items[] = tessera_manifest_row_to_item($row);
	}
}

tessera_console_ok([
	'items' => $items,
	'total' => $total,
	'summary' => $summary,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
	'sort' => $sort['sort'],
	'order' => $sort['order'],
	'filters' => $built['params_note'],
]);
