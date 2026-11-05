<?php
/**
 * Shared Tickets Home dashboard payload (T-P0-06).
 */
declare(strict_types=1);

require_once __DIR__ . '/home_dashboard_route.php';

if (!function_exists('tessera_home_dashboard_payload')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_home_dashboard_payload(mysqli $db, string $routeRaw): array
	{
		date_default_timezone_set('Africa/Dar_es_Salaam');

		$scope = tessera_home_route_scope($db, $routeRaw);
		$query_date = date('Y-m-d');
		$date1 = date('Y-m-01', strtotime($query_date));
		$date2 = date('Y-m-t', strtotime($query_date));
		$month = date('m', strtotime($query_date));
		$year = date('Y', strtotime($query_date));
		$routeSql = $scope['sql_route'];
		$tenantSql = $scope['sql_tenant'];

		$kpiSales = 0.0;
		$kpiSeats = 0;
		$kpiTrips = 0;
		$kpiFoc = 0;

		$wSales = $tenantSql . "book_date >= '{$date1}' AND book_date <= '{$date2}' AND delete_book=''";
		if ($routeSql !== '') {
			$wSales = $routeSql . $wSales;
		}
		$q = $db->query("SELECT SUM(cost) AS cost FROM booking WHERE {$wSales}");
		if ($q && ($r = $q->fetch_assoc())) {
			$kpiSales = (float) ($r['cost'] ?? 0);
		}

		$wSeats = $tenantSql . "trip_date >= '{$date1}' AND trip_date <= '{$date2}' AND status != 'booked' AND delete_book=''";
		if ($routeSql !== '') {
			$wSeats = $routeSql . $wSeats;
		}
		$q = $db->query("SELECT COUNT(*) AS c FROM booking WHERE {$wSeats}");
		if ($q && ($r = $q->fetch_assoc())) {
			$kpiSeats = (int) ($r['c'] ?? 0);
		}

		$wTrips = "trip_date >= '{$date1}' AND trip_date <= '{$date2}'";
		if ($routeSql !== '') {
			$wTrips = $routeSql . $wTrips;
		}
		$q = $db->query("SELECT COUNT(*) AS c FROM manifest WHERE {$wTrips}");
		if ($q && ($r = $q->fetch_assoc())) {
			$kpiTrips = (int) ($r['c'] ?? 0);
		}

		$wFoc = $tenantSql . "trip_date >= '{$date1}' AND trip_date <= '{$date2}' AND month='{$month}' AND year='{$year}' AND cost='0' AND status != 'booked' AND delete_book=''";
		if ($routeSql !== '') {
			$wFoc = $routeSql . $wFoc;
		}
		$q = $db->query("SELECT COUNT(*) AS c FROM booking WHERE {$wFoc}");
		if ($q && ($r = $q->fetch_assoc())) {
			$kpiFoc = (int) ($r['c'] ?? 0);
		}

		$days = [];
		for ($i = 6; $i >= 0; $i--) {
			$days[] = date('Y-m-d', strtotime("-{$i} days"));
		}
		$fromEsc = $db->real_escape_string($days[0]);
		$toEsc = $db->real_escape_string($days[6]);
		$salesByDay = array_fill_keys($days, 0.0);
		$seatsByDay = array_fill_keys($days, 0);
		$tripsByDay = array_fill_keys($days, 0);
		$focByDay = array_fill_keys($days, 0);

		$bookWhere = $tenantSql . "trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}' AND delete_book='' AND status != 'booked'";
		$focWhere = $tenantSql . "trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}' AND delete_book='' AND cost='0' AND status != 'booked'";
		if ($routeSql !== '') {
			$bookWhere = $routeSql . $bookWhere;
			$focWhere = $routeSql . $focWhere;
		}
		$q = $db->query("SELECT trip_date, SUM(cost) AS cost, COUNT(*) AS c FROM booking WHERE {$bookWhere} GROUP BY trip_date");
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$d = (string) ($r['trip_date'] ?? '');
				if (!isset($salesByDay[$d])) {
					continue;
				}
				$salesByDay[$d] = (float) ($r['cost'] ?? 0);
				$seatsByDay[$d] = (int) ($r['c'] ?? 0);
			}
		}
		$q = $db->query("SELECT trip_date, COUNT(*) AS c FROM manifest WHERE trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}' GROUP BY trip_date");
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$d = (string) ($r['trip_date'] ?? '');
				if (isset($tripsByDay[$d])) {
					$tripsByDay[$d] = (int) ($r['c'] ?? 0);
				}
			}
		}
		$q = $db->query("SELECT trip_date, COUNT(*) AS c FROM booking WHERE {$focWhere} GROUP BY trip_date");
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$d = (string) ($r['trip_date'] ?? '');
				if (isset($focByDay[$d])) {
					$focByDay[$d] = (int) ($r['c'] ?? 0);
				}
			}
		}
		$sparkSales = [];
		$sparkSeats = [];
		$sparkTrips = [];
		$sparkFoc = [];
		foreach ($days as $day) {
			$sparkSales[] = $salesByDay[$day];
			$sparkSeats[] = $seatsByDay[$day];
			$sparkTrips[] = $tripsByDay[$day];
			$sparkFoc[] = $focByDay[$day];
		}

		$yearlySeries = array_fill(1, 12, 0.0);
		$yearlyCats = [];
		for ($x = 1; $x <= 12; $x++) {
			$yearlyCats[] = date('F', mktime(0, 0, 0, $x, 10));
		}
		$wYear = $tenantSql . "year='{$year}' AND delete_book=''";
		if ($routeSql !== '') {
			$wYear = $routeSql . $wYear;
		}
		$q = $db->query("SELECT month, SUM(cost) AS cost FROM booking WHERE {$wYear} GROUP BY month");
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$m = (int) ($r['month'] ?? 0);
				if ($m >= 1 && $m <= 12) {
					$yearlySeries[$m] = (float) ($r['cost'] ?? 0);
				}
			}
		}
		$yearlyData = [];
		for ($x = 1; $x <= 12; $x++) {
			$yearlyData[] = $yearlySeries[$x];
		}

		$dailySeries = [];
		$dailyCats = [];
		$wDaily = $tenantSql . "month='{$month}' AND year='{$year}' AND delete_book='' AND status != 'booked'";
		if ($routeSql !== '') {
			$wDaily = $routeSql . $wDaily;
		}
		$q = $db->query("SELECT day, SUM(cost) AS cost FROM booking WHERE {$wDaily} GROUP BY day ORDER BY CAST(day AS UNSIGNED) ASC");
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$dailySeries[] = (float) ($r['cost'] ?? 0);
				$dailyCats[] = (string) ($r['day'] ?? '');
			}
		}
		if (!$dailySeries) {
			$dailySeries = [0];
			$dailyCats = ['-'];
		}

		$busValues = [];
		$busCats = [];
		$wBus = $tenantSql . "month='{$month}' AND year='{$year}' AND delete_book=''";
		if ($routeSql !== '') {
			$wBus = $routeSql . $wBus;
		}
		$q = $db->query("SELECT bus, SUM(cost) AS cost FROM booking WHERE {$wBus} GROUP BY bus ORDER BY bus ASC");
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$busValues[] = (float) ($r['cost'] ?? 0);
				$busCats[] = (string) ($r['bus'] ?? '');
			}
		}
		if (!$busValues) {
			$busValues = [0];
			$busCats = ['No bus sales this month'];
		}

		$routeAmounts = [];
		$routeLabels = [];
		$tenantPlain = tessera_sql_tenant_eq('b');
		$q = $db->query(
			"SELECT b.route_id, SUM(b.cost) AS cost, r.start_route, r.end_route
			 FROM booking b
			 LEFT JOIN routes r ON (r.route_id = b.route_id OR r.id = b.route_id)
			 WHERE b.month='{$month}' AND b.year='{$year}' AND {$tenantPlain}
			 GROUP BY b.route_id, r.start_route, r.end_route
			 ORDER BY cost DESC"
		);
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$routeAmounts[] = (float) ($r['cost'] ?? 0);
				$start = trim((string) ($r['start_route'] ?? ''));
				$end = trim((string) ($r['end_route'] ?? ''));
				$routeLabels[] = ($start !== '' || $end !== '')
					? trim($start . ' to ' . $end)
					: ('Route ' . (string) ($r['route_id'] ?? ''));
			}
		}
		if (!$routeAmounts) {
			$routeAmounts = [0];
			$routeLabels = ['No sales this month'];
		}

		$titleSuffix = $scope['all'] ? 'all routes' : ($scope['route_name'] !== '' ? $scope['route_name'] : 'selected route');

		return [
			'generated_at' => date('c'),
			'period' => [
				'month_label' => date('F Y'),
				'year' => (int) $year,
				'month' => (int) $month,
			],
			'route' => [
				'all' => (bool) $scope['all'],
				'name' => (string) $scope['route_name'],
				'id' => (string) $scope['route_id'],
			],
			'kpis' => [
				'sales' => $kpiSales,
				'sales_label' => number_format($kpiSales, 0) . ' Tsh',
				'seats' => $kpiSeats,
				'seats_label' => $kpiSeats . ' seats',
				'trips' => $kpiTrips,
				'trips_label' => $kpiTrips . ' trips',
				'foc' => $kpiFoc,
				'foc_label' => $kpiFoc . ' seats',
			],
			'sparklines' => [
				'sales' => $sparkSales,
				'seats' => $sparkSeats,
				'trips' => $sparkTrips,
				'foc' => $sparkFoc,
			],
			'yearly' => [
				'series' => $yearlyData,
				'categories' => $yearlyCats,
				'title' => 'Yearly sales - ' . $titleSuffix . ' - ' . $year,
			],
			'daily' => [
				'series' => $dailySeries,
				'categories' => $dailyCats,
			],
			'bus_income' => [
				'series' => $busValues,
				'categories' => $busCats,
			],
			'route_sales' => [
				'series' => $routeAmounts,
				'labels' => $routeLabels,
			],
		];
	}
}

