<?php
/**
 * Shared Accounts compose / vehicle ledger helpers (A-P0-02).
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/template/includes/assign_route_points.php';

if (!function_exists('tessera_accounts_compose_require_tenant')) {
	function tessera_accounts_compose_require_tenant(): void
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once dirname(__DIR__) . '/template/includes/tessera_tenant.php';
		}
	}
}

if (!function_exists('tessera_accounts_compose_num')) {
	function tessera_accounts_compose_num($value): float
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

if (!function_exists('tessera_accounts_compose_figure')) {
	function tessera_accounts_compose_figure(mysqli $db, string $username): string
	{
		$u = $db->real_escape_string($username);
		$q = $db->query("SELECT firstname, surname FROM users WHERE username='{$u}' LIMIT 1");
		if ($q && ($row = $q->fetch_assoc())) {
			$name = trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['surname'] ?? ''));
			if ($name !== '') {
				return $name;
			}
		}
		return $username;
	}
}

if (!function_exists('tessera_accounts_compose_context')) {
	/**
	 * @return array{ok:bool,error?:string,context?:array<string,mixed>,points?:list<array<string,mixed>>}
	 */
	function tessera_accounts_compose_context(
		mysqli $db,
		string $username,
		string $route,
		string $bus,
		string $tripDate
	): array {
		tessera_accounts_compose_require_tenant();
		$route = trim($route);
		$bus = trim($bus);
		$tripDate = trim($tripDate);
		if ($route === '' || $bus === '' || $tripDate === '') {
			return ['ok' => false, 'error' => 'Route, vehicle, and trip date are required'];
		}

		$tenant = tessera_sql_tenant_eq();
		$r = $db->real_escape_string($route);
		$b = $db->real_escape_string($bus);
		$d = $db->real_escape_string($tripDate);
		$mq = $db->query(
			"SELECT id, code, route_name, bus, trip_date, income, expenses, balance, analysed, checked,
			        checked_count, password, lock_icon
			 FROM manifest WHERE {$tenant} AND route_name='{$r}' AND bus='{$b}' AND trip_date='{$d}' LIMIT 1"
		);
		$manifest = $mq ? $mq->fetch_assoc() : null;
		if (!$manifest) {
			/* Soft context still works so compose can open after create race. */
			$manifest = [
				'id' => 0,
				'code' => '',
				'route_name' => $route,
				'bus' => $bus,
				'trip_date' => $tripDate,
				'income' => 0,
				'expenses' => 0,
				'balance' => 0,
				'analysed' => '',
				'checked' => '',
				'checked_count' => 0,
				'password' => '',
				'lock_icon' => '',
			];
		}

		$ts = strtotime($tripDate);
		$dateLabel = $ts ? date('D j M Y', $ts) : $tripDate;
		$analysed = trim((string) ($manifest['analysed'] ?? ''));
		$checkedRaw = trim((string) ($manifest['checked'] ?? ''));
		$checkedPoints = $checkedRaw === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $checkedRaw))));
		$passwordLocked = trim((string) ($manifest['password'] ?? '')) !== '';
		$points = [];
		foreach (tessera_assign_route_points($db, $username, $route) as $p) {
			$pointName = (string) ($p['point'] ?? '');
			$points[] = [
				'id' => (string) ($p['id'] ?? ''),
				'point' => $pointName,
				'row_num' => (int) ($p['row_num'] ?? 0),
				'main_route' => (string) ($p['main_route'] ?? $route),
				'route_name' => (string) ($p['route_name'] ?? $route),
				'end_point' => (string) ($p['end_point'] ?? ''),
				'checked' => in_array($pointName, $checkedPoints, true),
			];
		}

		$qs = http_build_query([
			'route_name' => $route,
			'bus' => $bus,
			'trip_date' => $tripDate,
			'checked' => base64_encode((string) ($manifest['checked'] ?? '')),
			'main_page' => 'manifest.php',
		]);

		return [
			'ok' => true,
			'context' => [
				'id' => (int) ($manifest['id'] ?? 0),
				'code' => (string) ($manifest['code'] ?? ''),
				'route' => $route,
				'vehicle' => $bus,
				'date' => $tripDate,
				'date_label' => $dateLabel,
				'income' => tessera_accounts_compose_num($manifest['income'] ?? 0),
				'expenses' => tessera_accounts_compose_num($manifest['expenses'] ?? 0),
				'balance' => tessera_accounts_compose_num($manifest['balance'] ?? 0),
				'analysed' => $analysed,
				'locked' => $analysed !== '' || $passwordLocked,
				'password_locked' => $passwordLocked,
				'checked_points' => $checkedPoints,
				'checked_count' => (int) ($manifest['checked_count'] ?? count($checkedPoints)),
				'manifest_url' => '/operator/app.php?module=accounts&page=manifest.php',
				'classic_url' => '/accounts/compose_vehicle.php?' . $qs,
				'view_url' => '/accounts/manifest_view.php?' . $qs,
			],
			'points' => $points,
		];
	}
}

