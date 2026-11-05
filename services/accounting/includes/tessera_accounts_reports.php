<?php
/**
 * Shared Accounts reports (A-P1-18…25).
 * Live vehicle_account, office_expenses, and cash — same books as trip P&L.
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_accounts_pnl.php';

if (!function_exists('tessera_accounts_reports_num')) {
	function tessera_accounts_reports_num($value): float
	{
		return tessera_accounts_manifests_num($value);
	}
}

if (!function_exists('tessera_accounts_reports_money')) {
	function tessera_accounts_reports_money(float $value): float
	{
		return (float) round($value);
	}
}

if (!function_exists('tessera_accounts_reports_date')) {
	function tessera_accounts_reports_date(string $raw): string
	{
		$raw = trim($raw);
		if ($raw === '') {
			return '';
		}
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
			return $raw;
		}
		$ts = strtotime($raw);
		return $ts ? date('Y-m-d', $ts) : '';
	}
}

if (!function_exists('tessera_accounts_reports_dates')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array{0:string,1:string}
	 */
	function tessera_accounts_reports_dates(array $filters): array
	{
		$from = tessera_accounts_reports_date((string) ($filters['date_from'] ?? ''));
		$to = tessera_accounts_reports_date((string) ($filters['date_to'] ?? ''));
		if ($from === '') {
			$from = date('Y-m-01');
		}
		if ($to === '') {
			$to = date('Y-m-d');
		}
		if ($from > $to) {
			$swap = $from;
			$from = $to;
			$to = $swap;
		}
		return [$from, $to];
	}
}

