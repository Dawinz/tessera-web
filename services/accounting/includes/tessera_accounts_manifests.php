<?php
/**
 * Shared Accounts manifest list helpers (A-P0-01).
 */
declare(strict_types=1);

if (!function_exists('tessera_accounts_manifests_require_tenant')) {
	function tessera_accounts_manifests_require_tenant(): void
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once dirname(__DIR__) . '/template/includes/tessera_tenant.php';
		}
	}
}

if (!function_exists('tessera_accounts_manifests_num')) {
	function tessera_accounts_manifests_num($value): float
	{
		if (is_int($value) || is_float($value)) {
			return (float) $value;
		}
		$clean = preg_replace('/[^0-9.\-]+/', '', (string) $value);
		if ($clean === null || $clean === '' || $clean === '-' || $clean === '.') {
			return 0.0;
		}
		return (float) $clean;
	}
}

if (!function_exists('tessera_accounts_manifests_assigned_routes')) {
	/**
	 * @return list<string>
	 */
	function tessera_accounts_manifests_assigned_routes(mysqli $db, string $username): array
	{
		$u = $db->real_escape_string($username);
		$out = [];
		$q = $db->query(
			"SELECT route_name FROM assign_route
			 WHERE username='{$u}' AND status='main route'
			 ORDER BY id DESC"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$name = trim((string) ($row['route_name'] ?? ''));
				if ($name !== '' && !in_array($name, $out, true)) {
					$out[] = $name;
				}
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_manifests_decode_route_label')) {
	function tessera_accounts_manifests_decode_route_label(string $fullname, string $stored): string
	{
		$label = trim($stored);
		$decoded = base64_decode($label, true);
		if ($decoded !== false && $decoded !== '' && preg_match('/^[\w\s.\/\-]+$/u', $decoded)) {
			$label = $decoded;
		}
		if ($label === '') {
			$label = $fullname;
		}
		return $label;
	}
}

if (!function_exists('tessera_accounts_manifests_filters')) {
	/**
	 * @return array{routes:list<array{name:string,label:string}>,vehicles:list<string>,assigned_routes:list<string>}
	 */
	function tessera_accounts_manifests_filters(mysqli $db, string $username): array
	{
		$assigned = tessera_accounts_manifests_assigned_routes($db, $username);
		$routes = [];
		$q = $db->query('SELECT fullname, name FROM route ORDER BY fullname ASC');
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$fullname = trim((string) ($row['fullname'] ?? ''));
				if ($fullname === '') {
					continue;
				}
				$routes[] = [
					'name' => $fullname,
					'label' => tessera_accounts_manifests_decode_route_label(
						$fullname,
						(string) ($row['name'] ?? '')
					),
				];
			}
		}

		$vehicles = [];
		if (function_exists('cargo_db')) {
			$cargo = cargo_db();
			$vq = $cargo->query('SELECT name FROM vehicles ORDER BY name ASC');
			if ($vq) {
				while ($row = $vq->fetch_assoc()) {
					$name = trim((string) ($row['name'] ?? ''));
					if ($name !== '') {
						$vehicles[] = $name;
					}
				}
			}
		} else {
			$vq = $db->query('SELECT DISTINCT bus FROM manifest WHERE bus<>\'\' ORDER BY bus ASC');
			if ($vq) {
				while ($row = $vq->fetch_assoc()) {
					$name = trim((string) ($row['bus'] ?? ''));
					if ($name !== '') {
						$vehicles[] = $name;
					}
				}
			}
		}

		return [
			'routes' => $routes,
			'vehicles' => $vehicles,
			'assigned_routes' => $assigned,
		];
	}
}