if (!function_exists('tessera_accounts_compose_bootstrap')) {
	/**
	 * @return array{income_categories:list<string>,expense_categories:list<string>,particulars:array<string,list<string>>,currencies:list<array{name:string,symbol:string}>,rates:array<string,float>,default_currency:string}
	 */
	function tessera_accounts_compose_bootstrap(mysqli $db): array
	{
		$income = [];
		$expense = [];
		$q = $db->query("SELECT name, category FROM particular_category ORDER BY name ASC");
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$name = trim((string) ($row['name'] ?? ''));
				$cat = (string) ($row['category'] ?? '');
				if ($name === '') {
					continue;
				}
				if ($cat === 'Income') {
					$income[] = $name;
				} elseif ($cat === 'Expenses') {
					$expense[] = $name;
				}
			}
		}

		$particulars = [];
		$pq = $db->query('SELECT category, name FROM particulars ORDER BY category ASC, name ASC');
		if ($pq) {
			while ($row = $pq->fetch_assoc()) {
				$cat = trim((string) ($row['category'] ?? ''));
				$name = trim((string) ($row['name'] ?? ''));
				if ($cat === '' || $name === '') {
					continue;
				}
				if (!isset($particulars[$cat])) {
					$particulars[$cat] = [];
				}
				$particulars[$cat][] = $name;
			}
		}

		$currencies = [];
		$cq = $db->query('SELECT name, symbol FROM currency ORDER BY id ASC');
		if ($cq) {
			while ($row = $cq->fetch_assoc()) {
				$name = trim((string) ($row['name'] ?? ''));
				if ($name === '') {
					continue;
				}
				$currencies[] = [
					'name' => $name,
					'symbol' => (string) ($row['symbol'] ?? ''),
				];
			}
		}
		if ($currencies === []) {
			$currencies[] = ['name' => 'Tsh', 'symbol' => '/='];
		}

		$rates = [];
		$rq = $db->query('SELECT first_point, second_point, value FROM currency_value ORDER BY id ASC');
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$from = trim((string) ($row['first_point'] ?? ''));
				$to = trim((string) ($row['second_point'] ?? ''));
				if ($from === '' || $to === '') {
					continue;
				}
				$rates[$from . '-' . $to] = tessera_accounts_compose_num($row['value'] ?? 0);
			}
		}

		return [
			'income_categories' => $income,
			'expense_categories' => $expense,
			'particulars' => $particulars,
			'currencies' => $currencies,
			'rates' => $rates,
			'default_currency' => $currencies[0]['name'] ?? 'Tsh',
		];
	}
}

if (!function_exists('tessera_accounts_compose_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_accounts_compose_row_to_item(array $row): array
	{
		$income = tessera_accounts_compose_num($row['income'] ?? 0);
		$expenses = tessera_accounts_compose_num($row['expenses'] ?? 0);
		$status = (string) ($row['status'] ?? '');
		if ($status === '' && $income > 0) {
			$status = 'Income';
		} elseif ($status === '' && $expenses > 0) {
			$status = 'Expenses';
		}
		return [
			'id' => (int) ($row['id'] ?? 0),
			'category' => (string) ($row['category'] ?? ''),
			'particular' => (string) ($row['name'] ?? ''),
			'income' => $income,
			'expenses' => $expenses,
			'amount' => $income > 0 ? $income : $expenses,
			'detail' => (string) ($row['detail'] ?? ''),
			'affect' => (string) ($row['affect'] ?? ''),
			'currency' => (string) ($row['currency'] ?? ''),
			'currency_sign' => (string) ($row['currency_sign'] ?? ''),
			'status' => $status,
			'point' => (string) ($row['point'] ?? ''),
			'figure_user' => (string) ($row['fullname_user'] ?? ''),
			'date_insert' => (string) ($row['date_insert'] ?? ''),
			'time_insert' => (string) ($row['time_insert'] ?? ''),
		];
	}
}

if (!function_exists('tessera_accounts_compose_ledger')) {
	/**
	 * @return array{items:list<array<string,mixed>>,summary:array{income:float,expenses:float,balance:float,count:int}}
	 */
	function tessera_accounts_compose_ledger(
		mysqli $db,
		string $route,
		string $bus,
		string $tripDate,
		string $point = '',
		string $q = ''
	): array {
		tessera_accounts_compose_require_tenant();
		$parts = [
			"route_name='" . $db->real_escape_string(trim($route)) . "'",
			"bus='" . $db->real_escape_string(trim($bus)) . "'",
			"trip_date='" . $db->real_escape_string(trim($tripDate)) . "'",
		];
		$point = trim($point);
		if ($point !== '' && strtolower($point) !== 'all') {
			$parts[] = "point='" . $db->real_escape_string($point) . "'";
		}
		$q = trim($q);
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(category LIKE '{$like}' OR name LIKE '{$like}' OR detail LIKE '{$like}')";
		}
		$where = implode(' AND ', $parts);

		$items = [];
		$income = 0.0;
		$expenses = 0.0;
		$sql = "SELECT id, category, name, income, expenses, detail, affect, currency, currency_sign,
			status, point, fullname_user, date_insert, time_insert
			FROM vehicle_account WHERE {$where} ORDER BY id ASC";
		$rq = $db->query($sql);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$item = tessera_accounts_compose_row_to_item($row);
				$items[] = $item;
				$income += $item['income'];
				$expenses += $item['expenses'];
			}
		}

		return [
			'items' => $items,
			'summary' => [
				'income' => $income,
				'expenses' => $expenses,
				'balance' => $income - $expenses,
				'count' => count($items),
			],
		];
	}
}

if (!function_exists('tessera_accounts_compose_recalc_manifest')) {
	function tessera_accounts_compose_recalc_manifest(mysqli $db, string $route, string $bus, string $tripDate): void
	{
		tessera_accounts_compose_require_tenant();
		$r = $db->real_escape_string($route);
		$b = $db->real_escape_string($bus);
		$d = $db->real_escape_string($tripDate);
		$income = 0.0;
		$expenses = 0.0;
		$q = $db->query(
			"SELECT income, expenses, affect FROM vehicle_account WHERE bus='{$b}' AND trip_date='{$d}'"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				if ((string) ($row['affect'] ?? '') === 'To be Paid') {
					continue;
				}
				$income += tessera_accounts_compose_num($row['income'] ?? 0);
				$expenses += tessera_accounts_compose_num($row['expenses'] ?? 0);
			}
		}
		$balance = $income - $expenses;
		$tenant = tessera_sql_tenant_eq();
		$db->query(
			"UPDATE manifest SET income={$income}, expenses={$expenses}, balance={$balance}
			 WHERE {$tenant} AND bus='{$b}' AND trip_date='{$d}' AND route_name='{$r}' LIMIT 1"
		);
	}
}

if (!function_exists('tessera_accounts_compose_currency_symbol')) {
	function tessera_accounts_compose_currency_symbol(mysqli $db, string $currency): string
	{
		$c = $db->real_escape_string($currency);
		$q = $db->query("SELECT symbol FROM currency WHERE name='{$c}' LIMIT 1");
		if ($q && ($row = $q->fetch_assoc())) {
			return (string) ($row['symbol'] ?? '');
		}
		return $currency === 'Tsh' ? '/=' : '';
	}
}

