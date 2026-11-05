<?php
/**
 * Shared Accounts daily expenses helpers (A-P0-03).
 */
declare(strict_types=1);

if (!function_exists('tessera_accounts_daily_require_tenant')) {
	function tessera_accounts_daily_require_tenant(): void
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once dirname(__DIR__) . '/template/includes/tessera_tenant.php';
		}
	}
}

if (!function_exists('tessera_accounts_daily_num')) {
	function tessera_accounts_daily_num($value): float
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

if (!function_exists('tessera_accounts_daily_months')) {
	/** @return list<string> */
	function tessera_accounts_daily_months(mysqli $db): array
	{
		$out = [];
		$q = $db->query('SELECT name FROM months ORDER BY id ASC');
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$name = trim((string) ($row['name'] ?? ''));
				if ($name !== '') {
					$out[] = $name;
				}
			}
		}
		if ($out === []) {
			$out = [
				'January', 'February', 'March', 'April', 'May', 'June',
				'July', 'August', 'September', 'October', 'November', 'December',
			];
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_daily_routes')) {
	/** @return list<string> */
	function tessera_accounts_daily_routes(mysqli $db, string $username): array
	{
		$routes = [];
		$u = $db->real_escape_string($username);
		$q = $db->query("SELECT DISTINCT route_name FROM assign_route WHERE username='{$u}' ORDER BY route_name ASC");
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$rn = trim((string) ($row['route_name'] ?? ''));
				if ($rn !== '') {
					$routes[] = $rn;
				}
			}
		}
		if ($routes === []) {
			$q2 = $db->query('SELECT DISTINCT route_name FROM cash WHERE route_name IS NOT NULL AND route_name<>\'\' ORDER BY route_name ASC');
			if ($q2) {
				while ($row = $q2->fetch_assoc()) {
					$rn = trim((string) ($row['route_name'] ?? ''));
					if ($rn !== '') {
						$routes[] = $rn;
					}
				}
			}
		}
		if ($routes === []) {
			$q3 = $db->query('SELECT fullname FROM route ORDER BY id ASC');
			if ($q3) {
				while ($row = $q3->fetch_assoc()) {
					$rn = trim((string) ($row['fullname'] ?? ''));
					if ($rn !== '') {
						$routes[] = $rn;
					}
				}
			}
		}
		return array_values(array_unique($routes));
	}
}

if (!function_exists('tessera_accounts_daily_cash_float')) {
	/**
	 * Latest cash float for a route.
	 * @return array{id:int,open_date:string,opening_stock:float,amount_remain:float}|null
	 */
	function tessera_accounts_daily_cash_float(mysqli $db, string $route): ?array
	{
		tessera_accounts_daily_require_tenant();
		$r = $db->real_escape_string(trim($route));
		$tenant = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1';
		$q = $db->query(
			"SELECT id, open_date, opening_stock, amount_remain FROM cash
			 WHERE {$tenant} AND route_name='{$r}' ORDER BY id DESC LIMIT 1"
		);
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return null;
		}
		return [
			'id' => (int) ($row['id'] ?? 0),
			'open_date' => (string) ($row['open_date'] ?? ''),
			'opening_stock' => tessera_accounts_daily_num($row['opening_stock'] ?? 0),
			'amount_remain' => tessera_accounts_daily_num($row['amount_remain'] ?? 0),
		];
	}
}