if (!function_exists('tessera_accounts_reports_query_filters')) {
	/**
	 * @return array<string,string>
	 */
	function tessera_accounts_reports_query_filters(): array
	{
		$keys = [
			'q', 'route', 'vehicle', 'currency', 'date_from', 'date_to',
			'as_of', 'vehicle_category', 'mode', 'ids', 'category', 'particular', 'name',
		];
		$out = [];
		foreach ($keys as $key) {
			$out[$key] = trim((string) ($_GET[$key] ?? ''));
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_reports_rows')) {
	/**
	 * @return list<array<string,mixed>>
	 */
	function tessera_accounts_reports_rows(mysqli $db, string $sql): array
	{
		$out = [];
		$q = $db->query($sql);
		if (!$q) {
			error_log('tessera_accounts_reports: ' . $db->error);
			return [];
		}
		while ($row = $q->fetch_assoc()) {
			$out[] = $row;
		}
		return $out;
	}
}

if (!function_exists('tessera_accounts_reports_primary')) {
	/**
	 * @return array{name:string,symbol:string}
	 */
	function tessera_accounts_reports_primary(mysqli $db): array
	{
		static $cached = null;
		if (is_array($cached)) {
			return $cached;
		}
		$name = 'Tsh';
		$symbol = 'Tsh';
		$rows = tessera_accounts_reports_rows($db, 'SELECT name, symbol, status FROM currency ORDER BY id ASC');
		foreach ($rows as $row) {
			$candidate = trim((string) ($row['name'] ?? ''));
			if ($candidate === '') {
				continue;
			}
			if ($name === 'Tsh' && $symbol === 'Tsh') {
				$name = $candidate;
				$symbol = trim((string) ($row['symbol'] ?? '')) ?: $candidate;
			}
			if (stripos((string) ($row['status'] ?? ''), 'Primary') !== false) {
				$name = $candidate;
				$symbol = trim((string) ($row['symbol'] ?? '')) ?: $candidate;
				break;
			}
		}
		$cached = ['name' => $name, 'symbol' => $symbol];
		return $cached;
	}
}

if (!function_exists('tessera_accounts_reports_fx')) {
	/**
	 * Key "target-source" => multiplier (source amount * rate = target amount).
	 *
	 * @return array<string,float>
	 */
	function tessera_accounts_reports_fx(mysqli $db): array
	{
		$map = [];
		foreach (tessera_accounts_reports_rows($db, 'SELECT first_point, second_point, value FROM currency_value') as $row) {
			$from = trim((string) ($row['first_point'] ?? ''));
			$to = trim((string) ($row['second_point'] ?? ''));
			if ($from === '' || $to === '') {
				continue;
			}
			$map[$to . '-' . $from] = (float) ($row['value'] ?? 0);
		}
		return $map;
	}
}

if (!function_exists('tessera_accounts_reports_rate')) {
	/**
	 * @param array<string,float> $map
	 */
	function tessera_accounts_reports_rate(string $from, string $to, array $map): float
	{
		$from = trim($from);
		$to = trim($to);
		if ($from === '' || strcasecmp($from, 'Choose Currency') === 0) {
			$from = $to;
		}
		if ($to === '' || strcasecmp($from, $to) === 0) {
			return 1.0;
		}
		$key = $to . '-' . $from;
		if (isset($map[$key]) && $map[$key] > 0) {
			return (float) $map[$key];
		}
		$inverse = $from . '-' . $to;
		if (isset($map[$inverse]) && $map[$inverse] > 0) {
			return 1.0 / (float) $map[$inverse];
		}
		foreach ($map as $pair => $value) {
			[$mapTo, $mapFrom] = array_pad(explode('-', $pair, 2), 2, '');
			if (strcasecmp($mapTo, $to) === 0 && strcasecmp($mapFrom, $from) === 0 && $value > 0) {
				return (float) $value;
			}
		}
		return 1.0;
	}
}

if (!function_exists('tessera_accounts_reports_target_currency')) {
	/**
	 * @param array<string,mixed> $filters
	 */
	function tessera_accounts_reports_target_currency(mysqli $db, array $filters): string
	{
		$wanted = trim((string) ($filters['currency'] ?? ''));
		if ($wanted === '' || strcasecmp($wanted, 'all') === 0) {
			return tessera_accounts_reports_primary($db)['name'];
		}
		return $wanted;
	}
}

if (!function_exists('tessera_accounts_reports_scope')) {
	/**
	 * vehicle_account WHERE fragment (no leading WHERE).
	 *
	 * @param array<string,mixed> $filters
	 */
	function tessera_accounts_reports_scope(
		mysqli $db,
		string $username,
		array $filters,
		string $alias = '',
		bool $withDates = true
	): string {
		tessera_accounts_manifests_require_tenant();
		$p = $alias !== '' ? preg_replace('/[^a-zA-Z0-9_]/', '', $alias) . '.' : '';
		$parts = [tessera_sql_tenant_eq($alias)];
		if ($withDates) {
			[$from, $to] = tessera_accounts_reports_dates($filters);
			$parts[] = $p . "trip_date>='" . $db->real_escape_string($from) . "'";
			$parts[] = $p . "trip_date<='" . $db->real_escape_string($to) . "'";
		}
		$route = trim((string) ($filters['route'] ?? ''));
		if ($route !== '') {
			$parts[] = $p . "route_name='" . $db->real_escape_string($route) . "'";
		} else {
			$assigned = tessera_accounts_manifests_assigned_routes($db, $username);
			if ($assigned !== []) {
				$in = array_map(static function ($name) use ($db) {
					return "'" . $db->real_escape_string($name) . "'";
				}, $assigned);
				$parts[] = $p . 'route_name IN (' . implode(',', $in) . ')';
			}
		}
		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		if ($vehicle !== '') {
			$parts[] = $p . "bus='" . $db->real_escape_string($vehicle) . "'";
		}
		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = '(' . $p . "bus LIKE '{$like}' OR " . $p . "route_name LIKE '{$like}' OR "
				. $p . "category LIKE '{$like}' OR " . $p . "name LIKE '{$like}')";
		}
		return implode(' AND ', $parts);
	}
}

if (!function_exists('tessera_accounts_reports_applied')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,string>
	 */
	function tessera_accounts_reports_applied(mysqli $db, array $filters): array
	{
		[$from, $to] = tessera_accounts_reports_dates($filters);
		$primary = tessera_accounts_reports_primary($db);
		return [
			'date_from' => $from,
			'date_to' => $to,
			'period_label' => tessera_accounts_pnl_period_label($from, $to),
			'currency' => tessera_accounts_reports_target_currency($db, $filters),
			'symbol' => $primary['symbol'],
			'route' => trim((string) ($filters['route'] ?? '')),
			'vehicle' => trim((string) ($filters['vehicle'] ?? '')),
		];
	}
}

if (!function_exists('tessera_accounts_reports_filters')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_filters(mysqli $db, string $username): array
	{
		$base = tessera_accounts_manifests_filters($db, $username);
		$primary = tessera_accounts_reports_primary($db);
		$currencies = [];
		foreach (tessera_accounts_reports_rows($db, 'SELECT id, name, symbol, status FROM currency ORDER BY id ASC') as $row) {
			$name = trim((string) ($row['name'] ?? ''));
			if ($name === '') {
				continue;
			}
			$currencies[] = [
				'id' => (int) ($row['id'] ?? 0),
				'name' => $name,
				'symbol' => trim((string) ($row['symbol'] ?? '')) ?: $name,
				'primary' => strcasecmp($name, $primary['name']) === 0,
			];
		}
		$categories = [];
		foreach (tessera_accounts_reports_rows($db, 'SELECT id, name FROM particular_category ORDER BY name ASC') as $row) {
			$categories[] = [
				'id' => (int) ($row['id'] ?? 0),
				'name' => trim((string) ($row['name'] ?? '')),
			];
		}
		$particulars = [];
		foreach (tessera_accounts_reports_rows(
			$db,
			'SELECT id, name, category FROM particulars ORDER BY category ASC, name ASC LIMIT 500'
		) as $row) {
			$particulars[] = [
				'id' => (int) ($row['id'] ?? 0),
				'name' => trim((string) ($row['name'] ?? '')),
				'category' => trim((string) ($row['category'] ?? '')),
			];
		}
		$vehicleCategories = [];
		foreach (tessera_accounts_reports_rows($db, 'SELECT name FROM vehicle_category ORDER BY name ASC') as $row) {
			$name = trim((string) ($row['name'] ?? ''));
			if ($name !== '') {
				$vehicleCategories[] = $name;
			}
		}
		$vehicles = [];
		tessera_accounts_manifests_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		foreach (tessera_accounts_reports_rows(
			$db,
			"SELECT DISTINCT bus FROM vehicle_account WHERE {$tenant} AND bus<>'' ORDER BY bus ASC LIMIT 400"
		) as $row) {
			$bus = trim((string) ($row['bus'] ?? ''));
			if ($bus !== '') {
				$vehicles[] = $bus;
			}
		}
		if ($vehicles === []) {
			$vehicles = $base['vehicles'] ?? [];
		}
		return [
			'routes' => $base['routes'] ?? [],
			'vehicles' => $vehicles,
			'currencies' => $currencies,
			'primary_currency' => $primary['name'],
			'categories' => $categories,
			'particulars' => $particulars,
			'vehicle_categories' => $vehicleCategories,
		];
	}
}

if (!function_exists('tessera_accounts_reports_income_sql')) {
	function tessera_accounts_reports_income_sql(string $alias = ''): string
	{
		$p = $alias !== '' ? $alias . '.' : '';
		return "SUM(CASE WHEN {$p}status='Income' AND {$p}category='Passanger' AND {$p}name='Ticket' THEN {$p}income ELSE 0 END) AS ticket,
			SUM(CASE WHEN {$p}status='Income' AND {$p}category='Passanger' AND {$p}name='Road collection' THEN {$p}income ELSE 0 END) AS road,
			SUM(CASE WHEN {$p}status='Income' AND {$p}category='Cargo' AND {$p}name='Paid' THEN {$p}income ELSE 0 END) AS cargo_paid,
			SUM(CASE WHEN {$p}status='Income' AND {$p}category='Cargo' AND {$p}name<>'Paid' THEN {$p}income ELSE 0 END) AS cargo_to_pay,
			SUM(CASE WHEN {$p}status='Expenses' AND {$p}affect='Paid' THEN {$p}expenses ELSE 0 END) AS expenses";
	}
}

if (!function_exists('tessera_accounts_reports_vehicle_statement')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_vehicle_statement(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$sql = "SELECT bus, route_name, IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency, "
			. tessera_accounts_reports_income_sql()
			. " FROM vehicle_account WHERE {$where} GROUP BY bus, route_name, currency ORDER BY bus ASC";
		$folded = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$bus = trim((string) ($row['bus'] ?? ''));
			if ($bus === '') {
				continue;
			}
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			if (!isset($folded[$bus])) {
				$folded[$bus] = [
					'bus' => $bus,
					'routes' => [],
					'ticket' => 0.0,
					'road' => 0.0,
					'cargo_paid' => 0.0,
					'cargo_to_pay' => 0.0,
					'expenses' => 0.0,
				];
			}
			$folded[$bus]['ticket'] += tessera_accounts_reports_num($row['ticket'] ?? 0) * $rate;
			$folded[$bus]['road'] += tessera_accounts_reports_num($row['road'] ?? 0) * $rate;
			$folded[$bus]['cargo_paid'] += tessera_accounts_reports_num($row['cargo_paid'] ?? 0) * $rate;
			$folded[$bus]['cargo_to_pay'] += tessera_accounts_reports_num($row['cargo_to_pay'] ?? 0) * $rate;
			$folded[$bus]['expenses'] += tessera_accounts_reports_num($row['expenses'] ?? 0) * $rate;
			$route = trim((string) ($row['route_name'] ?? ''));
			if ($route !== '') {
				$folded[$bus]['routes'][$route] = true;
			}
		}
		$items = [];
		$summary = [
			'vehicles' => 0,
			'ticket' => 0.0,
			'road' => 0.0,
			'cargo_paid' => 0.0,
			'cargo_to_pay' => 0.0,
			'income' => 0.0,
			'expenses' => 0.0,
			'balance' => 0.0,
		];
		foreach ($folded as $row) {
			$income = $row['ticket'] + $row['road'] + $row['cargo_paid'] + $row['cargo_to_pay'];
			$expenses = $row['expenses'];
			$balance = $income - $expenses;
			$item = [
				'bus' => $row['bus'],
				'routes' => implode(', ', array_keys($row['routes'])),
				'ticket' => tessera_accounts_reports_money($row['ticket']),
				'road' => tessera_accounts_reports_money($row['road']),
				'cargo_paid' => tessera_accounts_reports_money($row['cargo_paid']),
				'cargo_to_pay' => tessera_accounts_reports_money($row['cargo_to_pay']),
				'income' => tessera_accounts_reports_money($income),
				'expenses' => tessera_accounts_reports_money($expenses),
				'balance' => tessera_accounts_reports_money($balance),
			];
			$items[] = $item;
			$summary['vehicles']++;
			$summary['ticket'] += $item['ticket'];
			$summary['road'] += $item['road'];
			$summary['cargo_paid'] += $item['cargo_paid'];
			$summary['cargo_to_pay'] += $item['cargo_to_pay'];
			$summary['income'] += $item['income'];
			$summary['expenses'] += $item['expenses'];
			$summary['balance'] += $item['balance'];
		}
		return [
			'applied' => $applied,
			'items' => $items,
			'summary' => $summary,
		];
	}
}