if (!function_exists('tessera_accounts_compose_affect')) {
	function tessera_accounts_compose_affect(mysqli $db, string $category, string $particular): string
	{
		$c = $db->real_escape_string($category);
		$p = $db->real_escape_string($particular);
		$q = $db->query("SELECT affect FROM particulars WHERE category='{$c}' AND name='{$p}' LIMIT 1");
		if ($q && ($row = $q->fetch_assoc())) {
			$a = trim((string) ($row['affect'] ?? ''));
			if ($a !== '') {
				return $a;
			}
		}
		return 'Paid';
	}
}

if (!function_exists('tessera_accounts_compose_add_entry')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_compose_add_entry(mysqli $db, string $username, array $input): array
	{
		tessera_accounts_compose_require_tenant();
		date_default_timezone_set('Africa/Dar_es_Salaam');

		$type = strtolower(trim((string) ($input['type'] ?? 'income')));
		$isIncome = $type === 'income';
		$route = trim((string) ($input['route'] ?? ''));
		$bus = trim((string) ($input['vehicle'] ?? $input['bus'] ?? ''));
		$tripDate = trim((string) ($input['date'] ?? ''));
		$point = trim((string) ($input['point'] ?? ''));
		$currentRoute = trim((string) ($input['current_route'] ?? $route));
		$category = trim((string) ($input['category'] ?? ''));
		$particular = trim((string) ($input['particular'] ?? ''));
		$currency = trim((string) ($input['currency'] ?? 'Tsh'));
		$detail = trim((string) ($input['detail'] ?? ''));
		$amount = tessera_accounts_compose_num($input['amount'] ?? 0);

		if ($route === '' || $bus === '' || $tripDate === '' || $point === '') {
			return ['ok' => false, 'error' => 'Route, vehicle, date, and point are required'];
		}
		if ($category === '' || $particular === '') {
			return ['ok' => false, 'error' => 'Category and particular are required'];
		}
		if ($amount <= 0) {
			return ['ok' => false, 'error' => 'Amount must be greater than zero'];
		}

		$ctx = tessera_accounts_compose_context($db, $username, $route, $bus, $tripDate);
		if (!empty($ctx['context']['locked'])) {
			return ['ok' => false, 'error' => 'Manifest is analysed and locked'];
		}

		$detail = str_replace(["'", ','], ['_', '~'], $detail);
		$symbol = tessera_accounts_compose_currency_symbol($db, $currency);
		$affect = tessera_accounts_compose_affect($db, $category, $particular);
		$figure = tessera_accounts_compose_figure($db, $username);
		$dateInsert = date('Y-m-d');
		$timeInsert = date('h:i A');
		$month = date('F', strtotime($tripDate) ?: time());
		$year = (int) date('Y', strtotime($tripDate) ?: time());
		$status = $isIncome ? 'Income' : 'Expenses';
		$income = $isIncome ? $amount : 0.0;
		$expenses = $isIncome ? 0.0 : $amount;
		$tenantId = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 1);

		$b = $db->real_escape_string($bus);
		$d = $db->real_escape_string($tripDate);
		$cat = $db->real_escape_string($category);
		$name = $db->real_escape_string($particular);
		$det = $db->real_escape_string($detail);
		$cur = $db->real_escape_string($currency);
		$sym = $db->real_escape_string($symbol);
		$aff = $db->real_escape_string($affect);
		$r = $db->real_escape_string($route);
		$p = $db->real_escape_string($point);
		$m = $db->real_escape_string($month);
		$st = $db->real_escape_string($status);
		$cr = $db->real_escape_string($currentRoute);
		$fig = $db->real_escape_string($figure);
		$u = $db->real_escape_string($username);
		$di = $db->real_escape_string($dateInsert);
		$ti = $db->real_escape_string($timeInsert);

		$ok = $db->query(
			"INSERT INTO vehicle_account
			(bus, trip_date, income, expenses, price, category, name, detail, currency, date_insert, time_insert,
			 affect, route_name, point, month, currency_sign, status, current_route, year, fullname_user, username, source, changed, tenant_id)
			 VALUES
			('{$b}','{$d}',{$income},{$expenses},{$amount},'{$cat}','{$name}','{$det}','{$cur}','{$di}','{$ti}',
			 '{$aff}','{$r}','{$p}','{$m}','{$sym}','{$st}','{$cr}',{$year},'{$fig}','{$u}','','',{$tenantId})"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Failed to save entry'];
		}
		$id = (int) $db->insert_id;
		tessera_accounts_compose_recalc_manifest($db, $route, $bus, $tripDate);

		$rowQ = $db->query(
			"SELECT id, category, name, income, expenses, detail, affect, currency, currency_sign,
				status, point, fullname_user, date_insert, time_insert
			 FROM vehicle_account WHERE id={$id} LIMIT 1"
		);
		$row = $rowQ ? $rowQ->fetch_assoc() : null;
		return [
			'ok' => true,
			'item' => $row ? tessera_accounts_compose_row_to_item($row) : null,
		];
	}
}