if (!function_exists('tessera_home_route_options')) {
	/**
	 * @return array{routes:list<array{id:string,label:string}>,can_all:bool}
	 */
	function tessera_home_route_options(mysqli $db, string $username): array
	{
		$routes = [];
		$userEsc = mysqli_real_escape_string($db, $username);
		$q = mysqli_query(
			$db,
			"SELECT DISTINCT route_name, route_id FROM assigned_routes
			 WHERE username='$userEsc' AND route_name != ''
			 ORDER BY route_name ASC"
		);
		while ($q && ($row = mysqli_fetch_assoc($q))) {
			$routes[] = [
				'id' => (string) ($row['route_name'] ?? ''),
				'label' => (string) ($row['route_name'] ?? ''),
				'route_id' => (string) ($row['route_id'] ?? ''),
			];
		}

		$role = '';
		$rq = mysqli_query($db, "SELECT role FROM users WHERE username='$userEsc' LIMIT 1");
		if ($rq && ($rr = mysqli_fetch_assoc($rq))) {
			$role = (string) ($rr['role'] ?? '');
		}
		$canAll = in_array($role, ['Super Administrator', 'Administrator'], true);

		if ($routes === [] && $canAll) {
			$aq = mysqli_query(
				$db,
				"SELECT DISTINCT route_name, route_id FROM assigned_routes
				 WHERE route_name != '' ORDER BY route_name ASC LIMIT 80"
			);
			while ($aq && ($row = mysqli_fetch_assoc($aq))) {
				$routes[] = [
					'id' => (string) ($row['route_name'] ?? ''),
					'label' => (string) ($row['route_name'] ?? ''),
					'route_id' => (string) ($row['route_id'] ?? ''),
				];
			}
		}

		return [
			'routes' => $routes,
			'can_all' => $canAll,
			'role' => $role,
		];
	}
}