if (!function_exists('tessera_accounts_reports_vehicle_grouped')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_vehicle_grouped(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters, 'a');
		$category = trim((string) ($filters['vehicle_category'] ?? ''));
		$categorySql = '';
		if ($category !== '' && strcasecmp($category, 'all') !== 0) {
			$categorySql = " AND v.category_name='" . $db->real_escape_string($category) . "'";
		}
		$sql = "SELECT v.category_name, v.name AS vehicle,
				IFNULL(NULLIF(a.currency,''),'{$db->real_escape_string($target)}') AS currency,
				SUM(a.income) AS income,
				SUM(a.expenses) AS expenses
			FROM vehicle_particular_details v
			INNER JOIN vehicle_account a ON a.bus = v.name
			WHERE {$where}{$categorySql}
			GROUP BY v.category_name, v.name, currency
			ORDER BY v.category_name ASC, v.name ASC";
		$tripCounts = [];
		foreach (tessera_accounts_reports_rows(
			$db,
			"SELECT v.name AS vehicle, COUNT(DISTINCT a.trip_date) AS trips
			 FROM vehicle_particular_details v
			 INNER JOIN vehicle_account a ON a.bus = v.name
			 WHERE {$where}{$categorySql}
			 GROUP BY v.name"
		) as $row) {
			$tripCounts[trim((string) ($row['vehicle'] ?? ''))] = (int) ($row['trips'] ?? 0);
		}
		$folded = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$vehicle = trim((string) ($row['vehicle'] ?? ''));
			if ($vehicle === '') {
				continue;
			}
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			$key = trim((string) ($row['category_name'] ?? '')) . "\n" . $vehicle;
			if (!isset($folded[$key])) {
				$folded[$key] = [
					'category' => trim((string) ($row['category_name'] ?? '')),
					'vehicle' => $vehicle,
					'income' => 0.0,
					'expenses' => 0.0,
					'trips' => $tripCounts[$vehicle] ?? 0,
				];
			}
			$folded[$key]['income'] += tessera_accounts_reports_num($row['income'] ?? 0) * $rate;
			$folded[$key]['expenses'] += tessera_accounts_reports_num($row['expenses'] ?? 0) * $rate;
		}
		$items = [];
		$groups = [];
		$summary = ['vehicles' => 0, 'income' => 0.0, 'expenses' => 0.0, 'balance' => 0.0, 'trips' => 0];
		foreach ($folded as $row) {
			if ($row['trips'] <= 0 && $row['income'] == 0.0 && $row['expenses'] == 0.0) {
				continue;
			}
			$balance = $row['income'] - $row['expenses'];
			$item = [
				'category' => $row['category'],
				'vehicle' => $row['vehicle'],
				'income' => tessera_accounts_reports_money($row['income']),
				'expenses' => tessera_accounts_reports_money($row['expenses']),
				'balance' => tessera_accounts_reports_money($balance),
				'trips' => $row['trips'],
			];
			$items[] = $item;
			$cat = $row['category'] !== '' ? $row['category'] : 'Ungrouped';
			if (!isset($groups[$cat])) {
				$groups[$cat] = [
					'category' => $cat,
					'vehicles' => 0,
					'income' => 0.0,
					'expenses' => 0.0,
					'balance' => 0.0,
					'trips' => 0,
				];
			}
			$groups[$cat]['vehicles']++;
			$groups[$cat]['income'] += $item['income'];
			$groups[$cat]['expenses'] += $item['expenses'];
			$groups[$cat]['balance'] += $item['balance'];
			$groups[$cat]['trips'] += $item['trips'];
			$summary['vehicles']++;
			$summary['income'] += $item['income'];
			$summary['expenses'] += $item['expenses'];
			$summary['balance'] += $item['balance'];
			$summary['trips'] += $item['trips'];
		}
		return [
			'applied' => $applied,
			'items' => $items,
			'groups' => array_values($groups),
			'summary' => $summary,
		];
	}
}