if (!function_exists('tessera_accounts_compose_update_entry')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_compose_update_entry(mysqli $db, string $username, array $input): array
	{
		$id = (int) ($input['id'] ?? 0);
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Entry id required'];
		}
		$q = $db->query(
			"SELECT id, bus, trip_date, route_name, status, income, expenses
			 FROM vehicle_account WHERE id={$id} LIMIT 1"
		);
		$existing = $q ? $q->fetch_assoc() : null;
		if (!$existing) {
			return ['ok' => false, 'error' => 'Entry not found'];
		}

		$route = (string) ($existing['route_name'] ?? '');
		$bus = (string) ($existing['bus'] ?? '');
		$tripDate = (string) ($existing['trip_date'] ?? '');
		$ctx = tessera_accounts_compose_context($db, $username, $route, $bus, $tripDate);
		if (!empty($ctx['context']['locked'])) {
			return ['ok' => false, 'error' => 'Manifest is analysed and locked'];
		}

		$status = (string) ($existing['status'] ?? 'Income');
		$category = trim((string) ($input['category'] ?? ''));
		$particular = trim((string) ($input['particular'] ?? ''));
		$currency = trim((string) ($input['currency'] ?? 'Tsh'));
		$detail = str_replace(["'", ','], ['_', '~'], trim((string) ($input['detail'] ?? '')));
		$amount = tessera_accounts_compose_num($input['amount'] ?? 0);
		if ($category === '' || $particular === '' || $amount <= 0) {
			return ['ok' => false, 'error' => 'Category, particular, and amount are required'];
		}

		$symbol = tessera_accounts_compose_currency_symbol($db, $currency);
		$affect = tessera_accounts_compose_affect($db, $category, $particular);
		$income = $status === 'Expenses' ? 0.0 : $amount;
		$expenses = $status === 'Expenses' ? $amount : 0.0;
		/* Allow flipping type via explicit type field. */
		$type = strtolower(trim((string) ($input['type'] ?? '')));
		if ($type === 'income') {
			$status = 'Income';
			$income = $amount;
			$expenses = 0.0;
		} elseif ($type === 'expense' || $type === 'expenses') {
			$status = 'Expenses';
			$income = 0.0;
			$expenses = $amount;
		}

		$cat = $db->real_escape_string($category);
		$name = $db->real_escape_string($particular);
		$cur = $db->real_escape_string($currency);
		$sym = $db->real_escape_string($symbol);
		$det = $db->real_escape_string($detail);
		$aff = $db->real_escape_string($affect);
		$st = $db->real_escape_string($status);

		$ok = $db->query(
			"UPDATE vehicle_account SET category='{$cat}', name='{$name}', currency='{$cur}', currency_sign='{$sym}',
				income={$income}, expenses={$expenses}, price={$amount}, status='{$st}', detail='{$det}', affect='{$aff}'
			 WHERE id={$id} LIMIT 1"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Failed to update entry'];
		}
		tessera_accounts_compose_recalc_manifest($db, $route, $bus, $tripDate);

		$rowQ = $db->query(
			"SELECT id, category, name, income, expenses, detail, affect, currency, currency_sign,
				status, point, fullname_user, date_insert, time_insert
			 FROM vehicle_account WHERE id={$id} LIMIT 1"
		);
		$row = $rowQ ? $rowQ->fetch_assoc() : null;
		return [
			'ok' => true,
			'item' => $row ? tessera_accounts_compose_row_to_item($row) : null,
		];
	}
}

if (!function_exists('tessera_accounts_compose_delete_entry')) {
	/**
	 * @return array{ok:bool,error?:string}
	 */
	function tessera_accounts_compose_delete_entry(mysqli $db, string $username, int $id): array
	{
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Entry id required'];
		}
		$q = $db->query(
			"SELECT bus, trip_date, route_name FROM vehicle_account WHERE id={$id} LIMIT 1"
		);
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Entry not found'];
		}
		$route = (string) ($row['route_name'] ?? '');
		$bus = (string) ($row['bus'] ?? '');
		$tripDate = (string) ($row['trip_date'] ?? '');
		$ctx = tessera_accounts_compose_context($db, $username, $route, $bus, $tripDate);
		if (!empty($ctx['context']['locked'])) {
			return ['ok' => false, 'error' => 'Manifest is analysed and locked'];
		}
		$db->query("DELETE FROM vehicle_account WHERE id={$id} LIMIT 1");
		tessera_accounts_compose_recalc_manifest($db, $route, $bus, $tripDate);
		return ['ok' => true];
	}
}

if (!function_exists('tessera_accounts_compose_lock')) {
	/**
	 * Lock a trip ledger (password + analysed Complete). Unlock clears both.
	 *
	 * @return array{ok:bool,error?:string,context?:array<string,mixed>,points?:list<array<string,mixed>>}
	 */
	function tessera_accounts_compose_lock(
		mysqli $db,
		string $username,
		string $route,
		string $bus,
		string $tripDate,
		string $action,
		string $password = ''
	): array {
		tessera_accounts_compose_require_tenant();
		$route = trim($route);
		$bus = trim($bus);
		$tripDate = trim($tripDate);
		$action = strtolower(trim($action));
		if ($route === '' || $bus === '' || $tripDate === '') {
			return ['ok' => false, 'error' => 'Route, vehicle, and trip date are required'];
		}
		if ($action !== 'lock' && $action !== 'unlock') {
			return ['ok' => false, 'error' => 'Action must be lock or unlock'];
		}
		if ($action === 'lock' && trim($password) === '') {
			return ['ok' => false, 'error' => 'Password is required to lock'];
		}

		$tenant = tessera_sql_tenant_eq();
		$r = $db->real_escape_string($route);
		$b = $db->real_escape_string($bus);
		$d = $db->real_escape_string($tripDate);

		if ($action === 'lock') {
			$pw = $db->real_escape_string(trim($password));
			$icon = $db->real_escape_string(' typcn-lock-closed');
			$db->query(
				"UPDATE manifest SET lock_icon='{$icon}', password='{$pw}', analysed='Complete'
				 WHERE {$tenant} AND route_name='{$r}' AND bus='{$b}' AND trip_date='{$d}' LIMIT 1"
			);
		} else {
			$icon = $db->real_escape_string('typcn-lock-closed-outline');
			$db->query(
				"UPDATE manifest SET lock_icon='{$icon}', password='', analysed='', checked='', checked_count='0'
				 WHERE {$tenant} AND route_name='{$r}' AND bus='{$b}' AND trip_date='{$d}' LIMIT 1"
			);
		}

		return tessera_accounts_compose_context($db, $username, $route, $bus, $tripDate);
	}
}

