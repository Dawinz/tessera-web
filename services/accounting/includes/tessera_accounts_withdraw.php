<?php
/**
 * Shared Accounts withdraw / cash float helpers (A-P0-04).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_accounts_daily.php';

if (!function_exists('tessera_accounts_withdraw_require_tenant')) {
	function tessera_accounts_withdraw_require_tenant(): void
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once dirname(__DIR__) . '/template/includes/tessera_tenant.php';
		}
	}
}

if (!function_exists('tessera_accounts_withdraw_num')) {
	function tessera_accounts_withdraw_num($value): float
	{
		return tessera_accounts_daily_num($value);
	}
}

if (!function_exists('tessera_accounts_withdraw_issuer')) {
	function tessera_accounts_withdraw_issuer(mysqli $db, string $username): string
	{
		$u = $db->real_escape_string($username);
		$q = $db->query("SELECT firstname, surname FROM users WHERE username='{$u}' LIMIT 1");
		if ($q && ($row = $q->fetch_assoc())) {
			$name = trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['surname'] ?? ''));
			if ($name !== '') {
				return $username . ' (' . $name . ')';
			}
		}
		return $username;
	}
}

if (!function_exists('tessera_accounts_withdraw_main_routes')) {
	/** @return list<array{route:string,label:string}> */
	function tessera_accounts_withdraw_main_routes(mysqli $db): array
	{
		$out = [];
		$q = $db->query("SELECT fullname, name FROM route WHERE status='main' ORDER BY id ASC");
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$route = trim((string) ($row['fullname'] ?? ''));
				if ($route === '') {
					continue;
				}
				$raw = (string) ($row['name'] ?? '');
				$label = base64_decode($raw, true);
				if ($label === false || $label === '') {
					$label = $route;
				}
				$out[] = ['route' => $route, 'label' => $label];
			}
		}
		if ($out === []) {
			foreach (tessera_accounts_daily_routes($db, '') as $rn) {
				$out[] = ['route' => $rn, 'label' => $rn];
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_withdraw_bootstrap')) {
	/**
	 * @return array{routes:list<array{route:string,label:string}>}
	 */
	function tessera_accounts_withdraw_bootstrap(mysqli $db): array
	{
		return [
			'routes' => tessera_accounts_withdraw_main_routes($db),
		];
	}
}

if (!function_exists('tessera_accounts_withdraw_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_accounts_withdraw_row_to_item(array $row): array
	{
		$openDate = (string) ($row['open_date'] ?? '');
		$ts = $openDate !== '' ? strtotime($openDate) : false;
		$insertDate = (string) ($row['date'] ?? '');
		return [
			'id' => (int) ($row['id'] ?? 0),
			'route' => (string) ($row['route_name'] ?? ''),
			'open_date' => $openDate,
			'open_date_label' => $ts ? date('D j M Y', $ts) : $openDate,
			'authorizer' => (string) ($row['authorizer'] ?? ''),
			'issuer' => (string) ($row['issuer'] ?? ''),
			'amount' => tessera_accounts_withdraw_num($row['opening_stock'] ?? 0),
			'amount_remain' => tessera_accounts_withdraw_num($row['amount_remain'] ?? 0),
			'account' => (string) ($row['account'] ?? ''),
			'date_insert' => $insertDate,
			'time_insert' => (string) ($row['time'] ?? ''),
			'month' => (string) ($row['month'] ?? ''),
			'year' => (string) ($row['year'] ?? ''),
		];
	}
}

if (!function_exists('tessera_accounts_withdraw_list')) {
	/**
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array{count:int,deposited:float,latest_remain:float}}
	 */
	function tessera_accounts_withdraw_list(
		mysqli $db,
		string $route = '',
		string $q = '',
		int $page = 1,
		int $perPage = 25
	): array {
		tessera_accounts_withdraw_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$parts = [$tenant];
		$route = trim($route);
		if ($route !== '' && strtolower($route) !== 'all') {
			$parts[] = "route_name='" . $db->real_escape_string($route) . "'";
		}
		$q = trim($q);
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(authorizer LIKE '{$like}' OR issuer LIKE '{$like}' OR open_date LIKE '{$like}' OR route_name LIKE '{$like}')";
		}
		$where = implode(' AND ', $parts);

		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM cash WHERE {$where}");
		if ($cq && ($crow = $cq->fetch_assoc())) {
			$total = (int) ($crow['c'] ?? 0);
		}

		$items = [];
		$deposited = 0.0;
		$sql = "SELECT id, route_name, open_date, authorizer, issuer, opening_stock, amount_remain, account, date, time, month, year
			FROM cash WHERE {$where} ORDER BY open_date DESC, id DESC LIMIT {$perPage} OFFSET {$offset}";
		$rq = $db->query($sql);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$item = tessera_accounts_withdraw_row_to_item($row);
				$items[] = $item;
				$deposited += $item['amount'];
			}
		}

		$latestRemain = 0.0;
		if ($route !== '' && strtolower($route) !== 'all') {
			$cash = tessera_accounts_daily_cash_float($db, $route);
			$latestRemain = $cash['amount_remain'] ?? 0.0;
		} else {
			/* Sum of latest remain per route (approx float on hand). */
			$sumQ = $db->query(
				"SELECT SUM(t.amount_remain) AS s FROM (
					SELECT route_name, MAX(id) AS max_id FROM cash WHERE {$tenant} GROUP BY route_name
				) m JOIN cash t ON t.id = m.max_id"
			);
			if ($sumQ && ($srow = $sumQ->fetch_assoc())) {
				$latestRemain = tessera_accounts_withdraw_num($srow['s'] ?? 0);
			}
		}

		return [
			'items' => $items,
			'total' => $total,
			'summary' => [
				'count' => $total,
				'deposited' => $deposited,
				'latest_remain' => $latestRemain,
			],
		];
	}
}