if (!function_exists('tessera_accounts_reports_category_names')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array{mode:string,names:list<string>,categories:list<string>}|null null = no id filter
	 */
	function tessera_accounts_reports_category_names(mysqli $db, array $filters): ?array
	{
		$raw = trim((string) ($filters['ids'] ?? ''));
		if ($raw === '') {
			return null;
		}
		$ids = [];
		foreach (preg_split('/\s*,\s*/', $raw) ?: [] as $part) {
			$id = (int) $part;
			if ($id > 0) {
				$ids[] = $id;
			}
		}
		$ids = array_values(array_unique($ids));
		if ($ids === []) {
			return null;
		}
		$mode = strtolower(trim((string) ($filters['mode'] ?? 'category'))) === 'particular' ? 'particular' : 'category';
		$in = implode(',', $ids);
		$names = [];
		$categories = [];
		if ($mode === 'particular') {
			foreach (tessera_accounts_reports_rows(
				$db,
				"SELECT name, category FROM particulars WHERE id IN ({$in})"
			) as $row) {
				$name = trim((string) ($row['name'] ?? ''));
				if ($name !== '') {
					$names[] = $name;
					$categories[] = trim((string) ($row['category'] ?? ''));
				}
			}
		} else {
			foreach (tessera_accounts_reports_rows(
				$db,
				"SELECT name FROM particular_category WHERE id IN ({$in})"
			) as $row) {
				$name = trim((string) ($row['name'] ?? ''));
				if ($name !== '') {
					$names[] = $name;
				}
			}
		}
		return [
			'mode' => $mode,
			'names' => array_values(array_unique($names)),
			'categories' => array_values(array_unique(array_filter($categories))),
		];
	}
}

if (!function_exists('tessera_accounts_reports_category_filter')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_category_filter(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$picked = tessera_accounts_reports_category_names($db, $filters);
		$mode = $picked['mode'] ?? (strtolower(trim((string) ($filters['mode'] ?? 'category'))) === 'particular' ? 'particular' : 'category');
		$extra = '';
		if ($picked !== null) {
			if ($picked['names'] === []) {
				return [
					'applied' => $applied,
					'mode' => $mode,
					'rows' => [],
					'summary' => ['lines' => 0, 'total' => 0.0],
				];
			}
			$in = implode(',', array_map(static function ($name) use ($db) {
				return "'" . $db->real_escape_string($name) . "'";
			}, $picked['names']));
			if ($mode === 'particular') {
				$extra = " AND TRIM(name) IN ({$in})";
				if ($picked['categories'] !== []) {
					$cats = implode(',', array_map(static function ($name) use ($db) {
						return "'" . $db->real_escape_string($name) . "'";
					}, $picked['categories']));
					$extra .= " AND TRIM(category) IN ({$cats})";
				}
			} else {
				$extra = " AND TRIM(category) IN ({$in})";
			}
		}
		if ($mode === 'particular') {
			$sql = "SELECT TRIM(name) AS line_name, TRIM(category) AS category_name, route_name,
					IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
					SUM(expenses) AS amount
				FROM vehicle_account
				WHERE {$where} AND status='Expenses' AND expenses<>0 {$extra}
				GROUP BY line_name, category_name, route_name, currency";
		} else {
			$sql = "SELECT TRIM(category) AS line_name, '' AS category_name, route_name,
					IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
					SUM(expenses) AS amount
				FROM vehicle_account
				WHERE {$where} AND status='Expenses' AND expenses<>0 {$extra}
				GROUP BY line_name, route_name, currency";
		}
		$folded = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$name = trim((string) ($row['line_name'] ?? ''));
			if ($name === '') {
				continue;
			}
			$route = trim((string) ($row['route_name'] ?? ''));
			$key = $name . "\n" . $route;
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			if (!isset($folded[$key])) {
				$folded[$key] = [
					'name' => $name,
					'category' => trim((string) ($row['category_name'] ?? '')),
					'route' => $route,
					'amount' => 0.0,
				];
			}
			$folded[$key]['amount'] += tessera_accounts_reports_num($row['amount'] ?? 0) * $rate;
		}
		$rows = array_values($folded);
		usort($rows, static function ($a, $b) {
			return $b['amount'] <=> $a['amount'];
		});
		$total = 0.0;
		foreach ($rows as $i => $row) {
			$rows[$i]['amount'] = tessera_accounts_reports_money($row['amount']);
			$total += $rows[$i]['amount'];
		}
		return [
			'applied' => $applied,
			'mode' => $mode,
			'rows' => $rows,
			'summary' => [
				'lines' => count($rows),
				'total' => $total,
			],
		];
	}
}

if (!function_exists('tessera_accounts_reports_office_where')) {
	/**
	 * @param array<string,mixed> $filters
	 */
	function tessera_accounts_reports_office_where(mysqli $db, string $username, array $filters, string $from, string $to): string
	{
		tessera_accounts_manifests_require_tenant();
		$parts = [
			tessera_sql_tenant_eq(),
			"date>='" . $db->real_escape_string($from) . "'",
			"date<='" . $db->real_escape_string($to) . "'",
		];
		$route = trim((string) ($filters['route'] ?? ''));
		if ($route !== '') {
			$parts[] = "route_name='" . $db->real_escape_string($route) . "'";
		}
		return implode(' AND ', $parts);
	}
}