if (!function_exists('tessera_accounts_compose_point_check')) {
	/**
	 * Toggle boarding-point reviewed state (classic update_seen.php).
	 *
	 * @return array{ok:bool,error?:string,context?:array<string,mixed>,points?:list<array<string,mixed>>}
	 */
	function tessera_accounts_compose_point_check(
		mysqli $db,
		string $username,
		string $route,
		string $bus,
		string $tripDate,
		string $point
	): array {
		tessera_accounts_compose_require_tenant();
		$route = trim($route);
		$bus = trim($bus);
		$tripDate = trim($tripDate);
		$point = trim($point);
		if ($route === '' || $bus === '' || $tripDate === '' || $point === '') {
			return ['ok' => false, 'error' => 'Route, vehicle, date, and point are required'];
		}

		$tenant = tessera_sql_tenant_eq();
		$b = $db->real_escape_string($bus);
		$d = $db->real_escape_string($tripDate);
		$mq = $db->query(
			"SELECT checked, checked_count, route_name, password FROM manifest
			 WHERE {$tenant} AND bus='{$b}' AND trip_date='{$d}' LIMIT 1"
		);
		$manifest = $mq ? $mq->fetch_assoc() : null;
		if (!$manifest) {
			return ['ok' => false, 'error' => 'Manifest not found'];
		}
		if (trim((string) ($manifest['password'] ?? '')) !== '') {
			return ['ok' => false, 'error' => 'Manifest is password-locked'];
		}

		$routeName = (string) ($manifest['route_name'] ?? $route);
		$rn = $db->real_escape_string($routeName);
		$rq = $db->query("SELECT fullname FROM route WHERE main_route='{$rn}'");
		$totalRoute = 1 + ($rq ? (int) $rq->num_rows : 0);

		$checkedRaw = trim((string) ($manifest['checked'] ?? ''));
		$checkedCount = (int) ($manifest['checked_count'] ?? 0);
		$list = $checkedRaw === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $checkedRaw))));
		$analysed = 'Half Checked';

		$key = array_search($point, $list, true);
		if ($key !== false) {
			unset($list[$key]);
			$list = array_values($list);
			$checkedCount = max(0, $checkedCount - 1);
			if ($checkedCount === 0 || $list === []) {
				$analysed = '';
				$checkedCount = 0;
				$list = [];
			}
		} else {
			$list[] = $point;
			$checkedCount++;
			if ($checkedCount >= $totalRoute) {
				$analysed = 'Complete';
			} else {
				$analysed = 'Half Checked';
			}
		}

		$checkedStr = $db->real_escape_string(implode(',', $list));
		$an = $db->real_escape_string($analysed);
		$db->query(
			"UPDATE manifest SET checked_count='{$checkedCount}', checked='{$checkedStr}', analysed='{$an}'
			 WHERE {$tenant} AND bus='{$b}' AND trip_date='{$d}' LIMIT 1"
		);

		return tessera_accounts_compose_context($db, $username, $route, $bus, $tripDate);
	}
}

if (!function_exists('tessera_accounts_compose_fx_convert')) {
	/**
	 * Convert amount from line currency into display currency using currency_value rates.
	 *
	 * @param array<string,float> $rates
	 */
	function tessera_accounts_compose_fx_convert(
		float $amount,
		string $fromCurrency,
		string $toCurrency,
		array $rates
	): float {
		$from = trim($fromCurrency);
		$to = trim($toCurrency);
		if ($from === '' || $to === '' || strcasecmp($from, $to) === 0 || strcasecmp($to, 'Default') === 0) {
			return $amount;
		}
		$key = $from . '-' . $to;
		$rate = isset($rates[$key]) ? (float) $rates[$key] : 0.0;
		if ($rate <= 0) {
			/* Try inverse. */
			$inv = isset($rates[$to . '-' . $from]) ? (float) $rates[$to . '-' . $from] : 0.0;
			if ($inv > 0) {
				return $amount * $inv;
			}
			return $amount;
		}
		return $amount / $rate;
	}
}

if (!function_exists('tessera_accounts_compose_attachments')) {
	/**
	 * @return list<array{id:int,file_name:string,original_name:string,point:string,url:string}>
	 */
	function tessera_accounts_compose_attachments(
		mysqli $db,
		string $route,
		string $bus,
		string $tripDate
	): array {
		$r = $db->real_escape_string(trim($route));
		$b = $db->real_escape_string(trim($bus));
		$d = $db->real_escape_string(trim($tripDate));
		$out = [];
		$q = $db->query(
			"SELECT id, file_name, ogname, point FROM attachment
			 WHERE main_route='{$r}' AND bus='{$b}' AND trip_date='{$d}'
			 ORDER BY id DESC"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$file = (string) ($row['file_name'] ?? '');
				$out[] = [
					'id' => (int) ($row['id'] ?? 0),
					'file_name' => $file,
					'original_name' => (string) ($row['ogname'] ?? $file),
					'point' => (string) ($row['point'] ?? ''),
					'url' => '/accounts/upload/uploads/' . rawurlencode($file),
				];
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_compose_attachment_save')) {
	/**
	 * @param array{name?:string,tmp_name?:string,error?:int,size?:int} $file
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_compose_attachment_save(
		mysqli $db,
		string $route,
		string $bus,
		string $tripDate,
		string $point,
		string $currentRoute,
		array $file
	): array {
		if (!empty($file['error'])) {
			return ['ok' => false, 'error' => 'Upload failed'];
		}
		$tmp = (string) ($file['tmp_name'] ?? '');
		$orig = (string) ($file['name'] ?? '');
		if ($tmp === '' || !is_uploaded_file($tmp) || $orig === '') {
			return ['ok' => false, 'error' => 'No file uploaded'];
		}
		$ext = pathinfo($orig, PATHINFO_EXTENSION);
		$safeExt = preg_replace('/[^a-zA-Z0-9]/', '', (string) $ext);
		$newName = (string) round(microtime(true) * 1000) . ($safeExt !== '' ? '.' . $safeExt : '');
		/* Same folder classic upload/upload.php uses (under accounting template). */
		$candidates = [
			dirname(__DIR__) . '/template/upload/uploads',
			dirname(__DIR__) . '/upload/uploads',
			sys_get_temp_dir() . '/tessera-accounts-uploads',
		];
		$dir = '';
		foreach ($candidates as $cand) {
			if (!is_dir($cand)) {
				@mkdir($cand, 0777, true);
			}
			if (is_dir($cand) && is_writable($cand)) {
				$dir = $cand;
				break;
			}
		}
		if ($dir === '') {
			return ['ok' => false, 'error' => 'Upload directory unavailable'];
		}
		$dest = $dir . '/' . $newName;
		if (!@move_uploaded_file($tmp, $dest)) {
			/* Fallback for non-HTTP test clients that pass a real temp path. */
			if (!@rename($tmp, $dest) && !@copy($tmp, $dest)) {
				return ['ok' => false, 'error' => 'Could not store file'];
			}
		}
		$fn = $db->real_escape_string($newName);
		$og = $db->real_escape_string($orig);
		$d = $db->real_escape_string(trim($tripDate));
		$b = $db->real_escape_string(trim($bus));
		$cr = $db->real_escape_string(trim($currentRoute !== '' ? $currentRoute : $route));
		$mr = $db->real_escape_string(trim($route));
		$pt = $db->real_escape_string(trim($point));
		$db->query(
			"INSERT INTO attachment (file_name, trip_date, bus, route_name, main_route, ogname, point)
			 VALUES ('{$fn}','{$d}','{$b}','{$cr}','{$mr}','{$og}','{$pt}')"
		);
		$id = (int) $db->insert_id;
		return [
			'ok' => true,
			'item' => [
				'id' => $id,
				'file_name' => $newName,
				'original_name' => $orig,
				'point' => $point,
				'url' => '/accounts/upload/uploads/' . rawurlencode($newName),
			],
		];
	}
}

if (!function_exists('tessera_accounts_compose_attachment_delete')) {
	/** @return array{ok:bool,error?:string} */
	function tessera_accounts_compose_attachment_delete(mysqli $db, int $id): array
	{
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Attachment id required'];
		}
		$q = $db->query("SELECT file_name FROM attachment WHERE id={$id} LIMIT 1");
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Attachment not found'];
		}
		$file = (string) ($row['file_name'] ?? '');
		$db->query("DELETE FROM attachment WHERE id={$id} LIMIT 1");
		$path = dirname(__DIR__) . '/template/upload/uploads/' . $file;
		if ($file !== '' && is_file($path)) {
			@unlink($path);
		}
		return ['ok' => true];
	}
}

