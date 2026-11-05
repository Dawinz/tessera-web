<?php
/**
 * Shared schedule calendar event builder (legacy feed + console API).
 */
declare(strict_types=1);

if (!function_exists('tessera_schedule_build_events')) {
	/**
	 * @param array{start:string,end:string,starting_location?:string,ending_location?:string,username?:string} $opts
	 * @return list<array<string,mixed>>
	 */
	function tessera_schedule_build_events(mysqli $db, array $opts): array
	{
		date_default_timezone_set('Africa/Dar_es_Salaam');

		$startRaw = (string) ($opts['start'] ?? '');
		$endRaw = (string) ($opts['end'] ?? '');
		$startingLocation = trim((string) ($opts['starting_location'] ?? ''));
		$endingLocation = trim((string) ($opts['ending_location'] ?? ''));
		$username = trim((string) ($opts['username'] ?? ($_SESSION['username'] ?? '')));

		if ($startRaw === '' || $endRaw === '') {
			return [];
		}

		$rangeStart = date('Y-m-d', strtotime($startRaw));
		$rangeEnd = date('Y-m-d', strtotime($endRaw));
		if ($rangeStart === '1970-01-01' || $rangeEnd === '1970-01-01') {
			return [];
		}

		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}

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
		$routesQ = mysqli_query($db, 'SELECT * FROM routes WHERE ' . $routeFilter);
		if ($routesQ) {
			while ($route = mysqli_fetch_assoc($routesQ)) {
				$routeId = (string) ($route['route_id'] ?? '');
				if ($routeId === '' || $routeId === '0') {
					$routeId = (string) ($route['id'] ?? '');
				}
				if ($routeId === '') {
					continue;
				}
				if ($level === 'Agent' && $assignedRouteIds !== [] && !isset($assignedRouteIds[$routeId])) {
					continue;
				}
				$routesById[$routeId] = $route;
			}
		}

		if ($routesById === []) {
			return [];
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
			return [];
		}

		$templates = [];
		while ($rd = mysqli_fetch_assoc($rdQ)) {
			if ((string) ($rd['day'] ?? '') === '') {
				continue;
			}
			$templates[] = $rd;
		}

		$vehicleSeats = [];
		$vehQ = mysqli_query($db, 'SELECT name, seats FROM vehicle WHERE ' . tessera_sql_tenant_eq());
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
			'SELECT trip_date, bus, COUNT(*) AS c FROM booking WHERE ' . tessera_sql_tenant_eq()
			. " AND trip_date BETWEEN '$startEsc' AND '$endEsc' AND (delete_book='' OR delete_book IS NULL)"
			. ' GROUP BY trip_date, bus'
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
				$full = $seatsTotal > 0 && $booked >= $seatsTotal;

				$events[] = [
					'id' => $routeDayId . '-' . $cursor,
					'title' => $title,
					'start' => $startIso,
					'allDay' => ($departure === ''),
					'className' => $full ? 'bg-danger tds-cal-event--full' : 'bg-primary tds-cal-event--open',
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
						'full' => $full,
					],
				];
			}

			$cursor = date('Y-m-d', strtotime($cursor . ' +1 day'));
		}

		return $events;
	}
}

if (!function_exists('tessera_schedule_filter_locations')) {
	/**
	 * @return array{starts:list<string>,ends:list<string>}
	 */
	function tessera_schedule_filter_locations(mysqli $db, string $username = ''): array
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$username = $username !== '' ? $username : (string) ($_SESSION['username'] ?? '');
		$level = '';
		$uq = mysqli_query($db, "SELECT role FROM users WHERE username='" . mysqli_real_escape_string($db, $username) . "' LIMIT 1");
		if ($uq && ($ur = mysqli_fetch_assoc($uq))) {
			$level = (string) ($ur['role'] ?? '');
		}

		$assigned = [];
		if ($level === 'Agent') {
			$aq = mysqli_query($db, "SELECT route_id FROM assigned_routes WHERE username='" . mysqli_real_escape_string($db, $username) . "'");
			if ($aq) {
				while ($ar = mysqli_fetch_assoc($aq)) {
					$rid = trim((string) ($ar['route_id'] ?? ''));
					if ($rid !== '') {
						$assigned[$rid] = true;
					}
				}
			}
		}

		$starts = [];
		$ends = [];
		$q = mysqli_query($db, "SELECT id, route_id, start_route, end_route FROM routes WHERE " . tessera_sql_tenant_eq() . " AND route_type='Main Route'");
		if ($q) {
			while ($r = mysqli_fetch_assoc($q)) {
				$rid = (string) ($r['route_id'] ?? '');
				if ($rid === '' || $rid === '0') {
					$rid = (string) ($r['id'] ?? '');
				}
				if ($level === 'Agent' && $assigned !== [] && !isset($assigned[$rid])) {
					continue;
				}
				$s = trim((string) ($r['start_route'] ?? ''));
				$e = trim((string) ($r['end_route'] ?? ''));
				if ($s !== '') {
					$starts[$s] = true;
				}
				if ($e !== '') {
					$ends[$e] = true;
				}
			}
		}
		$startList = array_keys($starts);
		$endList = array_keys($ends);
		sort($startList, SORT_STRING);
		sort($endList, SORT_STRING);
		return ['starts' => $startList, 'ends' => $endList];
	}
}