if (!function_exists('tessera_accounts_manifests_trip_dates')) {
	/**
	 * Trip dates available on cargo vehicle_name for a route.
	 *
	 * @return list<array{date:string,date_label:string}>
	 */
	function tessera_accounts_manifests_trip_dates(string $route): array
	{
		$route = trim($route);
		if ($route === '' || !function_exists('cargo_db')) {
			return [];
		}
		$cargo = cargo_db();
		$r = $cargo->real_escape_string($route);
		$out = [];
		$q = $cargo->query(
			"SELECT `date` FROM vehicle_name WHERE Route='{$r}' GROUP BY `date` ORDER BY `date` DESC LIMIT 120"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$date = (string) ($row['date'] ?? '');
				if ($date === '') {
					continue;
				}
				$ts = strtotime($date);
				$out[] = [
					'date' => $date,
					'date_label' => $ts ? date('D, d M Y', $ts) : $date,
				];
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_manifests_build_where')) {
	/**
	 * @param array{q?:string,route?:string,vehicle?:string,date_from?:string,date_to?:string} $filters
	 * @param list<string> $assignedRoutes
	 * @return array{where:string,order:string}
	 */
	function tessera_accounts_manifests_build_where(mysqli $db, array $filters, array $assignedRoutes): array
	{
		tessera_accounts_manifests_require_tenant();
		$parts = [tessera_sql_tenant_eq()];

		$route = trim((string) ($filters['route'] ?? ''));
		if ($route !== '') {
			$parts[] = "route_name='" . $db->real_escape_string($route) . "'";
		} elseif ($assignedRoutes !== []) {
			$escaped = array_map(
				static fn(string $r): string => "'" . $db->real_escape_string($r) . "'",
				$assignedRoutes
			);
			$parts[] = 'route_name IN (' . implode(',', $escaped) . ')';
		}

		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		if ($vehicle !== '') {
			$parts[] = "bus='" . $db->real_escape_string($vehicle) . "'";
		}

		$from = trim((string) ($filters['date_from'] ?? ''));
		if ($from !== '' && (int) date('Y', strtotime($from) ?: 0) >= 2000) {
			$parts[] = "trip_date>='" . $db->real_escape_string(date('Y-m-d', strtotime($from))) . "'";
		}
		$to = trim((string) ($filters['date_to'] ?? ''));
		if ($to !== '' && (int) date('Y', strtotime($to) ?: 0) >= 2000) {
			$parts[] = "trip_date<='" . $db->real_escape_string(date('Y-m-d', strtotime($to))) . "'";
		}

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(code LIKE '{$like}' OR bus LIKE '{$like}' OR route_name LIKE '{$like}' OR trip_date LIKE '{$like}')";
		}

		$order = 'trip_date DESC, id DESC';
		if ($from !== '' || $to !== '') {
			$order = 'trip_date ASC, id ASC';
		}

		return ['where' => implode(' AND ', $parts), 'order' => $order];
	}
}

if (!function_exists('tessera_accounts_manifests_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_accounts_manifests_row_to_item(array $row): array
	{
		$id = (int) ($row['id'] ?? 0);
		$route = (string) ($row['route_name'] ?? '');
		$bus = (string) ($row['bus'] ?? '');
		$tripDate = (string) ($row['trip_date'] ?? '');
		$checked = (string) ($row['checked'] ?? '');
		$analysed = trim((string) ($row['analysed'] ?? ''));
		$income = tessera_accounts_manifests_num($row['income'] ?? 0);
		$expenses = tessera_accounts_manifests_num($row['expenses'] ?? 0);
		$balance = $income - $expenses;
		$ts = strtotime($tripDate);
		$dateLabel = $ts ? date('D j M Y', $ts) : $tripDate;

		$badge = 'neutral';
		$statusLabel = 'Open';
		if ($analysed === 'Complete') {
			$badge = 'paid';
			$statusLabel = 'Complete';
		} elseif ($analysed === 'Half Checked' || $analysed !== '') {
			$badge = 'warn';
			$statusLabel = $analysed !== '' ? $analysed : 'In progress';
		}

		$qs = http_build_query([
			'route_name' => $route,
			'bus' => $bus,
			'trip_date' => $tripDate,
			'checked' => base64_encode($checked),
			'main_page' => 'manifest.php',
		]);

		return [
			'id' => $id,
			'code' => (string) ($row['code'] ?? ''),
			'route' => $route,
			'vehicle' => $bus,
			'date' => $tripDate,
			'date_label' => $dateLabel,
			'income' => $income,
			'expenses' => $expenses,
			'balance' => $balance,
			'ticket' => tessera_accounts_manifests_num($row['ticket'] ?? 0),
			'cargo' => tessera_accounts_manifests_num($row['amount'] ?? 0),
			'analysed' => $analysed,
			'status_badge' => $badge,
			'status_label' => $statusLabel,
			'can_edit' => $analysed === '',
			'compose_url' => '/operator/app.php?module=accounts&page=compose_vehicle.php&' . $qs,
			'view_url' => '/accounts/manifest_view.php?' . $qs,
		];
	}
}

if (!function_exists('tessera_accounts_manifests_list')) {
	/**
	 * @param array{q?:string,route?:string,vehicle?:string,date_from?:string,date_to?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array{count:int,income:float,expenses:float,balance:float}}
	 */
	function tessera_accounts_manifests_list(
		mysqli $db,
		string $username,
		array $filters,
		int $page,
		int $perPage
	): array {
		$assigned = tessera_accounts_manifests_assigned_routes($db, $username);
		$built = tessera_accounts_manifests_build_where($db, $filters, $assigned);
		$where = $built['where'];
		$order = $built['order'];

		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM manifest WHERE {$where}");
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$total = (int) ($cr['c'] ?? 0);
		}

		$summary = ['count' => $total, 'income' => 0.0, 'expenses' => 0.0, 'balance' => 0.0];
		$sq = $db->query(
			"SELECT COALESCE(SUM(income),0) AS income, COALESCE(SUM(expenses),0) AS expenses
			 FROM manifest WHERE {$where}"
		);
		if ($sq && ($sr = $sq->fetch_assoc())) {
			$summary['income'] = tessera_accounts_manifests_num($sr['income'] ?? 0);
			$summary['expenses'] = tessera_accounts_manifests_num($sr['expenses'] ?? 0);
			$summary['balance'] = $summary['income'] - $summary['expenses'];
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$items = [];
		$sql = "SELECT id, trip_date, open_date, code, analysed, route_name, to_be, paid, amount,
			expenses, income, ticket, bus, icon, checked
			FROM manifest WHERE {$where} ORDER BY {$order} LIMIT {$offset}, {$perPage}";
		$q = $db->query($sql);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$items[] = tessera_accounts_manifests_row_to_item($row);
			}
		}

		return ['items' => $items, 'total' => $total, 'summary' => $summary];
	}
}