if (!function_exists('tessera_accounts_compose_pull_tickets_cargo')) {
	/**
	 * Pull ticket + cargo totals into vehicle_account (classic fetch/manifest_details.php).
	 *
	 * @return array{ok:bool,error?:string,created?:int}
	 */
	function tessera_accounts_compose_pull_tickets_cargo(
		mysqli $db,
		string $username,
		string $mainRoute,
		string $currentRoute,
		string $bus,
		string $tripDate,
		string $sourceTripDate
	): array {
		tessera_accounts_compose_require_tenant();
		$mainRoute = trim($mainRoute);
		$currentRoute = trim($currentRoute);
		$bus = trim($bus);
		$tripDate = trim($tripDate);
		$sourceTripDate = trim($sourceTripDate);
		if ($mainRoute === '' || $currentRoute === '' || $bus === '' || $tripDate === '' || $sourceTripDate === '') {
			return ['ok' => false, 'error' => 'Route, vehicle, dates are required'];
		}

		$ctx = tessera_accounts_compose_context($db, $username, $mainRoute, $bus, $tripDate);
		if (!empty($ctx['context']['locked'])) {
			return ['ok' => false, 'error' => 'Manifest is analysed and locked'];
		}

		require_once dirname(__DIR__) . '/template/connect/connect_cargo.php';
		require_once dirname(__DIR__) . '/template/connect/connect_ticket.php';
		/** @var mysqli $db_up */
		/** @var mysqli $db_ticket */

		$b = $db_up->real_escape_string($bus);
		$src = $db_up->real_escape_string($sourceTripDate);
		$cr = $db_up->real_escape_string($currentRoute);
		$paid = 0.0;
		$toPay = 0.0;
		$vq = $db_up->query(
			"SELECT to_pay, paid, Name FROM vehicle_name WHERE Route='{$cr}' AND date='{$src}' AND Name='{$b}' LIMIT 1"
		);
		if ($vq && ($vrow = $vq->fetch_assoc())) {
			$paid = tessera_accounts_compose_num($vrow['paid'] ?? 0);
			$toPay = tessera_accounts_compose_num($vrow['to_pay'] ?? 0);
		}
		$tamount = $paid + $toPay;

		$tenantTicket = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1';
		$bt = $db_ticket->real_escape_string($bus);
		$dt = $db_ticket->real_escape_string($tripDate);
		$totalTicket = 0.0;
		$routeDayId = '';
		$tq = $db_ticket->query(
			"SELECT cost, route_day_id FROM booking WHERE {$tenantTicket} AND bus='{$bt}' AND trip_date='{$dt}' AND cost != '0'"
		);
		if ($tq) {
			while ($row = $tq->fetch_assoc()) {
				$totalTicket += tessera_accounts_compose_num($row['cost'] ?? 0);
				if ($routeDayId === '') {
					$routeDayId = (string) ($row['route_day_id'] ?? '');
				}
			}
		}
		$manifestId = 0;
		$tm = $db_ticket->query(
			"SELECT id FROM manifest WHERE bus='{$bt}' AND trip_date='{$dt}' LIMIT 1"
		);
		if ($tm && ($tmRow = $tm->fetch_assoc())) {
			$manifestId = (int) ($tmRow['id'] ?? 0);
		}

		$rq = $db->query(
			"SELECT start_point, currency FROM route WHERE fullname='" . $db->real_escape_string($currentRoute) . "' LIMIT 1"
		);
		$startPoint = '';
		$currency = 'Tsh';
		if ($rq && ($rrow = $rq->fetch_assoc())) {
			$startPoint = (string) ($rrow['start_point'] ?? '');
			$currency = trim((string) ($rrow['currency'] ?? 'Tsh')) ?: 'Tsh';
		}
		if ($startPoint === '' && !empty($ctx['points'][0]['point'])) {
			$startPoint = (string) $ctx['points'][0]['point'];
		}
		$symbol = tessera_accounts_compose_currency_symbol($db, $currency);
		$figure = tessera_accounts_compose_figure($db, $username);
		$tenantId = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 1);
		$created = 0;

		$ticketDetail = $routeDayId !== ''
			? "<a href='/tickets/sales_summary.php?id={$routeDayId}&trip_date={$tripDate}&manifest={$manifestId}'>Tickets pulled</a>"
			: 'Tickets pulled';
		$parcelDetail = "<a href='/cargo/vehicle_report_new.php?date={$tripDate}&date2={$tripDate}&date3=" . rawurlencode($bus) . "&open=open'>Paid cargo</a>";

		$pullLines = [
			['type' => 'income', 'category' => 'Passangers', 'particular' => 'Tickets', 'amount' => $totalTicket, 'detail' => $ticketDetail],
			['type' => 'income', 'category' => 'Cargo', 'particular' => 'Paid', 'amount' => $paid, 'detail' => $parcelDetail],
		];
		foreach ($pullLines as $line) {
			if ($line['amount'] <= 0) {
				continue;
			}
			$res = tessera_accounts_compose_add_entry($db, $username, [
				'type' => $line['type'],
				'route' => $mainRoute,
				'vehicle' => $bus,
				'date' => $tripDate,
				'point' => $startPoint,
				'current_route' => $currentRoute,
				'category' => $line['category'],
				'particular' => $line['particular'],
				'amount' => $line['amount'],
				'currency' => $currency,
				'detail' => $line['detail'],
			]);
			if (!empty($res['ok'])) {
				$created++;
			}
		}

		$rmCheck = $db->query(
			"SELECT paid FROM route_manifest WHERE bus='" . $db->real_escape_string($bus) . "'
			 AND trip_date='" . $db->real_escape_string($sourceTripDate) . "'
			 AND location='" . $db->real_escape_string($currentRoute) . "' LIMIT 1"
		);
		if (!$rmCheck || $rmCheck->num_rows === 0) {
			$db->query(
				"INSERT INTO route_manifest (to_be, paid, amount, location, bus, trip_date, main_location, og_trip_date)
				 VALUES ('{$toPay}','{$paid}','{$tamount}','" . $db->real_escape_string($currentRoute) . "','" . $db->real_escape_string($bus) . "','" . $db->real_escape_string($tripDate) . "','" . $db->real_escape_string($mainRoute) . "','" . $db->real_escape_string($sourceTripDate) . "')"
			);
		}

		$cq = $db->query(
			"SELECT * FROM constants WHERE route_name='" . $db->real_escape_string($currentRoute) . "'
			 AND route_category='Sub Route' AND vehicle='Bus'"
		);
		if ($cq) {
			while ($c = $cq->fetch_assoc()) {
				$price = tessera_accounts_compose_num($c['price_value'] ?? 0);
				if ($price <= 0) {
					continue;
				}
				$res = tessera_accounts_compose_add_entry($db, $username, [
					'type' => 'expense',
					'route' => $mainRoute,
					'vehicle' => $bus,
					'date' => $tripDate,
					'point' => $startPoint,
					'current_route' => $currentRoute,
					'category' => (string) ($c['name_category'] ?? 'Expenses'),
					'particular' => (string) ($c['name'] ?? 'Constant'),
					'amount' => $price,
					'currency' => trim((string) ($c['currency'] ?? $currency)) ?: $currency,
					'detail' => 'Constant',
				]);
				if (!empty($res['ok'])) {
					$created++;
				}
			}
		}

		unset($symbol, $figure, $tenantId);
		tessera_accounts_compose_recalc_manifest($db, $mainRoute, $bus, $tripDate);
		return ['ok' => true, 'created' => $created];
	}
}