if (!function_exists('tessera_accounts_daily_bootstrap')) {
	/**
	 * @return array{months:list<string>,routes:list<string>,categories:list<string>,particulars:array<string,list<string>>,vehicles:list<string>,accounts:list<string>,years:list<int>}
	 */
	function tessera_accounts_daily_bootstrap(mysqli $db, string $username): array
	{
		/* Classic daily.php offers every particular_category name (not Expenses-only). */
		$categories = [];
		$q = $db->query('SELECT DISTINCT name FROM particular_category ORDER BY name ASC');
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$name = trim((string) ($row['name'] ?? ''));
				if ($name !== '') {
					$categories[] = $name;
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

		$vehicles = [];
		$vq = $db->query("SELECT DISTINCT bus FROM manifest WHERE bus IS NOT NULL AND bus<>'' ORDER BY bus ASC LIMIT 200");
		if ($vq) {
			while ($row = $vq->fetch_assoc()) {
				$bus = trim((string) ($row['bus'] ?? ''));
				if ($bus !== '') {
					$vehicles[] = $bus;
				}
			}
		}

		$yearNow = (int) date('Y');
		$years = [];
		for ($y = $yearNow; $y >= $yearNow - 3; $y--) {
			$years[] = $y;
		}
		$yq = $db->query('SELECT DISTINCT year FROM office_expenses WHERE year IS NOT NULL AND year<>\'\' ORDER BY year DESC LIMIT 10');
		if ($yq) {
			while ($row = $yq->fetch_assoc()) {
				$y = (int) ($row['year'] ?? 0);
				if ($y > 0 && !in_array($y, $years, true)) {
					$years[] = $y;
				}
			}
			rsort($years);
		}

		$routes = tessera_accounts_daily_routes($db, $username);
		/* Prefer routes that already have a cash float so the first paint is useful. */
		$withCash = [];
		$withoutCash = [];
		foreach ($routes as $rn) {
			if (tessera_accounts_daily_cash_float($db, $rn) !== null) {
				$withCash[] = $rn;
			} else {
				$withoutCash[] = $rn;
			}
		}

		return [
			'months' => tessera_accounts_daily_months($db),
			'routes' => array_merge($withCash, $withoutCash),
			'categories' => $categories,
			'particulars' => $particulars,
			'vehicles' => $vehicles,
			'accounts' => ['Cash', 'Cheque'],
			'years' => $years,
		];
	}
}

if (!function_exists('tessera_accounts_daily_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_accounts_daily_row_to_item(array $row): array
	{
		$date = (string) ($row['date'] ?? '');
		$ts = $date !== '' ? strtotime($date) : false;
		$item = trim((string) ($row['item'] ?? ''));
		$vehicle = trim((string) ($row['vehicle'] ?? ''));
		if ($item === 'Choose Item') {
			$item = '';
		}
		if ($vehicle === 'Choose Vehicle') {
			$vehicle = '';
		}
		return [
			'id' => (int) ($row['id'] ?? 0),
			'date' => $date,
			'date_label' => $ts ? date('d.m.Y', $ts) : $date,
			'category' => (string) ($row['cat_group'] ?? ''),
			'particular' => (string) ($row['name'] ?? ''),
			'item' => $item,
			'quantity' => tessera_accounts_daily_num($row['quantity'] ?? 0),
			'vehicle' => $vehicle,
			'detail' => (string) ($row['detail'] ?? ''),
			'username' => (string) ($row['username'] ?? ''),
			'account' => (string) ($row['account'] ?? ''),
			'amount' => tessera_accounts_daily_num($row['amount'] ?? 0),
			'stock_amount' => tessera_accounts_daily_num($row['stock_amount'] ?? 0),
			'amount_remain' => tessera_accounts_daily_num($row['amount_remain'] ?? 0),
			'route' => (string) ($row['route_name'] ?? ''),
			'month' => (string) ($row['month'] ?? ''),
			'year' => (string) ($row['year'] ?? ''),
			'open_id' => (int) ($row['open_id'] ?? 0),
		];
	}
}

if (!function_exists('tessera_accounts_daily_list')) {
	/**
	 * @return array{items:list<array<string,mixed>>,summary:array{count:int,expenses:float,cash_remain:float},cash:?array{id:int,open_date:string,opening_stock:float,amount_remain:float}}
	 */
	function tessera_accounts_daily_list(
		mysqli $db,
		string $route,
		string $month,
		int $year,
		string $q = ''
	): array {
		$route = trim($route);
		$month = trim($month);
		if ($month === '') {
			$month = date('F');
		}
		if ($year <= 0) {
			$year = (int) date('Y');
		}

		$parts = [
			"month='" . $db->real_escape_string($month) . "'",
			"year='" . $db->real_escape_string((string) $year) . "'",
		];
		if ($route !== '') {
			$parts[] = "route_name='" . $db->real_escape_string($route) . "'";
		}
		$q = trim($q);
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(cat_group LIKE '{$like}' OR name LIKE '{$like}' OR detail LIKE '{$like}' OR vehicle LIKE '{$like}' OR item LIKE '{$like}')";
		}
		$where = implode(' AND ', $parts);

		$items = [];
		$expenses = 0.0;
		$sql = "SELECT id, cat_group, name, vehicle, date, detail, amount, username, stock_amount, amount_remain,
			account, month, year, route_name, item, quantity, open_id
			FROM office_expenses WHERE {$where} ORDER BY id ASC";
		$rq = $db->query($sql);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$item = tessera_accounts_daily_row_to_item($row);
				$items[] = $item;
				$expenses += $item['amount'];
			}
		}

		$cash = $route !== '' ? tessera_accounts_daily_cash_float($db, $route) : null;

		return [
			'items' => $items,
			'summary' => [
				'count' => count($items),
				'expenses' => $expenses,
				'cash_remain' => $cash['amount_remain'] ?? 0.0,
			],
			'cash' => $cash,
		];
	}
}