if (!function_exists('tessera_accounts_manifests_fetch_one')) {
	/**
	 * @return array<string,mixed>|null
	 */
	function tessera_accounts_manifests_fetch_one(mysqli $db, int $id): ?array
	{
		tessera_accounts_manifests_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		$q = $db->query(
			"SELECT id, trip_date, open_date, code, analysed, route_name, to_be, paid, amount,
				expenses, income, ticket, bus, icon, checked
			 FROM manifest WHERE {$tenant} AND id={$id} LIMIT 1"
		);
		if (!$q) {
			return null;
		}
		$row = $q->fetch_assoc();
		return $row ?: null;
	}
}

if (!function_exists('tessera_accounts_manifests_create')) {
	/**
	 * Register an accounts manifest for a bus+trip (mirrors insert/manifest.php core).
	 *
	 * @param array{route:string,date:string,vehicle:string,vehicle_type?:string} $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_manifests_create(mysqli $db, string $username, array $input): array
	{
		tessera_accounts_manifests_require_tenant();
		date_default_timezone_set('Africa/Dar_es_Salaam');

		$route = trim((string) ($input['route'] ?? ''));
		$tripDate = trim((string) ($input['date'] ?? ''));
		$vehicle = trim((string) ($input['vehicle'] ?? ''));
		$vehicleType = trim((string) ($input['vehicle_type'] ?? 'Bus'));
		if ($vehicleType === '') {
			$vehicleType = 'Bus';
		}
		if ($route === '' || $tripDate === '' || $vehicle === '') {
			return ['ok' => false, 'error' => 'Route, trip date, and vehicle are required'];
		}
		$ts = strtotime($tripDate);
		if (!$ts || (int) date('Y', $ts) < 2000) {
			return ['ok' => false, 'error' => 'Invalid trip date'];
		}
		$tripDate = date('Y-m-d', $ts);
		$month = date('F', $ts);
		$year = date('Y', $ts);

		$vEsc = $db->real_escape_string($vehicle);
		$dEsc = $db->real_escape_string($tripDate);
		$mEsc = $db->real_escape_string($month);
		$yEsc = $db->real_escape_string($year);
		$tenant = tessera_sql_tenant_eq();

		$dup = $db->query(
			"SELECT id FROM manifest WHERE {$tenant} AND bus='{$vEsc}' AND trip_date='{$dEsc}'
			 AND month='{$mEsc}' AND year='{$yEsc}' LIMIT 1"
		);
		if ($dup && $dup->num_rows > 0) {
			return ['ok' => false, 'error' => 'Manifest already exists for this vehicle and date'];
		}

		$toPay = 0.0;
		$paid = 0.0;
		$code = '';
		$parcelDetail = '';
		if (function_exists('cargo_db')) {
			$cargo = cargo_db();
			$cq = $cargo->query(
				"SELECT to_pay, paid, code FROM vehicle_name
				 WHERE Name='{$vEsc}' AND date='{$dEsc}' LIMIT 1"
			);
			if ($cq && ($cr = $cq->fetch_assoc())) {
				$toPay = tessera_accounts_manifests_num($cr['to_pay'] ?? 0);
				$paid = tessera_accounts_manifests_num($cr['paid'] ?? 0);
				$code = (string) ($cr['code'] ?? '');
				$tamount = $toPay + $paid;
				$db->query(
					"INSERT INTO route_manifest (to_be, paid, amount, location, bus, trip_date, main_location)
					 VALUES ({$toPay}, {$paid}, {$tamount}, '" . $db->real_escape_string($route) . "',
					 '{$vEsc}', '{$dEsc}', '" . $db->real_escape_string($route) . "')"
				);
				$parcelDetail = "<a href='/cargo/vehicle_report_new.php?date={$tripDate}&date2={$tripDate}&date3="
					. rawurlencode($vehicle) . "&open=open'>Paid cargo</a>";
			}
		}

		$totalTicket = 0.0;
		$routeDayId = '';
		$ticketManifestId = '';
		$ticketCfg = function_exists('sr_db_config') ? sr_db_config('ticket') : null;
		if (is_array($ticketCfg)) {
			$tdb = @new mysqli(
				(string) $ticketCfg['host'],
				(string) $ticketCfg['user'],
				(string) $ticketCfg['pass'],
				(string) $ticketCfg['name'],
				(int) $ticketCfg['port']
			);
			if (!$tdb->connect_error) {
				$tdb->set_charset('utf8mb4');
				$tq = $tdb->query(
					"SELECT cost FROM booking WHERE " . tessera_sql_tenant_eq()
					. " AND bus='{$vEsc}' AND trip_date='{$dEsc}' AND cost<>'0'"
				);
				if ($tq) {
					while ($tr = $tq->fetch_assoc()) {
						$totalTicket += tessera_accounts_manifests_num($tr['cost'] ?? 0);
					}
				}
				$tm = $tdb->query(
					"SELECT id, route_day_id FROM manifest WHERE bus='{$vEsc}' AND trip_date='{$dEsc}' LIMIT 1"
				);
				if ($tm && ($tmr = $tm->fetch_assoc())) {
					$routeDayId = (string) ($tmr['route_day_id'] ?? '');
					$ticketManifestId = (string) ($tmr['id'] ?? '');
				}
				$tdb->close();
			}
		}

		$startPoint = '';
		$endPoint = '';
		$rEsc = $db->real_escape_string($route);
		$rq = $db->query("SELECT start_point, end_point FROM route WHERE fullname='{$rEsc}' LIMIT 1");
		if ($rq && ($rr = $rq->fetch_assoc())) {
			$startPoint = (string) ($rr['start_point'] ?? '');
			$endPoint = (string) ($rr['end_point'] ?? '');
		}
		$locationStart = $startPoint !== '' ? $startPoint : $route;

		$insertTime = date('Y-m-d') . ' at ' . date('h:i A');
		$lockIcon = 'typcn-lock-closed-outline';
		$expenses = 0.0;
		$income = 0.0;
		$balance = 0.0;
		$totalCargo = 0.0;
		$checked = '';
		$checkedCount = '0';
		$password = '';
		$analysed = '';
		$changed = '';
		$changedUser = '';
		$changeTime = '';
		$icon = '';
		$tenantId = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 1);
		$openEsc = $db->real_escape_string($insertTime);
		$locEsc = $db->real_escape_string($locationStart);
		$codeEsc = $db->real_escape_string($code);
		$lockEsc = $db->real_escape_string($lockIcon);

		$ins = $db->query(
			"INSERT INTO manifest (
				bus, trip_date, open_date, route_name, to_be, paid, amount, expenses, income, ticket, balance,
				checked, checked_count, lock_icon, password, analysed, month, year, location_start,
				changed, changed_user, change_time, icon, code, tenant_id
			) VALUES (
				'{$vEsc}', '{$dEsc}', '{$openEsc}', '{$rEsc}', {$toPay}, {$paid}, {$totalCargo}, {$expenses},
				{$income}, {$totalTicket}, {$balance}, '', '0', '{$lockEsc}', '', '', '{$mEsc}', '{$yEsc}',
				'{$locEsc}', '', '', '', '', '{$codeEsc}', {$tenantId}
			)"
		);
		if (!$ins) {
			return ['ok' => false, 'error' => 'Failed to create manifest'];
		}
		$newId = (int) $db->insert_id;

		$spEsc = $db->real_escape_string($startPoint);
		$epEsc = $db->real_escape_string($endPoint);
		$db->query(
			"INSERT INTO manifest_entries (paid, to_be, trip_date, bus, main_route, route_name, start_route, end_route)
			 VALUES ({$paid}, {$toPay}, '{$dEsc}', '{$vEsc}', '{$rEsc}', '{$rEsc}', '{$spEsc}', '{$epEsc}')"
		);

		/* Seed vehicle_account income/expense lines (legacy vehicle_account.php). */
		$figureName = $username;
		$uq = $db->query(
			"SELECT firstname, surname FROM users WHERE username='" . $db->real_escape_string($username) . "' LIMIT 1"
		);
		if ($uq && ($ur = $uq->fetch_assoc())) {
			$figureName = trim((string) ($ur['firstname'] ?? '') . ' ' . (string) ($ur['surname'] ?? ''));
			if ($figureName === '') {
				$figureName = $username;
			}
		}
		$figEsc = $db->real_escape_string($figureName);
		$userEsc = $db->real_escape_string($username);
		$dateInsert = $db->real_escape_string(date('Y-m-d'));
		$timeInsert = $db->real_escape_string(date('h:i A'));
		$monthName = $db->real_escape_string(date('F'));
		$yearNow = $db->real_escape_string(date('Y'));
		$pointEsc = $db->real_escape_string($startPoint);

		if ($paid != 0.0) {
			$detailEsc = $db->real_escape_string($parcelDetail);
			$db->query(
				"INSERT INTO vehicle_account
				(category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month,current_route,year,figure_user,username)
				VALUES ('Cargo','Paid',{$paid},'Paid',{$paid},'{$dateInsert}','{$timeInsert}','Income','{$vEsc}','{$dEsc}','{$rEsc}','{$pointEsc}','{$detailEsc}','Tsh','/=','api','{$monthName}','{$rEsc}','{$yearNow}','{$figEsc}','{$userEsc}')"
			);
		}
		if ($totalTicket != 0.0) {
			$detail = $routeDayId !== ''
				? "<a href='/tickets/sales_summary.php?id={$routeDayId}&trip_date={$tripDate}&manifest={$ticketManifestId}'>Tickets</a>"
				: '';
			$detailEsc = $db->real_escape_string($detail);
			$db->query(
				"INSERT INTO vehicle_account
				(category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month,current_route,year,figure_user,username)
				VALUES ('Passangers','Tickets',{$totalTicket},'Paid',{$totalTicket},'{$dateInsert}','{$timeInsert}','Income','{$vEsc}','{$dEsc}','{$rEsc}','{$pointEsc}','{$detailEsc}','Tsh','/=','api','{$monthName}','{$rEsc}','{$yearNow}','{$figEsc}','{$userEsc}')"
			);
		}

		$totalExpenses = 0.0;
		$vtEsc = $db->real_escape_string($vehicleType);
		$cq = $db->query(
			"SELECT price_value, name, name_category, status, affect, currency
			 FROM constants WHERE route_name='{$rEsc}' AND route_category='Main' AND vehicle='{$vtEsc}'"
		);
		if ($cq) {
			while ($cr = $cq->fetch_assoc()) {
				$price = tessera_accounts_manifests_num($cr['price_value'] ?? 0);
				$nameCat = $db->real_escape_string((string) ($cr['name_category'] ?? ''));
				$name = $db->real_escape_string((string) ($cr['name'] ?? ''));
				$statusExp = $db->real_escape_string((string) ($cr['status'] ?? ''));
				$affectExp = $db->real_escape_string((string) ($cr['affect'] ?? ''));
				$curr = $db->real_escape_string((string) ($cr['currency'] ?? 'Tsh'));
				$db->query(
					"INSERT INTO vehicle_account
					(category,name,expenses,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,currency,currency_sign,month,source,current_route,year,figure_user,username)
					VALUES ('{$nameCat}','{$name}',{$price},'{$affectExp}',{$price},'{$dateInsert}','{$timeInsert}','{$statusExp}','{$vEsc}','{$dEsc}','{$rEsc}','{$pointEsc}','{$curr}','/=','{$monthName}','constants','{$rEsc}','{$yearNow}','{$figEsc}','{$userEsc}')"
				);
				$totalExpenses += $price;
			}
		}

		if ($totalExpenses > 0 || $totalTicket > 0 || $paid > 0) {
			$incomeTotal = $paid + $totalTicket;
			$bal = $incomeTotal - $totalExpenses;
			$db->query(
				"UPDATE manifest SET income={$incomeTotal}, expenses={$totalExpenses}, balance={$bal}, ticket={$totalTicket}
				 WHERE id={$newId} LIMIT 1"
			);
		}

		$row = tessera_accounts_manifests_fetch_one($db, $newId);
		return [
			'ok' => true,
			'item' => $row ? tessera_accounts_manifests_row_to_item($row) : null,
		];
	}
}