if (!function_exists('tessera_accounts_withdraw_add')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_accounts_withdraw_add(mysqli $db, string $username, array $input): array
	{
		date_default_timezone_set('Africa/Dar_es_Salaam');
		tessera_accounts_withdraw_require_tenant();

		$route = trim((string) ($input['route'] ?? ''));
		$openDateRaw = trim((string) ($input['date'] ?? $input['open_date'] ?? ''));
		$amount = tessera_accounts_withdraw_num($input['amount'] ?? 0);
		$authorizer = trim((string) ($input['authorizer'] ?? ''));

		if ($route === '' || $openDateRaw === '' || $authorizer === '') {
			return ['ok' => false, 'error' => 'Route, effective date, and authorizer are required'];
		}
		if ($amount <= 0) {
			return ['ok' => false, 'error' => 'Amount must be greater than zero'];
		}

		$ts = strtotime($openDateRaw);
		if ($ts === false) {
			return ['ok' => false, 'error' => 'Invalid effective date'];
		}
		$openDate = date('Y-m-d', $ts);
		$year = date('Y', $ts);
		$month = date('F', $ts);
		$day = date('d', $ts);
		$dateInsert = date('Y-m-d');
		$timeInsert = date('h:i A');
		$issuer = tessera_accounts_withdraw_issuer($db, $username);

		$prevRemain = 0.0;
		$cash = tessera_accounts_daily_cash_float($db, $route);
		if ($cash !== null) {
			$prevRemain = $cash['amount_remain'];
		}
		$newRemain = (int) round($prevRemain + $amount);
		$amt = (int) round($amount);
		$tenantId = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 1);

		$r = $db->real_escape_string($route);
		$iss = $db->real_escape_string($issuer);
		$auth = $db->real_escape_string($authorizer);
		$od = $db->real_escape_string($openDate);
		$di = $db->real_escape_string($dateInsert);
		$ti = $db->real_escape_string($timeInsert);
		$m = $db->real_escape_string($month);
		$dy = $db->real_escape_string($day);
		$yr = $db->real_escape_string($year);

		$ok = $db->query(
			"INSERT INTO cash
			(opening_stock, issuer, date, time, authorizer, open_date, stock_no, amount_remain, initial_am_remain,
			 status, percentage, color, amount_dollar, account, day, month, year, bank, check_no, check_to, vehicle, name, route_name, tenant_id)
			 VALUES
			({$amt},'{$iss}','{$di}','{$ti}','{$auth}','{$od}',0,{$newRemain},{$amt},
			 '','0','',0.00,'','{$dy}','{$m}','{$yr}','','','','','','{$r}',{$tenantId})"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Failed to save cash deposit'];
		}
		$id = (int) $db->insert_id;
		$rowQ = $db->query(
			"SELECT id, route_name, open_date, authorizer, issuer, opening_stock, amount_remain, account, date, time, month, year
			 FROM cash WHERE id={$id} LIMIT 1"
		);
		$row = $rowQ ? $rowQ->fetch_assoc() : null;
		return [
			'ok' => true,
			'item' => $row ? tessera_accounts_withdraw_row_to_item($row) : null,
		];
	}
}