if (!function_exists('tessera_accounts_daily_add')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_daily_add(mysqli $db, string $username, array $input): array
	{
		date_default_timezone_set('Africa/Dar_es_Salaam');
		tessera_accounts_daily_require_tenant();

		$route = trim((string) ($input['route'] ?? ''));
		$category = trim((string) ($input['category'] ?? ''));
		$particular = trim((string) ($input['particular'] ?? ''));
		$date = trim((string) ($input['date'] ?? ''));
		$amount = tessera_accounts_daily_num($input['amount'] ?? 0);
		$account = trim((string) ($input['account'] ?? 'Cash'));
		$vehicle = trim((string) ($input['vehicle'] ?? ''));
		$detail = str_replace(',', '^', trim((string) ($input['detail'] ?? '')));
		$item = trim((string) ($input['item'] ?? ''));
		$quantity = tessera_accounts_daily_num($input['quantity'] ?? 0);

		if ($route === '') {
			return ['ok' => false, 'error' => 'Route is required'];
		}
		if ($category === '' || $particular === '' || $date === '') {
			return ['ok' => false, 'error' => 'Category, particular, and date are required'];
		}
		if ($amount <= 0) {
			return ['ok' => false, 'error' => 'Amount must be greater than zero'];
		}
		if ($account === '') {
			$account = 'Cash';
		}

		$cash = tessera_accounts_daily_cash_float($db, $route);
		if ($cash === null) {
			return ['ok' => false, 'error' => 'No cash float for this route — add cash on Withdraw first'];
		}

		$newRemain = $cash['amount_remain'] - $amount;
		$dateInsert = date('Y-m-d');
		$timeInsert = date('h:i A');
		$month = date('F', strtotime($dateInsert) ?: time());
		$year = (string) date('Y', strtotime($dateInsert) ?: time());
		$day = date('d', strtotime($date) ?: time());
		$tenantId = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 1);

		$cat = $db->real_escape_string($category);
		$name = $db->real_escape_string($particular);
		$veh = $db->real_escape_string($vehicle);
		$d = $db->real_escape_string($date);
		$det = $db->real_escape_string($detail);
		$acc = $db->real_escape_string($account);
		$u = $db->real_escape_string($username);
		$ti = $db->real_escape_string($timeInsert);
		$di = $db->real_escape_string($dateInsert);
		$m = $db->real_escape_string($month);
		$dy = $db->real_escape_string($day);
		$yr = $db->real_escape_string($year);
		$r = $db->real_escape_string($route);
		$openDate = $db->real_escape_string($cash['open_date']);
		$it = $db->real_escape_string($item);
		$openId = (int) $cash['id'];
		$stock = (int) $cash['opening_stock'];
		$remain = (int) round($newRemain);
		$amt = (int) round($amount);
		$qty = (int) round($quantity);

		$ok = $db->query(
			"INSERT INTO office_expenses
			(approved, cat_group, vehicle, date, detail, amount, amount_curr, username, time, insert_date_time,
			 stock_amount, amount_remain, name, account, day, month, year, balance, account_from, icon,
			 route_name, cash_hand, cash_date, cash_id, item, quantity, open_id, tenant_id)
			 VALUES
			('','{$cat}','{$veh}','{$d}','{$det}',{$amt},0,'{$u}','{$ti}','{$di}',
			 {$stock},{$remain},'{$name}','{$acc}','{$dy}','{$m}','{$yr}',0,'','',
			 '{$r}',0,'{$openDate}',0,'{$it}',{$qty},{$openId},{$tenantId})"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Failed to save expense'];
		}
		$id = (int) $db->insert_id;
		$db->query("UPDATE cash SET amount_remain={$remain} WHERE id={$openId} LIMIT 1");

		$rowQ = $db->query(
			"SELECT id, cat_group, name, vehicle, date, detail, amount, username, stock_amount, amount_remain,
				account, month, year, route_name, item, quantity, open_id
			 FROM office_expenses WHERE id={$id} LIMIT 1"
		);
		$row = $rowQ ? $rowQ->fetch_assoc() : null;
		return [
			'ok' => true,
			'item' => $row ? tessera_accounts_daily_row_to_item($row) : null,
		];
	}
}

