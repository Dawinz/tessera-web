<?php
/**
 * GET /tickets/api/console/seats.php?route_day_id=&trip_date=&route_id=
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/schedule_trip_from_request.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_schedule_seats.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
tessera_console_require_capability($db, $username, 'tickets');

$trip = schedule_trip_from_request($db);
if ($trip === null) {
	tessera_console_error('not_found', 'Trip not found', 404);
}

$map = tessera_schedule_seat_map($db, $trip);

$subRoutes = [];
$pk = (int) ($trip['routes_pk'] ?? 0);
$mainNo = (int) ($trip['main_route_id'] ?? 0);
$routeIdInt = (int) ($trip['route_id'] ?? 0);
$ids = array_values(array_unique(array_filter([$pk, $mainNo, $routeIdInt], static fn ($n) => $n !== 0)));
if ($ids !== []) {
	$idList = implode(',', array_map('intval', $ids));
	$sq = mysqli_query(
		$db,
		"SELECT id, sub_route_id, start_route, end_route, fare_price, currency_symbol, route_name, name
		 FROM sub_route
		 WHERE main_route_no IN ($idList)
		 ORDER BY start_route ASC, end_route ASC, id ASC"
	);
	if ($sq) {
		while ($row = mysqli_fetch_assoc($sq)) {
			$labelStart = (string) ($row['start_route'] ?? '');
			$labelEnd = (string) ($row['end_route'] ?? '');
			$subRoutes[] = [
				'id' => (string) ($row['id'] ?? ''),
				'sub_route_id' => (string) ($row['sub_route_id'] ?? $row['id'] ?? ''),
				'start_route' => $labelStart,
				'end_route' => $labelEnd,
				'fare_price' => (float) ($row['fare_price'] ?? 0),
				'currency_symbol' => (string) ($row['currency_symbol'] ?? ''),
				'label' => trim($labelStart . ' → ' . $labelEnd),
				'route_name' => (string) ($row['route_name'] ?? $row['name'] ?? ''),
			];
		}
	}
}

require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_network.php';
tessera_console_ok([
	'trip' => $trip,
	'seats' => $map,
	'sub_routes' => $subRoutes,
	'can_manage_template' => tessera_tickets_can_manage_template($db, $username),
]);