if (!function_exists('tessera_accounts_reports_cash_where')) {
	/**
	 * @param array<string,mixed> $filters
	 */
	function tessera_accounts_reports_cash_where(mysqli $db, string $username, array $filters, string $from, string $to, bool $before = false): string
	{
		tessera_accounts_manifests_require_tenant();
		$parts = [tessera_sql_tenant_eq()];
		if ($before) {
			$parts[] = "open_date<'" . $db->real_escape_string($from) . "'";
		} else {
			$parts[] = "open_date>='" . $db->real_escape_string($from) . "'";
			$parts[] = "open_date<='" . $db->real_escape_string($to) . "'";
		}
		$route = trim((string) ($filters['route'] ?? ''));
		if ($route !== '') {
			$parts[] = "route_name='" . $db->real_escape_string($route) . "'";
		} else {
			$assigned = tessera_accounts_manifests_assigned_routes($db, $username);
			if ($assigned !== []) {
				$in = array_map(static function ($name) use ($db) {
					return "'" . $db->real_escape_string($name) . "'";
				}, $assigned);
				$parts[] = 'route_name IN (' . implode(',', $in) . ')';
			}
		}
		return implode(' AND ', $parts);
	}
}

if (!function_exists('tessera_accounts_reports_cash_flow')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_cash_flow(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		[$from, $to] = [$applied['date_from'], $applied['date_to']];
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$days = [];
		$span = (int) floor((strtotime($to) - strtotime($from)) / 86400);
		$fill = $span >= 0 && $span <= 62;
		if ($fill) {
			for ($ts = strtotime($from); $ts <= strtotime($to); $ts += 86400) {
				$key = date('Y-m-d', $ts);
				$days[$key] = [
					'date' => $key,
					'label' => date('D, j M', $ts),
					'cash_in' => 0.0,
					'trip_costs' => 0.0,
					'office' => 0.0,
					'deposited' => 0.0,
				];
			}
		}
		$ensure = static function (string $date) use (&$days): void {
			if ($date === '' || isset($days[$date])) {
				return;
			}
			$ts = strtotime($date);
			$days[$date] = [
				'date' => $date,
				'label' => $ts ? date('D, j M', $ts) : $date,
				'cash_in' => 0.0,
				'trip_costs' => 0.0,
				'office' => 0.0,
				'deposited' => 0.0,
			];
		};
		$sql = "SELECT trip_date AS d, IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
				SUM(CASE WHEN status='Income' THEN income ELSE 0 END) AS cash_in,
				SUM(CASE WHEN status='Expenses' AND affect='Paid' THEN expenses ELSE 0 END) AS trip_costs
			FROM vehicle_account WHERE {$where}
			GROUP BY trip_date, currency";
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$date = tessera_accounts_reports_date((string) ($row['d'] ?? ''));
			if ($date === '') {
				continue;
			}
			$ensure($date);
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			$days[$date]['cash_in'] += tessera_accounts_reports_num($row['cash_in'] ?? 0) * $rate;
			$days[$date]['trip_costs'] += tessera_accounts_reports_num($row['trip_costs'] ?? 0) * $rate;
		}
		$officeWhere = tessera_accounts_reports_office_where($db, $username, $filters, $from, $to);
		foreach (tessera_accounts_reports_rows(
			$db,
			"SELECT date AS d, SUM(amount) AS amt FROM office_expenses WHERE {$officeWhere} GROUP BY date"
		) as $row) {
			$date = tessera_accounts_reports_date((string) ($row['d'] ?? ''));
			if ($date === '') {
				continue;
			}
			$ensure($date);
			$days[$date]['office'] += tessera_accounts_reports_num($row['amt'] ?? 0);
		}
		$cashWhere = tessera_accounts_reports_cash_where($db, $username, $filters, $from, $to, false);
		$deposits = [];
		foreach (tessera_accounts_reports_rows(
			$db,
			"SELECT id, open_date, route_name, opening_stock, authorizer
			 FROM cash WHERE {$cashWhere} ORDER BY open_date ASC, id ASC"
		) as $row) {
			$date = tessera_accounts_reports_date((string) ($row['open_date'] ?? ''));
			$amount = tessera_accounts_reports_num($row['opening_stock'] ?? 0);
			if ($date !== '') {
				$ensure($date);
				$days[$date]['deposited'] += $amount;
			}
			$ts = $date !== '' ? strtotime($date) : false;
			$deposits[] = [
				'date' => $date,
				'date_label' => $ts ? date('D, j M Y', $ts) : $date,
				'route' => (string) ($row['route_name'] ?? ''),
				'authorizer' => (string) ($row['authorizer'] ?? ''),
				'amount' => tessera_accounts_reports_money($amount),
			];
		}
		ksort($days);
		$opening = 0.0;
		$seenRoute = [];
		$before = tessera_accounts_reports_cash_where($db, $username, $filters, $from, $to, true);
		foreach (tessera_accounts_reports_rows(
			$db,
			"SELECT route_name, amount_remain FROM cash WHERE {$before} ORDER BY open_date DESC, id DESC"
		) as $row) {
			$route = (string) ($row['route_name'] ?? '');
			if (isset($seenRoute[$route])) {
				continue;
			}
			$seenRoute[$route] = true;
			$opening += tessera_accounts_reports_num($row['amount_remain'] ?? 0);
		}
		$balance = $opening;
		$cashIn = 0.0;
		$tripCosts = 0.0;
		$office = 0.0;
		$deposited = 0.0;
		$list = [];
		foreach ($days as $row) {
			$net = $row['cash_in'] - $row['trip_costs'] - $row['office'];
			$balance += $net + $row['deposited'];
			$list[] = [
				'date' => $row['date'],
				'label' => $row['label'],
				'cash_in' => tessera_accounts_reports_money($row['cash_in']),
				'trip_costs' => tessera_accounts_reports_money($row['trip_costs']),
				'office' => tessera_accounts_reports_money($row['office']),
				'deposited' => tessera_accounts_reports_money($row['deposited']),
				'net' => tessera_accounts_reports_money($net),
				'balance' => tessera_accounts_reports_money($balance),
			];
			$cashIn += $row['cash_in'];
			$tripCosts += $row['trip_costs'];
			$office += $row['office'];
			$deposited += $row['deposited'];
		}
		$cashOut = $tripCosts + $office;
		$net = $cashIn - $cashOut;
		$closing = $opening + $net + $deposited;
		$statement = [
			['type' => 'subtotal', 'label' => 'Opening cash balance', 'amount' => tessera_accounts_reports_money($opening)],
			['type' => 'heading', 'label' => 'Cash received', 'amount' => null],
			['type' => 'line', 'label' => 'Trip income', 'amount' => tessera_accounts_reports_money($cashIn)],
			['type' => 'subtotal', 'label' => 'Total cash received', 'amount' => tessera_accounts_reports_money($cashIn)],
			['type' => 'heading', 'label' => 'Cash paid out', 'amount' => null],
			['type' => 'line', 'label' => 'Trip costs paid', 'amount' => tessera_accounts_reports_money($tripCosts)],
			['type' => 'line', 'label' => 'Office and daily expenses', 'amount' => tessera_accounts_reports_money($office)],
			['type' => 'subtotal', 'label' => 'Total cash paid out', 'amount' => tessera_accounts_reports_money($cashOut)],
			['type' => 'subtotal', 'label' => 'Net operating cash flow', 'amount' => tessera_accounts_reports_money($net)],
			['type' => 'line', 'label' => 'Cash book deposits', 'amount' => tessera_accounts_reports_money($deposited)],
			['type' => 'total', 'label' => 'Closing cash balance', 'amount' => tessera_accounts_reports_money($closing)],
		];
		return [
			'applied' => $applied,
			'statement' => $statement,
			'days' => $list,
			'deposits' => $deposits,
			'summary' => [
				'opening' => tessera_accounts_reports_money($opening),
				'cash_in' => tessera_accounts_reports_money($cashIn),
				'cash_out' => tessera_accounts_reports_money($cashOut),
				'net' => tessera_accounts_reports_money($net),
				'deposited' => tessera_accounts_reports_money($deposited),
				'closing' => tessera_accounts_reports_money($closing),
			],
		];
	}
}

