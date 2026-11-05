<?php
/**
 * GET /tickets/api/console/bookings.php — paginated Book list (T-P0-03).
 *
 * Filters: q, status (complete|booked|half_complete|locked), due=1,
 * start_route, end_route, bus, issuer, trip_date_from, trip_date_to, foc=1, transit=1
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_bookings_list.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$pager = tessera_console_pagination(50, 100);
$sort = tessera_console_sort(
	['id', 'trip_date', 'seat_no', 'firstname', 'bus', 'issuer', 'cost', 'status'],
	'id',
	'desc'
);

$filters = [
	'q' => (string) ($_GET['q'] ?? ''),
	'status' => (string) ($_GET['status'] ?? ''),
	'due' => isset($_GET['due']) && (string) $_GET['due'] === '1',
	'start_route' => (string) ($_GET['start_route'] ?? ''),
	'end_route' => (string) ($_GET['end_route'] ?? ''),
	'bus' => (string) ($_GET['bus'] ?? ''),
	'issuer' => (string) ($_GET['issuer'] ?? ''),
	'trip_date_from' => (string) ($_GET['trip_date_from'] ?? ''),
	'trip_date_to' => (string) ($_GET['trip_date_to'] ?? ''),
	'foc' => isset($_GET['foc']) && (string) $_GET['foc'] === '1',
	'transit' => isset($_GET['transit']) && (string) $_GET['transit'] === '1',
];

$built = tessera_bookings_list_query($db, $filters);
$where = $built['where'];
$sortCol = $sort['sort'];
$order = strtoupper($sort['order']);

$countSql = "SELECT COUNT(*) AS c FROM booking WHERE $where";
$countRes = mysqli_query($db, $countSql);
$total = 0;
if ($countRes && ($cr = mysqli_fetch_assoc($countRes))) {
	$total = (int) ($cr['c'] ?? 0);
}

$offset = $pager['offset'];
$limit = $pager['per_page'];
$sql = "SELECT id, seat_no, firstname, lastname, phone_no, trip_date, bus, issuer, cost,
	status, amount_status, amount_left, book_status, start_route, end_route, route_id,
	route_day_id, print_id, gender, initial, currency
	FROM booking WHERE $where
	ORDER BY `$sortCol` $order
	LIMIT $offset, $limit";

$items = [];
$res = mysqli_query($db, $sql);
if ($res) {
	while ($row = mysqli_fetch_assoc($res)) {
		$items[] = tessera_bookings_row_to_item($row);
	}
}

tessera_console_ok([
	'items' => $items,
	'total' => $total,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
	'sort' => $sort['sort'],
	'order' => $sort['order'],
	'filters' => $built['params_note'],
]);
