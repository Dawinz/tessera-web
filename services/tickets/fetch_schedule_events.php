<?php
/**
 * FullCalendar feed - expand route_day weekday templates into concrete trip events.
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('Africa/Dar_es_Salaam');

$startRaw = isset($_GET['start']) ? (string) $_GET['start'] : '';
$endRaw = isset($_GET['end']) ? (string) $_GET['end'] : '';
$startingLocation = trim((string) ($_GET['starting_location'] ?? ''));
$endingLocation = trim((string) ($_GET['ending_location'] ?? ''));

if ($startRaw === '' || $endRaw === '') {
	echo json_encode([]);
	exit;
}

$rangeStart = date('Y-m-d', strtotime($startRaw));
$rangeEnd = date('Y-m-d', strtotime($endRaw));
if ($rangeStart === '1970-01-01' || $rangeEnd === '1970-01-01') {
	echo json_encode([]);
	exit;
}

$username = $_SESSION['username'] ?? '';
$level = '';
$selectUser = mysqli_query($db, "SELECT role FROM users WHERE username='" . mysqli_real_escape_string($db, $username) . "' LIMIT 1");
if ($selectUser && mysqli_num_rows($selectUser) > 0) {
	$rowUser = mysqli_fetch_assoc($selectUser);
	$level = (string) ($rowUser['role'] ?? '');
}

$assignedRouteIds = [];
if ($level === 'Agent') {
	$assignedQ = mysqli_query($db, "SELECT route_id FROM assigned_routes WHERE username='" . mysqli_real_escape_string($db, $username) . "'");
	if ($assignedQ) {
		while ($ar = mysqli_fetch_assoc($assignedQ)) {
			$rid = trim((string) ($ar['route_id'] ?? ''));
			if ($rid !== '') {
				$assignedRouteIds[$rid] = true;
			}
		}
	}
}

$routeFilter = tessera_sql_tenant_eq() . " AND route_type='Main Route'";
if ($startingLocation !== '' && $startingLocation !== 'Choose Starting Point') {
	$routeFilter .= " AND start_route='" . mysqli_real_escape_string($db, $startingLocation) . "'";
}
if ($endingLocation !== '' && $endingLocation !== 'Choose Ending Point') {
	$routeFilter .= " AND end_route='" . mysqli_real_escape_string($db, $endingLocation) . "'";
}

$routesById = [];
$routesQ = mysqli_query($db, "SELECT * FROM routes WHERE " . $routeFilter);
if ($routesQ) {
	while ($route = mysqli_fetch_assoc($routesQ)) {
		$routeId = (string) ($route['route_id'] ?? '');
		if ($routeId === '' || $routeId === '0') {
			$routeId = (string) ($route['id'] ?? '');
		}
		if ($routeId === '') {
			continue;
		}
		if ($level === 'Agent' && !empty($assignedRouteIds) && !isset($assignedRouteIds[$routeId])) {
			continue;
		}
		$routesById[$routeId] = $route;
	}
}

if ($routesById === []) {
	echo json_encode([]);
	exit;
}

$tenantSql = tessera_sql_tenant_eq('rd');
$idParts = [];
foreach (array_keys($routesById) as $rid) {
	$idParts[] = "'" . mysqli_real_escape_string($db, (string) $rid) . "'";
}
$routeIdList = implode(',', $idParts);

$rdQ = mysqli_query(
	$db,
	"SELECT rd.* FROM route_day rd WHERE " . $tenantSql
	. " AND rd.status='active' AND rd.route_id IN ($routeIdList)"
);
if (!$rdQ) {
	echo json_encode([]);
	exit;
}

$templates = [];
while ($rd = mysqli_fetch_assoc($rdQ)) {
	if ((string) ($rd['day'] ?? '') === '') {
		continue;
	}
	$templates[] = $rd;
}

$vehicleSeats = [];
$vehQ = mysqli_query($db, "SELECT name, seats FROM vehicle WHERE " . tessera_sql_tenant_eq());
if ($vehQ) {
	while ($v = mysqli_fetch_assoc($vehQ)) {
		$vehicleSeats[(string) $v['name']] = (int) ($v['seats'] ?? 0);
	}
}

$bookedMap = [];
$startEsc = mysqli_real_escape_string($db, $rangeStart);
$endEsc = mysqli_real_escape_string($db, $rangeEnd);
$bookedQ = mysqli_query(
	$db,
	"SELECT trip_date, bus, COUNT(*) AS c FROM booking WHERE " . tessera_sql_tenant_eq()
	. " AND trip_date BETWEEN '$startEsc' AND '$endEsc' AND (delete_book='' OR delete_book IS NULL)"
	. " GROUP BY trip_date, bus"
);
if ($bookedQ) {
	while ($b = mysqli_fetch_assoc($bookedQ)) {
		$key = ($b['trip_date'] ?? '') . '|' . ($b['bus'] ?? '');
		$bookedMap[$key] = (int) ($b['c'] ?? 0);
	}
}

$today = date('Y-m-d');
$events = [];
$cursor = $rangeStart;

while ($cursor <= $rangeEnd) {
	if ($level === 'Agent' && $cursor < $today) {
		$cursor = date('Y-m-d', strtotime($cursor . ' +1 day'));
		continue;
	}

	$weekdayLower = strtolower(date('l', strtotime($cursor)));

	foreach ($templates as $rd) {
		if (strtolower((string) ($rd['day'] ?? '')) !== $weekdayLower) {
			continue;
		}

		$routeId = (string) ($rd['route_id'] ?? '');
		if (!isset($routesById[$routeId])) {
			continue;
		}
		$route = $routesById[$routeId];

		$bus = (string) ($rd['bus'] ?? $rd['buss'] ?? '');
		$routeDayId = (int) ($rd['id'] ?? 0);
		if ($routeDayId < 1 || $bus === '') {
			continue;
		}

		$booked = $bookedMap[$cursor . '|' . $bus] ?? 0;
		$seatsTotal = $vehicleSeats[$bus] ?? 0;

		$departure = (string) ($rd['departure'] ?? $rd['deperture'] ?? $route['departure'] ?? '');
		$startIso = $cursor;
		if ($departure !== '') {
			$ts = strtotime($cursor . ' ' . $departure);
			if ($ts !== false) {
				$startIso = date('Y-m-d\TH:i:s', $ts);
			}
		}

		$routeLabel = (string) ($route['fullname'] ?? $route['route_name'] ?? ($route['start_route'] . ' -> ' . $route['end_route']));
		$title = $routeLabel . ' - ' . $bus . ' (' . $booked . '/' . max($seatsTotal, 0) . ')';

		$events[] = [
			'id' => $routeDayId . '-' . $cursor,
			'title' => $title,
			'start' => $startIso,
			'allDay' => ($departure === ''),
			'className' => $booked >= $seatsTotal && $seatsTotal > 0 ? 'bg-danger' : 'bg-primary',
			'extendedProps' => [
				'route_day_id' => $routeDayId,
				'trip_date' => $cursor,
				'trip_date_display' => date('m/d/Y', strtotime($cursor)),
				'route_id' => $routeId,
				'route_id_main' => $routeId,
				'bus' => $bus,
				'fare_price' => (float) ($route['fare_price'] ?? 0),
				'currency_symbol' => (string) ($route['currency_symbol'] ?? ''),
				'start_route' => (string) ($route['start_route'] ?? ''),
				'end_route' => (string) ($route['end_route'] ?? ''),
				'route_name' => $routeLabel,
				'departure' => $departure,
				'arrival' => (string) ($rd['arrival'] ?? $rd['arraival'] ?? ''),
				'seats_booked' => $booked,
				'seats_total' => $seatsTotal,
				'status' => (string) ($rd['status'] ?? ''),
				'weekday' => date('l', strtotime($cursor)),
			],
		];
	}

	$cursor = date('Y-m-d', strtotime($cursor . ' +1 day'));
}

echo json_encode($events);