if (!function_exists('tessera_accounts_compose_user_charges')) {
	/**
	 * Cargo uploader charge summary for this trip (classic search/user_charge.php).
	 *
	 * @return array{ok:bool,error?:string,totals?:array<string,float>,rows?:list<array<string,mixed>>}
	 */
	function tessera_accounts_compose_user_charges(
		string $route,
		string $currentRoute,
		string $bus,
		string $tripDate
	): array {
		require_once dirname(__DIR__) . '/template/connect/connect_cargo.php';
		/** @var mysqli $db_up */

		$route = trim($route);
		$currentRoute = trim($currentRoute);
		$bus = trim($bus);
		$tripDate = trim($tripDate);
		if ($bus === '' || $tripDate === '' || $currentRoute === '') {
			return ['ok' => false, 'error' => 'Vehicle, date, and boarding route are required'];
		}

		$b = $db_up->real_escape_string($bus);
		$d = $db_up->real_escape_string($tripDate);
		$cr = $db_up->real_escape_string($currentRoute);

		$toPay = 0.0;
		$paid = 0.0;
		$due = 0.0;
		$vq = $db_up->query(
			"SELECT to_pay, paid, due FROM vehicle_name WHERE date='{$d}' AND Name='{$b}' AND Route='{$cr}' LIMIT 1"
		);
		if ($vq && ($v = $vq->fetch_assoc())) {
			$toPay = tessera_accounts_compose_num($v['to_pay'] ?? 0);
			$paid = tessera_accounts_compose_num($v['paid'] ?? 0);
			$due = tessera_accounts_compose_num($v['due'] ?? 0);
		}

		$rows = [];
		$uq = $db_up->query(
			"SELECT parcel_uploader FROM manifest
			 WHERE trip_date='{$d}' AND bus='{$b}' AND parcel_route='{$cr}'
			 GROUP BY parcel_uploader"
		);
		if ($uq) {
			while ($u = $uq->fetch_assoc()) {
				$uploader = trim((string) ($u['parcel_uploader'] ?? ''));
				if ($uploader === '') {
					continue;
				}
				$upEsc = $db_up->real_escape_string($uploader);
				$paidAmt = 0.0;
				$toBeAmt = 0.0;
				$pq = $db_up->query(
					"SELECT SUM(paid) AS paid FROM manifest
					 WHERE parcel_route='{$cr}' AND parcel_uploader='{$upEsc}' AND trip_date='{$d}' AND bus='{$b}'"
				);
				if ($pq && ($pr = $pq->fetch_assoc())) {
					$paidAmt = tessera_accounts_compose_num($pr['paid'] ?? 0);
				}
				$tq = $db_up->query(
					"SELECT SUM(to_be) AS to_be FROM manifest
					 WHERE parcel_route='{$cr}' AND parcel_uploader='{$upEsc}' AND trip_date='{$d}' AND bus='{$b}'"
				);
				if ($tq && ($tr = $tq->fetch_assoc())) {
					$toBeAmt = tessera_accounts_compose_num($tr['to_be'] ?? 0);
				}
				$rows[] = [
					'uploader' => $uploader,
					'paid' => $paidAmt,
					'to_be' => $toBeAmt,
				];
			}
		}

		return [
			'ok' => true,
			'totals' => [
				'paid' => $paid,
				'to_pay' => $toPay,
				'due' => $due,
			],
			'rows' => $rows,
		];
	}
}

