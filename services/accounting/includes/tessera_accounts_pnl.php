<?php
/**
 * Shared Accounts profit & loss helpers (A-P0-06).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_accounts_manifests.php';

if (!function_exists('tessera_accounts_pnl_num')) {
	function tessera_accounts_pnl_num($value): float
	{
		return tessera_accounts_manifests_num($value);
	}
}

if (!function_exists('tessera_accounts_pnl_margin')) {
	function tessera_accounts_pnl_margin(float $part, float $whole): float
	{
		if ($whole == 0.0) {
			return 0.0;
		}
		return round(($part / $whole) * 1000) / 10;
	}
}

if (!function_exists('tessera_accounts_pnl_period_label')) {
	function tessera_accounts_pnl_period_label(string $from, string $to): string
	{
		$tsFrom = strtotime($from);
		$tsTo = strtotime($to);
		if (!$tsFrom || !$tsTo) {
			return trim($from . ' – ' . $to);
		}
		if (date('Y-m', $tsFrom) === date('Y-m', $tsTo)
			&& date('j', $tsFrom) === '1'
			&& date('j', $tsTo) === date('t', $tsTo)
		) {
			return date('M Y', $tsFrom);
		}
		return date('j M Y', $tsFrom) . ' – ' . date('j M Y', $tsTo);
	}
}

if (!function_exists('tessera_accounts_pnl_previous_range')) {
	/**
	 * @return array{0:string,1:string} previous from/to of equal length
	 */
	function tessera_accounts_pnl_previous_range(string $from, string $to): array
	{
		$tsFrom = strtotime($from);
		$tsTo = strtotime($to);
		if (!$tsFrom || !$tsTo) {
			return [$from, $to];
		}
		$days = (int) floor(($tsTo - $tsFrom) / 86400) + 1;
		$prevTo = date('Y-m-d', strtotime('-1 day', $tsFrom));
		$prevFrom = date('Y-m-d', strtotime('-' . $days . ' day', $tsFrom));
		return [$prevFrom, $prevTo];
	}
}