if (!function_exists('tessera_accounts_daily_update')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_daily_update(mysqli $db, array $input): array
	{
		$id = (int) ($input['id'] ?? 0);
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Expense id required'];
		}
		$q = $db->query(
			"SELECT id, amount, open_id, cat_group, name, vehicle, date, detail, account, item, quantity, route_name
			 FROM office_expenses WHERE id={$id} LIMIT 1"
		);
		$existing = $q ? $q->fetch_assoc() : null;
		if (!$existing) {
			return ['ok' => false, 'error' => 'Expense not found'];
		}

		$oldAmount = tessera_accounts_daily_num($existing['amount'] ?? 0);
		$openId = (int) ($existing['open_id'] ?? 0);
		$newAmount = tessera_accounts_daily_num($input['amount'] ?? $oldAmount);
		$category = trim((string) ($input['category'] ?? $existing['cat_group'] ?? ''));
		$particular = trim((string) ($input['particular'] ?? $existing['name'] ?? ''));
		$vehicle = trim((string) ($input['vehicle'] ?? $existing['vehicle'] ?? ''));
		$date = trim((string) ($input['date'] ?? $existing['date'] ?? ''));
		$detail = str_replace(',', '^', trim((string) ($input['detail'] ?? $existing['detail'] ?? '')));
		$account = trim((string) ($input['account'] ?? $existing['account'] ?? 'Cash'));
		$item = trim((string) ($input['item'] ?? $existing['item'] ?? ''));
		$quantity = tessera_accounts_daily_num($input['quantity'] ?? $existing['quantity'] ?? 0);

		if ($category === '' || $particular === '' || $date === '' || $newAmount <= 0) {
			return ['ok' => false, 'error' => 'Category, particular, date, and amount are required'];
		}

		$delta = $oldAmount - $newAmount; /* positive = restore cash */
		if ($openId > 0 && abs($delta) > 0.0001) {
			$db->query(
				"UPDATE cash SET amount_remain = amount_remain + " . ((int) round($delta)) . " WHERE id={$openId} LIMIT 1"
			);
			$db->query(
				"UPDATE office_expenses SET amount_remain = amount_remain + " . ((int) round($delta)) .
				" WHERE id>={$id} AND open_id={$openId}"
			);
		}

		$cat = $db->real_escape_string($category);
		$name = $db->real_escape_string($particular);
		$veh = $db->real_escape_string($vehicle);
		$d = $db->real_escape_string($date);
		$det = $db->real_escape_string($detail);
		$acc = $db->real_escape_string($account);
		$it = $db->real_escape_string($item);
		$amt = (int) round($newAmount);
		$qty = (int) round($quantity);

		$ok = $db->query(
			"UPDATE office_expenses SET amount={$amt}, cat_group='{$cat}', name='{$name}', vehicle='{$veh}',
				date='{$d}', detail='{$det}', account='{$acc}', item='{$it}', quantity={$qty}
			 WHERE id={$id} LIMIT 1"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Failed to update expense'];
		}

		$rowQ = $db->query(
			"SELECT id, cat_group, name, vehicle, date, detail, amount, username, stock_amount, amount_remain,
				account, month, year, route_name, item, quantity, open_id
			 FROM office_expenses WHERE id={$id} LIMIT 1"
		);
		$row = $rowQ ? $rowQ->fetch_assoc() : null;
		return [
			'ok' => true,
			'item' => $row ? tessera_accounts_daily_row_to_item($row) : null,
		];
	}
}

if (!function_exists('tessera_accounts_daily_delete')) {
	/**
	 * @return array{ok:bool,error?:string}
	 */
	function tessera_accounts_daily_delete(mysqli $db, int $id): array
	{
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Expense id required'];
		}
		$q = $db->query(
			"SELECT amount, open_id FROM office_expenses WHERE id={$id} LIMIT 1"
		);
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Expense not found'];
		}
		$amount = (int) round(tessera_accounts_daily_num($row['amount'] ?? 0));
		$openId = (int) ($row['open_id'] ?? 0);

		if ($openId > 0 && $amount > 0) {
			$db->query("UPDATE cash SET amount_remain = amount_remain + {$amount} WHERE id={$openId} LIMIT 1");
			$db->query(
				"UPDATE office_expenses SET amount_remain = amount_remain + {$amount}
				 WHERE id>={$id} AND open_id={$openId}"
			);
		}
		$db->query("DELETE FROM office_expenses WHERE id={$id} LIMIT 1");
		return ['ok' => true];
	}
}