if (!function_exists('tessera_accounts_reports_revenue')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_revenue(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$sql = "SELECT route_name, IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
				" . tessera_accounts_reports_income_sql() . "
			FROM vehicle_account
			WHERE {$where} AND status='Income'
			GROUP BY route_name, currency";
		$tripCounts = [];
		foreach (tessera_accounts_reports_rows(
			$db,
			"SELECT route_name, COUNT(DISTINCT CONCAT(bus,'|',trip_date)) AS trips
			 FROM vehicle_account WHERE {$where} AND status='Income'
			 GROUP BY route_name"
		) as $row) {
			$route = trim((string) ($row['route_name'] ?? ''));
			if ($route === '') {
				$route = 'Unassigned route';
			}
			$tripCounts[$route] = (int) ($row['trips'] ?? 0);
		}
		$folded = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$route = trim((string) ($row['route_name'] ?? ''));
			if ($route === '') {
				$route = 'Unassigned route';
			}
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			if (!isset($folded[$route])) {
				$folded[$route] = [
					'route' => $route,
					'ticket' => 0.0,
					'road' => 0.0,
					'cargo_paid' => 0.0,
					'cargo_to_pay' => 0.0,
					'trips' => $tripCounts[$route] ?? 0,
				];
			}
			$folded[$route]['ticket'] += tessera_accounts_reports_num($row['ticket'] ?? 0) * $rate;
			$folded[$route]['road'] += tessera_accounts_reports_num($row['road'] ?? 0) * $rate;
			$folded[$route]['cargo_paid'] += tessera_accounts_reports_num($row['cargo_paid'] ?? 0) * $rate;
			$folded[$route]['cargo_to_pay'] += tessera_accounts_reports_num($row['cargo_to_pay'] ?? 0) * $rate;
		}
		$routes = [];
		$ticket = $road = $cargoPaid = $cargoToPay = 0.0;
		$trips = 0;
		foreach ($folded as $row) {
			$total = $row['ticket'] + $row['road'] + $row['cargo_paid'] + $row['cargo_to_pay'];
			$routes[] = [
				'route' => $row['route'],
				'trips' => $row['trips'],
				'ticket' => tessera_accounts_reports_money($row['ticket']),
				'road' => tessera_accounts_reports_money($row['road']),
				'cargo_paid' => tessera_accounts_reports_money($row['cargo_paid']),
				'cargo_to_pay' => tessera_accounts_reports_money($row['cargo_to_pay']),
				'total' => tessera_accounts_reports_money($total),
				'per_trip' => $row['trips'] > 0 ? tessera_accounts_reports_money($total / $row['trips']) : 0.0,
			];
			$ticket += $row['ticket'];
			$road += $row['road'];
			$cargoPaid += $row['cargo_paid'];
			$cargoToPay += $row['cargo_to_pay'];
			$trips += $row['trips'];
		}
		usort($routes, static function ($a, $b) {
			return $b['total'] <=> $a['total'];
		});
		$grand = $ticket + $road + $cargoPaid + $cargoToPay;
		foreach ($routes as $i => $row) {
			$routes[$i]['share'] = tessera_accounts_pnl_margin((float) $row['total'], tessera_accounts_reports_money($grand));
		}
		$mix = [
			['source' => 'Ticket sales', 'amount' => tessera_accounts_reports_money($ticket)],
			['source' => 'Road collection', 'amount' => tessera_accounts_reports_money($road)],
			['source' => 'Cargo — prepaid', 'amount' => tessera_accounts_reports_money($cargoPaid)],
			['source' => 'Cargo — to be paid', 'amount' => tessera_accounts_reports_money($cargoToPay)],
		];
		$grandRounded = tessera_accounts_reports_money($grand);
		foreach ($mix as $i => $row) {
			$mix[$i]['share'] = tessera_accounts_pnl_margin((float) $row['amount'], $grandRounded);
		}
		return [
			'applied' => $applied,
			'mix' => $mix,
			'routes' => $routes,
			'summary' => [
				'total' => $grandRounded,
				'ticket' => tessera_accounts_reports_money($ticket),
				'road' => tessera_accounts_reports_money($road),
				'cargo_paid' => tessera_accounts_reports_money($cargoPaid),
				'cargo_to_pay' => tessera_accounts_reports_money($cargoToPay),
				'cargo' => tessera_accounts_reports_money($cargoPaid + $cargoToPay),
				'trips' => $trips,
				'per_trip' => $trips > 0 ? tessera_accounts_reports_money($grand / $trips) : 0.0,
			],
		];
	}
}