if (!function_exists('tessera_accounts_pnl_figures')) {
	/**
	 * @return array{
	 *   ticket:float,cargo:float,cargo_paid:float,cargo_to_pay:float,revenue:float,
	 *   direct_costs:array<string,float>,direct:float,gross:float,
	 *   overheads:array<string,float>,overheads_total:float,net:float,trips:int
	 * }
	 */
	function tessera_accounts_pnl_figures(
		mysqli $db,
		string $username,
		string $dateFrom,
		string $dateTo,
		string $route = ''
	): array {
		tessera_accounts_manifests_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		$parts = [
			$tenant,
			"trip_date>='" . $db->real_escape_string($dateFrom) . "'",
			"trip_date<='" . $db->real_escape_string($dateTo) . "'",
		];
		$route = trim($route);
		if ($route !== '') {
			$parts[] = "route_name='" . $db->real_escape_string($route) . "'";
		} else {
			$assigned = tessera_accounts_manifests_assigned_routes($db, $username);
			if ($assigned !== []) {
				$in = array_map(static function ($r) use ($db) {
					return "'" . $db->real_escape_string($r) . "'";
				}, $assigned);
				$parts[] = 'route_name IN (' . implode(',', $in) . ')';
			}
		}
		$where = implode(' AND ', $parts);

		$ticket = 0.0;
		$cargoPaid = 0.0;
		$cargoToPay = 0.0;
		$incomeSum = 0.0;
		$expensesSum = 0.0;
		$trips = 0;
		$mq = $db->query(
			"SELECT COALESCE(SUM(ticket),0) AS ticket, COALESCE(SUM(paid),0) AS paid,
				COALESCE(SUM(to_be),0) AS to_be, COALESCE(SUM(amount),0) AS amount,
				COALESCE(SUM(income),0) AS income, COALESCE(SUM(expenses),0) AS expenses,
				COUNT(*) AS trips
			 FROM manifest WHERE {$where}"
		);
		if ($mq && ($row = $mq->fetch_assoc())) {
			$ticket = tessera_accounts_pnl_num($row['ticket'] ?? 0);
			$cargoPaid = tessera_accounts_pnl_num($row['paid'] ?? 0);
			$cargoToPay = tessera_accounts_pnl_num($row['to_be'] ?? 0);
			$cargoAmount = tessera_accounts_pnl_num($row['amount'] ?? 0);
			if ($cargoPaid + $cargoToPay <= 0 && $cargoAmount > 0) {
				$cargoPaid = $cargoAmount;
			}
			$incomeSum = tessera_accounts_pnl_num($row['income'] ?? 0);
			$expensesSum = tessera_accounts_pnl_num($row['expenses'] ?? 0);
			$trips = (int) ($row['trips'] ?? 0);
		}

		$revenueParts = $ticket + $cargoPaid + $cargoToPay;
		$revenue = $incomeSum > 0 ? $incomeSum : $revenueParts;
		/* If income exists but ticket/cargo breakdown is empty, keep income as revenue. */
		if ($revenue <= 0 && $revenueParts > 0) {
			$revenue = $revenueParts;
		}

		$directCosts = [];
		$mWhere = str_replace(
			['trip_date', 'route_name', 'tenant_id'],
			['m.trip_date', 'm.route_name', 'm.tenant_id'],
			$where
		);
		/* Prefer categorized vehicle_account lines for trips in the manifest filter. */
		$vq = $db->query(
			"SELECT v.category, SUM(v.expenses) AS expenses
			 FROM vehicle_account v
			 INNER JOIN manifest m ON m.bus=v.bus AND m.trip_date=v.trip_date AND m.route_name=v.route_name
			 WHERE {$mWhere} AND v.expenses>0
			 GROUP BY v.category
			 ORDER BY expenses DESC"
		);
		if ($vq) {
			while ($row = $vq->fetch_assoc()) {
				$cat = trim((string) ($row['category'] ?? ''));
				$amt = tessera_accounts_pnl_num($row['expenses'] ?? 0);
				if ($cat === '' || $amt <= 0) {
					continue;
				}
				$directCosts[$cat] = ($directCosts[$cat] ?? 0.0) + $amt;
			}
		}
		$direct = array_sum($directCosts);
		if ($direct <= 0 && $expensesSum > 0) {
			$directCosts = ['Trip expenses' => $expensesSum];
			$direct = $expensesSum;
		} elseif ($direct > 0 && abs($direct - $expensesSum) > 1 && $expensesSum > $direct) {
			/* Manifest expenses can exceed categorized VA lines — add remainder. */
			$remainder = $expensesSum - $direct;
			if ($remainder > 0) {
				$directCosts['Other trip costs'] = $remainder;
				$direct = $expensesSum;
			}
		}

		$overheads = [];
		$officeParts = [
			"date>='" . $db->real_escape_string($dateFrom) . "'",
			"date<='" . $db->real_escape_string($dateTo) . "'",
		];
		if ($route !== '') {
			$officeParts[] = "route_name='" . $db->real_escape_string($route) . "'";
		}
		$officeWhere = implode(' AND ', $officeParts);
		$oq = $db->query(
			"SELECT IFNULL(NULLIF(name,''), IFNULL(NULLIF(detail,''), 'Office')) AS name, SUM(amount) AS amt
			 FROM office_expenses WHERE {$officeWhere}
			 GROUP BY name ORDER BY amt DESC"
		);
		if ($oq) {
			while ($row = $oq->fetch_assoc()) {
				$name = trim((string) ($row['name'] ?? 'Office'));
				$amt = tessera_accounts_pnl_num($row['amt'] ?? 0);
				if ($amt == 0.0) {
					continue;
				}
				$overheads[$name] = $amt;
			}
		}
		$overheadsTotal = array_sum($overheads);
		$gross = $revenue - $direct;
		$net = $gross - $overheadsTotal;

		return [
			'ticket' => $ticket,
			'cargo' => $cargoPaid + $cargoToPay,
			'cargo_paid' => $cargoPaid,
			'cargo_to_pay' => $cargoToPay,
			'revenue' => $revenue,
			'direct_costs' => $directCosts,
			'direct' => $direct,
			'gross' => $gross,
			'overheads' => $overheads,
			'overheads_total' => $overheadsTotal,
			'net' => $net,
			'trips' => $trips,
		];
	}
}