if (!function_exists('tessera_accounts_compose_source_dates')) {
	/**
	 * Candidate cargo trip dates for Pull tickets & cargo.
	 *
	 * @return list<string>
	 */
	function tessera_accounts_compose_source_dates(string $currentRoute, string $bus): array
	{
		require_once dirname(__DIR__) . '/template/connect/connect_cargo.php';
		/** @var mysqli $db_up */
		$cr = $db_up->real_escape_string(trim($currentRoute));
		$b = $db_up->real_escape_string(trim($bus));
		$out = [];
		$q = $db_up->query(
			"SELECT DISTINCT date FROM vehicle_name WHERE Route='{$cr}' AND Name='{$b}' ORDER BY date DESC LIMIT 40"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$d = trim((string) ($row['date'] ?? ''));
				if ($d !== '') {
					$out[] = $d;
				}
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_compose_transfer_targets')) {
	/**
	 * Other manifests the selected lines can move to.
	 *
	 * @return list<array{route:string,bus:string,trip_date:string,label:string}>
	 */
	function tessera_accounts_compose_transfer_targets(mysqli $db, string $username, string $route, string $bus, string $tripDate): array
	{
		tessera_accounts_compose_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		$assigned = function_exists('tessera_accounts_manifests_assigned_routes')
			? tessera_accounts_manifests_assigned_routes($db, $username)
			: [];
		$where = $tenant;
		if ($assigned !== []) {
			$in = array_map(static function ($name) use ($db) {
				return "'" . $db->real_escape_string($name) . "'";
			}, $assigned);
			$where .= ' AND route_name IN (' . implode(',', $in) . ')';
		}
		$out = [];
		$q = $db->query(
			"SELECT route_name, bus, trip_date FROM manifest WHERE {$where}
			 ORDER BY trip_date DESC, bus ASC LIMIT 80"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$r = (string) ($row['route_name'] ?? '');
				$b = (string) ($row['bus'] ?? '');
				$d = (string) ($row['trip_date'] ?? '');
				if ($r === $route && $b === $bus && $d === $tripDate) {
					continue;
				}
				$out[] = [
					'route' => $r,
					'bus' => $b,
					'trip_date' => $d,
					'label' => trim($b . ' · ' . $d . ' · ' . $r),
				];
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_compose_transfer')) {
	/**
	 * Move selected ledger lines onto another trip (classic compose transfer).
	 *
	 * @param list<int> $ids
	 * @return array{ok:bool,error?:string,moved?:int,context?:array<string,mixed>}
	 */
	function tessera_accounts_compose_transfer(
		mysqli $db,
		string $username,
		string $route,
		string $bus,
		string $tripDate,
		string $targetRoute,
		string $targetBus,
		string $targetDate,
		array $ids
	): array {
		tessera_accounts_compose_require_tenant();
		$route = trim($route);
		$bus = trim($bus);
		$tripDate = trim($tripDate);
		$targetRoute = trim($targetRoute);
		$targetBus = trim($targetBus);
		$targetDate = trim($targetDate);
		$ids = array_values(array_unique(array_filter(array_map('intval', $ids), static function ($id) {
			return $id > 0;
		})));
		if ($route === '' || $bus === '' || $tripDate === '' || $targetRoute === '' || $targetBus === '' || $targetDate === '') {
			return ['ok' => false, 'error' => 'Source trip, target trip, and selected lines are required'];
		}
		if ($ids === []) {
			return ['ok' => false, 'error' => 'Select at least one ledger line'];
		}
		if ($route === $targetRoute && $bus === $targetBus && $tripDate === $targetDate) {
			return ['ok' => false, 'error' => 'Pick a different trip'];
		}
		$source = tessera_accounts_compose_context($db, $username, $route, $bus, $tripDate);
		if (!empty($source['context']['locked'])) {
			return ['ok' => false, 'error' => 'Manifest is analysed and locked'];
		}
		$target = tessera_accounts_compose_context($db, $username, $targetRoute, $targetBus, $targetDate);
		if ((int) ($target['context']['id'] ?? 0) < 1) {
			return ['ok' => false, 'error' => 'Target trip was not found'];
		}
		if (!empty($target['context']['locked'])) {
			return ['ok' => false, 'error' => 'Target trip is locked'];
		}
		$in = implode(',', $ids);
		$r = $db->real_escape_string($route);
		$b = $db->real_escape_string($bus);
		$d = $db->real_escape_string($tripDate);
		$tr = $db->real_escape_string($targetRoute);
		$tb = $db->real_escape_string($targetBus);
		$td = $db->real_escape_string($targetDate);
		$tenant = tessera_sql_tenant_eq();
		$ok = $db->query(
			"UPDATE vehicle_account SET bus='{$tb}', trip_date='{$td}', route_name='{$tr}'
			 WHERE id IN ({$in}) AND {$tenant} AND bus='{$b}' AND trip_date='{$d}' AND route_name='{$r}'"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Transfer failed'];
		}
		$moved = (int) $db->affected_rows;
		if ($moved < 1) {
			return ['ok' => false, 'error' => 'Those lines are not on this trip'];
		}
		tessera_accounts_compose_recalc_manifest($db, $route, $bus, $tripDate);
		tessera_accounts_compose_recalc_manifest($db, $targetRoute, $targetBus, $targetDate);
		$fresh = tessera_accounts_compose_context($db, $username, $route, $bus, $tripDate);
		return ['ok' => true, 'moved' => $moved, 'context' => $fresh['context'] ?? null];
	}
}