if (!function_exists('tessera_accounts_reports_cost_buckets')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array{costs:array<string,float>,office:array<string,float>,trips:int}
	 */
	function tessera_accounts_reports_cost_buckets(mysqli $db, string $username, array $filters, string $from, string $to): array
	{
		$local = $filters;
		$local['date_from'] = $from;
		$local['date_to'] = $to;
		$target = tessera_accounts_reports_target_currency($db, $filters);
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $local);
		$costs = [];
		$sql = "SELECT TRIM(category) AS category_name,
				IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
				SUM(expenses) AS amount
			FROM vehicle_account
			WHERE {$where} AND status='Expenses' AND affect='Paid' AND expenses<>0
			GROUP BY category_name, currency";
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$name = trim((string) ($row['category_name'] ?? ''));
			if ($name === '') {
				$name = 'Uncategorised';
			}
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			$costs[$name] = ($costs[$name] ?? 0.0) + tessera_accounts_reports_num($row['amount'] ?? 0) * $rate;
		}
		$office = [];
		$officeWhere = tessera_accounts_reports_office_where($db, $username, $filters, $from, $to);
		foreach (tessera_accounts_reports_rows(
			$db,
			"SELECT IFNULL(NULLIF(name,''), IFNULL(NULLIF(detail,''), 'Office')) AS office_name, SUM(amount) AS amt
			 FROM office_expenses WHERE {$officeWhere} GROUP BY office_name"
		) as $row) {
			$name = trim((string) ($row['office_name'] ?? 'Office'));
			$amt = tessera_accounts_reports_num($row['amt'] ?? 0);
			if ($name === '' || $amt == 0.0) {
				continue;
			}
			$office[$name] = ($office[$name] ?? 0.0) + $amt;
		}
		$trips = 0;
		$tq = tessera_accounts_reports_rows(
			$db,
			"SELECT COUNT(DISTINCT CONCAT(bus,'|',trip_date)) AS c
			 FROM vehicle_account WHERE {$where} AND status='Expenses' AND affect='Paid' AND expenses<>0"
		);
		if ($tq !== []) {
			$trips = (int) ($tq[0]['c'] ?? 0);
		}
		return ['costs' => $costs, 'office' => $office, 'trips' => $trips];
	}
}

if (!function_exists('tessera_accounts_reports_expense')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_expense(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		[$from, $to] = [$applied['date_from'], $applied['date_to']];
		[$prevFrom, $prevTo] = tessera_accounts_pnl_previous_range($from, $to);
		$now = tessera_accounts_reports_cost_buckets($db, $username, $filters, $from, $to);
		$before = tessera_accounts_reports_cost_buckets($db, $username, $filters, $prevFrom, $prevTo);
		$totalNow = array_sum($now['costs']);
		$totalBefore = array_sum($before['costs']);
		$names = array_unique(array_merge(array_keys($now['costs']), array_keys($before['costs'])));
		$rows = [];
		foreach ($names as $name) {
			$amount = (float) ($now['costs'][$name] ?? 0);
			$prior = (float) ($before['costs'][$name] ?? 0);
			$change = $amount - $prior;
			$rows[] = [
				'category' => $name,
				'now' => tessera_accounts_reports_money($amount),
				'before' => tessera_accounts_reports_money($prior),
				'change' => tessera_accounts_reports_money($change),
				'change_pct' => tessera_accounts_pnl_margin($change, $prior),
				'per_trip' => $now['trips'] > 0 ? tessera_accounts_reports_money($amount / $now['trips']) : 0.0,
				'share' => tessera_accounts_pnl_margin($amount, $totalNow),
			];
		}
		usort($rows, static function ($a, $b) {
			return $b['now'] <=> $a['now'];
		});
		$officeNames = array_unique(array_merge(array_keys($now['office']), array_keys($before['office'])));
		$officeRows = [];
		foreach ($officeNames as $name) {
			$amount = (float) ($now['office'][$name] ?? 0);
			$prior = (float) ($before['office'][$name] ?? 0);
			$officeRows[] = [
				'name' => $name,
				'now' => tessera_accounts_reports_money($amount),
				'before' => tessera_accounts_reports_money($prior),
				'change' => tessera_accounts_reports_money($amount - $prior),
			];
		}
		usort($officeRows, static function ($a, $b) {
			return $b['now'] <=> $a['now'];
		});
		$officeTotal = array_sum($now['office']);
		$largest = $rows[0]['category'] ?? '';
		$largestShare = (float) ($rows[0]['share'] ?? 0);
		return [
			'applied' => $applied,
			'previous' => [
				'from' => $prevFrom,
				'to' => $prevTo,
				'label' => tessera_accounts_pnl_period_label($prevFrom, $prevTo),
			],
			'rows' => $rows,
			'office' => $officeRows,
			'summary' => [
				'trip_costs' => tessera_accounts_reports_money($totalNow),
				'trip_costs_before' => tessera_accounts_reports_money($totalBefore),
				'change' => tessera_accounts_reports_money($totalNow - $totalBefore),
				'change_pct' => tessera_accounts_pnl_margin($totalNow - $totalBefore, $totalBefore),
				'office' => tessera_accounts_reports_money($officeTotal),
				'trips' => $now['trips'],
				'per_trip' => $now['trips'] > 0 ? tessera_accounts_reports_money($totalNow / $now['trips']) : 0.0,
				'largest' => $largest,
				'largest_share' => $largestShare,
			],
		];
	}
}