if (!function_exists('tessera_accounts_manifests_update')) {
	/**
	 * @param array{id:int,route?:string,date?:string,vehicle?:string} $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_manifests_update(mysqli $db, array $input): array
	{
		$id = (int) ($input['id'] ?? 0);
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Manifest id required'];
		}
		$existing = tessera_accounts_manifests_fetch_one($db, $id);
		if ($existing === null) {
			return ['ok' => false, 'error' => 'Manifest not found'];
		}
		if (trim((string) ($existing['analysed'] ?? '')) !== '') {
			return ['ok' => false, 'error' => 'Manifest is analysed and cannot be edited'];
		}

		$route = trim((string) ($input['route'] ?? $existing['route_name'] ?? ''));
		$tripDate = trim((string) ($input['date'] ?? $existing['trip_date'] ?? ''));
		$vehicle = trim((string) ($input['vehicle'] ?? $existing['bus'] ?? ''));
		$ts = strtotime($tripDate);
		if (!$ts || (int) date('Y', $ts) < 2000) {
			return ['ok' => false, 'error' => 'Invalid trip date'];
		}
		$tripDate = date('Y-m-d', $ts);

		$orgRoute = (string) ($existing['route_name'] ?? '');
		$orgBus = (string) ($existing['bus'] ?? '');
		$orgDate = (string) ($existing['trip_date'] ?? '');

		if ($route !== $orgRoute) {
			return ['ok' => false, 'error' => 'Changing route is not supported here — use classic update'];
		}

		$vEsc = $db->real_escape_string($vehicle);
		$dEsc = $db->real_escape_string($tripDate);
		$orgV = $db->real_escape_string($orgBus);
		$orgD = $db->real_escape_string($orgDate);
		$rEsc = $db->real_escape_string($orgRoute);

		$db->query(
			"UPDATE vehicle_account SET bus='{$vEsc}', trip_date='{$dEsc}'
			 WHERE route_name='{$rEsc}' AND bus='{$orgV}' AND trip_date='{$orgD}'"
		);
		$ok = $db->query(
			"UPDATE manifest SET bus='{$vEsc}', trip_date='{$dEsc}' WHERE id={$id} LIMIT 1"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Failed to update manifest'];
		}

		$row = tessera_accounts_manifests_fetch_one($db, $id);
		return [
			'ok' => true,
			'item' => $row ? tessera_accounts_manifests_row_to_item($row) : null,
		];
	}
}
