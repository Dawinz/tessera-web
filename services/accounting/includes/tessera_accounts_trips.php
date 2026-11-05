<?php
/**
 * Shared Accounts trip profitability helpers (A-P0-05).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_accounts_manifests.php';

if (!function_exists('tessera_accounts_trips_margin')) {
	function tessera_accounts_trips_margin(float $net, float $revenue): float
	{
		if ($revenue <= 0) {
			return $net < 0 ? -100.0 : 0.0;
		}
		return round(($net / $revenue) * 1000) / 10;
	}
}

if (!function_exists('tessera_accounts_trips_status')) {
	/**
	 * @return array{label:string,badge:string}
	 */
	function tessera_accounts_trips_status(float $net, float $margin): array
	{
		if ($net < 0) {
			return ['label' => 'Loss', 'badge' => 'danger'];
		}
		if ($margin < 30) {
			return ['label' => 'Low margin', 'badge' => 'warn'];
		}
		return ['label' => 'Profitable', 'badge' => 'paid'];
	}
}

if (!function_exists('tessera_accounts_trips_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_accounts_trips_row_to_item(array $row): array
	{
		$ticket = tessera_accounts_manifests_num($row['ticket'] ?? 0);
		$cargo = tessera_accounts_manifests_num($row['amount'] ?? 0);
		$income = tessera_accounts_manifests_num($row['income'] ?? 0);
		$expenses = tessera_accounts_manifests_num($row['expenses'] ?? 0);
		/* Prefer stored income; fall back to ticket+cargo when income not yet composed. */
		$revenue = $income > 0 ? $income : ($ticket + $cargo);
		$net = $revenue - $expenses;
		$margin = tessera_accounts_trips_margin($net, $revenue);
		$status = tessera_accounts_trips_status($net, $margin);
		$tripDate = (string) ($row['trip_date'] ?? '');
		$ts = $tripDate !== '' ? strtotime($tripDate) : false;

		$qs = http_build_query([
			'route_name' => (string) ($row['route_name'] ?? ''),
			'bus' => (string) ($row['bus'] ?? ''),
			'trip_date' => $tripDate,
			'checked' => base64_encode((string) ($row['checked'] ?? '')),
			'main_page' => 'manifest.php',
		]);

		return [
			'id' => (int) ($row['id'] ?? 0),
			'code' => (string) ($row['code'] ?? ''),
			'date' => $tripDate,
			'date_label' => $ts ? date('j M Y', $ts) : $tripDate,
			'route' => (string) ($row['route_name'] ?? ''),
			'vehicle' => (string) ($row['bus'] ?? ''),
			'ticket' => $ticket,
			'cargo' => $cargo,
			'revenue' => $revenue,
			'expenses' => $expenses,
			'net' => $net,
			'margin' => $margin,
			'status_label' => $status['label'],
			'status_badge' => $status['badge'],
			'analysed' => (string) ($row['analysed'] ?? ''),
			'compose_url' => '/operator/app.php?module=accounts&page=compose_vehicle.php&' . $qs,
		];
	}
}

if (!function_exists('tessera_accounts_trips_list')) {
	/**
	 * @param array{q?:string,route?:string,vehicle?:string,date_from?:string,date_to?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array{count:int,revenue:float,expenses:float,net:float,margin:float,review:int}}
	 */
	function tessera_accounts_trips_list(
		mysqli $db,
		string $username,
		array $filters,
		int $page = 1,
		int $perPage = 25
	): array {
		tessera_accounts_manifests_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$parts = [$tenant];
		$q = trim((string) ($filters['q'] ?? ''));
		$route = trim((string) ($filters['route'] ?? ''));
		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		$dateFrom = trim((string) ($filters['date_from'] ?? ''));
		$dateTo = trim((string) ($filters['date_to'] ?? ''));

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
		if ($vehicle !== '') {
			$parts[] = "bus='" . $db->real_escape_string($vehicle) . "'";
		}
		if ($dateFrom !== '') {
			$parts[] = "trip_date>='" . $db->real_escape_string($dateFrom) . "'";
		}
		if ($dateTo !== '') {
			$parts[] = "trip_date<='" . $db->real_escape_string($dateTo) . "'";
		}
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(code LIKE '{$like}' OR bus LIKE '{$like}' OR route_name LIKE '{$like}')";
		}
		$where = implode(' AND ', $parts);

		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM manifest WHERE {$where}");
		if ($cq && ($crow = $cq->fetch_assoc())) {
			$total = (int) ($crow['c'] ?? 0);
		}

		$items = [];
		$sql = "SELECT id, trip_date, code, analysed, route_name, amount, expenses, income, ticket, bus, checked
			FROM manifest WHERE {$where} ORDER BY trip_date DESC, id DESC LIMIT {$perPage} OFFSET {$offset}";
		$rq = $db->query($sql);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$items[] = tessera_accounts_trips_row_to_item($row);
			}
		}

		/* Summary over full filtered set (not just page). */
		$revenue = 0.0;
		$expenses = 0.0;
		$review = 0;
		$sumQ = $db->query(
			"SELECT income, expenses, ticket, amount FROM manifest WHERE {$where}"
		);
		if ($sumQ) {
			while ($row = $sumQ->fetch_assoc()) {
				$ticket = tessera_accounts_manifests_num($row['ticket'] ?? 0);
				$cargo = tessera_accounts_manifests_num($row['amount'] ?? 0);
				$income = tessera_accounts_manifests_num($row['income'] ?? 0);
				$exp = tessera_accounts_manifests_num($row['expenses'] ?? 0);
				$rev = $income > 0 ? $income : ($ticket + $cargo);
				$net = $rev - $exp;
				$margin = tessera_accounts_trips_margin($net, $rev);
				$revenue += $rev;
				$expenses += $exp;
				if ($net < 0 || $margin < 30) {
					$review++;
				}
			}
		}
		$net = $revenue - $expenses;

		return [
			'items' => $items,
			'total' => $total,
			'summary' => [
				'count' => $total,
				'revenue' => $revenue,
				'expenses' => $expenses,
				'net' => $net,
				'margin' => tessera_accounts_trips_margin($net, $revenue),
				'review' => $review,
			],
		];
	}
}