if (!function_exists('tessera_accounts_reports_aging_bucket')) {
	/**
	 * @return array{0:string,1:string}
	 */
	function tessera_accounts_reports_aging_bucket(int $days): array
	{
		if ($days <= 30) {
			return ['0 – 30 days', 'paid'];
		}
		if ($days <= 60) {
			return ['31 – 60 days', 'warn'];
		}
		if ($days <= 90) {
			return ['61 – 90 days', 'warn'];
		}
		return ['Over 90 days', 'danger'];
	}
}

if (!function_exists('tessera_accounts_reports_payables')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_payables(mysqli $db, string $username, array $filters): array
	{
		$asOf = tessera_accounts_reports_date((string) ($filters['as_of'] ?? ''));
		if ($asOf === '') {
			$asOf = date('Y-m-d');
		}
		$local = $filters;
		$local['date_from'] = '2000-01-01';
		$local['date_to'] = $asOf;
		$applied = tessera_accounts_reports_applied($db, $local);
		$applied['as_of'] = $asOf;
		$applied['date_from'] = '';
		$applied['period_label'] = 'As of ' . date('j M Y', strtotime($asOf));
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $local);
		$sql = "SELECT id, bus, trip_date, route_name, category, name, expenses, currency
			FROM vehicle_account
			WHERE {$where} AND status='Expenses' AND affect='To be Paid' AND expenses>0
			ORDER BY trip_date ASC, id ASC";
		$items = [];
		$aging = [
			'0 – 30 days' => ['bucket' => '0 – 30 days', 'items' => 0, 'amount' => 0.0, 'badge' => 'paid'],
			'31 – 60 days' => ['bucket' => '31 – 60 days', 'items' => 0, 'amount' => 0.0, 'badge' => 'warn'],
			'61 – 90 days' => ['bucket' => '61 – 90 days', 'items' => 0, 'amount' => 0.0, 'badge' => 'warn'],
			'Over 90 days' => ['bucket' => 'Over 90 days', 'items' => 0, 'amount' => 0.0, 'badge' => 'danger'],
		];
		$asTs = strtotime($asOf);
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$trip = tessera_accounts_reports_date((string) ($row['trip_date'] ?? ''));
			$tripTs = $trip !== '' ? strtotime($trip) : false;
			if (!$tripTs || !$asTs) {
				continue;
			}
			$days = (int) floor(($asTs - $tripTs) / 86400);
			if ($days < 0) {
				continue;
			}
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			$amount = tessera_accounts_reports_money(tessera_accounts_reports_num($row['expenses'] ?? 0) * $rate);
			[$bucket, $badge] = tessera_accounts_reports_aging_bucket($days);
			$aging[$bucket]['items']++;
			$aging[$bucket]['amount'] += $amount;
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'bus' => (string) ($row['bus'] ?? ''),
				'date' => $trip,
				'date_label' => date('j M Y', $tripTs),
				'route' => (string) ($row['route_name'] ?? ''),
				'category' => trim((string) ($row['category'] ?? '')),
				'name' => trim((string) ($row['name'] ?? '')),
				'amount' => $amount,
				'days' => $days,
				'bucket' => $bucket,
				'badge' => $badge,
			];
		}
		usort($items, static function ($a, $b) {
			return $b['days'] <=> $a['days'];
		});
		$total = 0.0;
		foreach ($aging as $name => $row) {
			$total += $row['amount'];
		}
		foreach ($aging as $name => $row) {
			$aging[$name]['share'] = tessera_accounts_pnl_margin($row['amount'], $total);
		}
		$current = $aging['0 – 30 days']['amount'];
		$oldest = $items[0]['days'] ?? 0;
		return [
			'applied' => $applied,
			'items' => $items,
			'aging' => array_values($aging),
			'summary' => [
				'total' => $total,
				'items' => count($items),
				'overdue' => $total - $current,
				'oldest_days' => $oldest,
				'current' => $current,
			],
		];
	}
}

if (!function_exists('tessera_accounts_reports_currency')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_reports_currency(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$primary = tessera_accounts_reports_primary($db);
		$base = $primary['name'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$sql = "SELECT IFNULL(NULLIF(currency,''),'{$db->real_escape_string($base)}') AS code,
				SUM(CASE WHEN status='Income' THEN income ELSE 0 END) AS income,
				SUM(CASE WHEN status='Expenses' AND affect='Paid' THEN expenses ELSE 0 END) AS expenses,
				COUNT(*) AS entries
			FROM vehicle_account WHERE {$where}
			GROUP BY code
			ORDER BY code ASC";
		$rows = [];
		$netBase = 0.0;
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$code = trim((string) ($row['code'] ?? ''));
			if ($code === '' || strcasecmp($code, 'Choose Currency') === 0) {
				$code = $base;
			}
			$income = tessera_accounts_reports_num($row['income'] ?? 0);
			$expenses = tessera_accounts_reports_num($row['expenses'] ?? 0);
			$net = $income - $expenses;
			$rate = tessera_accounts_reports_rate($code, $base, $fx);
			$converted = $net * $rate;
			$netBase += $converted;
			$rateText = strcasecmp($code, $base) === 0
				? 'Base'
				: '1 ' . $code . ' = ' . number_format($rate, $rate >= 100 ? 0 : 4) . ' ' . $base;
			$rows[] = [
				'code' => $code,
				'entries' => (int) ($row['entries'] ?? 0),
				'income' => tessera_accounts_reports_money($income),
				'expenses' => tessera_accounts_reports_money($expenses),
				'net' => tessera_accounts_reports_money($net),
				'rate' => $rate,
				'rate_text' => $rateText,
				'net_base' => tessera_accounts_reports_money($converted),
			];
		}
		usort($rows, static function ($a, $b) {
			return abs($b['net_base']) <=> abs($a['net_base']);
		});
		return [
			'applied' => $applied,
			'base' => $base,
			'rows' => $rows,
			'summary' => [
				'net_base' => tessera_accounts_reports_money($netBase),
				'currencies' => count($rows),
				'primary' => $base,
			],
		];
	}
}