if (!function_exists('tessera_accounts_pnl_statement')) {
	/**
	 * @param array<string,mixed> $figures
	 * @return list<array{type:string,label:string,amount:?float}>
	 */
	function tessera_accounts_pnl_statement(array $figures): array
	{
		$rows = [
			['type' => 'heading', 'label' => 'Revenue', 'amount' => null],
			['type' => 'line', 'label' => 'Ticket sales', 'amount' => (float) $figures['ticket']],
			['type' => 'line', 'label' => 'Cargo revenue — prepaid', 'amount' => (float) $figures['cargo_paid']],
			['type' => 'line', 'label' => 'Cargo revenue — pay on delivery', 'amount' => (float) $figures['cargo_to_pay']],
			['type' => 'subtotal', 'label' => 'Total revenue', 'amount' => (float) $figures['revenue']],
			['type' => 'heading', 'label' => 'Direct trip costs', 'amount' => null],
		];
		foreach ($figures['direct_costs'] as $cat => $amount) {
			$rows[] = ['type' => 'line', 'label' => (string) $cat, 'amount' => (float) $amount];
		}
		if ($figures['direct_costs'] === []) {
			$rows[] = ['type' => 'line', 'label' => 'No trip costs', 'amount' => 0.0];
		}
		$rows[] = ['type' => 'subtotal', 'label' => 'Total direct trip costs', 'amount' => (float) $figures['direct']];
		$rows[] = ['type' => 'subtotal', 'label' => 'Gross profit', 'amount' => (float) $figures['gross']];
		$rows[] = ['type' => 'heading', 'label' => 'Operating expenses', 'amount' => null];
		foreach ($figures['overheads'] as $name => $amount) {
			$rows[] = ['type' => 'line', 'label' => (string) $name, 'amount' => (float) $amount];
		}
		if ($figures['overheads'] === []) {
			$rows[] = ['type' => 'line', 'label' => 'No office expenses', 'amount' => 0.0];
		}
		$rows[] = ['type' => 'subtotal', 'label' => 'Total operating expenses', 'amount' => (float) $figures['overheads_total']];
		$rows[] = ['type' => 'total', 'label' => 'Net profit', 'amount' => (float) $figures['net']];
		return $rows;
	}
}

if (!function_exists('tessera_accounts_pnl_report')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_accounts_pnl_report(
		mysqli $db,
		string $username,
		string $dateFrom,
		string $dateTo,
		string $route = ''
	): array {
		if ($dateFrom === '' || $dateTo === '') {
			$dateFrom = date('Y-m-01');
			$dateTo = date('Y-m-t');
		}
		[$prevFrom, $prevTo] = tessera_accounts_pnl_previous_range($dateFrom, $dateTo);
		$now = tessera_accounts_pnl_figures($db, $username, $dateFrom, $dateTo, $route);
		$before = tessera_accounts_pnl_figures($db, $username, $prevFrom, $prevTo, $route);

		$compare = [];
		foreach (
			[
				'revenue' => 'Total revenue',
				'direct' => 'Direct trip costs',
				'gross' => 'Gross profit',
				'overheads_total' => 'Operating expenses',
				'net' => 'Net profit',
			] as $key => $label
		) {
			$n = (float) ($now[$key] ?? 0);
			$b = (float) ($before[$key] ?? 0);
			$change = $n - $b;
			$compare[] = [
				'line' => $label,
				'now' => $n,
				'before' => $b,
				'change' => $change,
				'change_pct' => tessera_accounts_pnl_margin($change, $b),
			];
		}

		return [
			'period' => [
				'from' => $dateFrom,
				'to' => $dateTo,
				'label' => tessera_accounts_pnl_period_label($dateFrom, $dateTo),
			],
			'previous' => [
				'from' => $prevFrom,
				'to' => $prevTo,
				'label' => tessera_accounts_pnl_period_label($prevFrom, $prevTo),
			],
			'route' => $route,
			'figures' => $now,
			'statement' => tessera_accounts_pnl_statement($now),
			'compare' => $compare,
			'summary' => [
				'revenue' => $now['revenue'],
				'direct' => $now['direct'],
				'gross' => $now['gross'],
				'net' => $now['net'],
				'trips' => $now['trips'],
				'direct_pct' => tessera_accounts_pnl_margin($now['direct'], $now['revenue']),
				'gross_pct' => tessera_accounts_pnl_margin($now['gross'], $now['revenue']),
				'net_pct' => tessera_accounts_pnl_margin($now['net'], $now['revenue']),
			],
		];
	}
}
