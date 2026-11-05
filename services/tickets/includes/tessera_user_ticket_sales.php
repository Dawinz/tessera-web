<?php
/**
 * User / My ticket sales helpers (T-P1-03 / T-P1-04).
 * Classic: user_ticket_sales_summary.php, my_ticket_sales_summary.php
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_ticket_sales_summary.php';
require_once __DIR__ . '/tessera_agent_daily_charge.php';

if (!function_exists('tessera_user_ticket_sales_payload')) {
	/**
	 * @param 'booker'|'issuer' $personField
	 * @param 'trip_date'|'book_date' $dateField
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array<string,mixed>,booker:string,route_id:int,date_from:string,date_to:string}
	 */
	function tessera_user_ticket_sales_payload(
		mysqli $db,
		string $sessionUser,
		string $booker,
		int $routeId,
		string $dateFrom,
		string $dateTo,
		string $q = '',
		int $page = 1,
		int $perPage = 100,
		string $personField = 'booker',
		string $dateField = 'trip_date',
		bool $selfOnly = false
	): array {
		if ($selfOnly || !tessera_ticket_sales_is_admin($db, $sessionUser)) {
			$booker = $sessionUser;
		} else {
			$booker = trim($booker) !== '' ? trim($booker) : $sessionUser;
		}

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
			$dateFrom = date('Y-m-d');
		}
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
			$dateTo = date('Y-m-d');
		}
		if ($dateFrom > $dateTo) {
			$tmp = $dateFrom;
			$dateFrom = $dateTo;
			$dateTo = $tmp;
		}

		$personField = $personField === 'issuer' ? 'issuer' : 'booker';
		$dateField = $dateField === 'book_date' ? 'book_date' : 'trip_date';

		$allowed = tessera_ticket_sales_routes($db, $sessionUser);
		$allowedIds = array_map(static fn(array $r): int => (int) $r['id'], $allowed);
		if ($routeId > 0 && $allowedIds !== [] && !in_array($routeId, $allowedIds, true) && !tessera_ticket_sales_is_admin($db, $sessionUser)) {
			$routeId = 0;
		}

		$page = max(1, $page);
		$perPage = max(1, min(200, $perPage));
		$offset = ($page - 1) * $perPage;

		$tenant = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1';
		$bookerEsc = mysqli_real_escape_string($db, $booker);
		$fromEsc = mysqli_real_escape_string($db, $dateFrom);
		$toEsc = mysqli_real_escape_string($db, $dateTo);

		$where = "{$tenant} AND {$personField}='{$bookerEsc}'
			AND {$dateField} >= '{$fromEsc}' AND {$dateField} <= '{$toEsc}'
			AND cost != 0 AND (delete_book='' OR delete_book IS NULL)";

		if ($routeId > 0) {
			$where .= ' AND (route_id=' . (int) $routeId . ' OR sub_route_id=' . (int) $routeId . ')';
		} elseif ($allowedIds !== [] && !tessera_ticket_sales_is_admin($db, $sessionUser)) {
			$where .= ' AND route_id IN (' . implode(',', $allowedIds) . ')';
		}

		$q = trim($q);
		if ($q !== '') {
			$like = mysqli_real_escape_string($db, '%' . $q . '%');
			$where .= " AND (bus LIKE '{$like}' OR start_location LIKE '{$like}' OR ending_location LIKE '{$like}'
				OR firstname LIKE '{$like}' OR lastname LIKE '{$like}' OR issuer LIKE '{$like}' OR booker LIKE '{$like}'
				OR seat_no LIKE '{$like}' OR CAST(id AS CHAR) LIKE '{$like}')";
		}

		$total = 0;
		$sumCost = 0.0;
		$cq = mysqli_query($db, "SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS s FROM booking WHERE {$where}");
		if ($cq && ($cr = mysqli_fetch_assoc($cq))) {
			$total = (int) ($cr['c'] ?? 0);
			$sumCost = (float) ($cr['s'] ?? 0);
		}

		$items = [];
		$lq = mysqli_query(
			$db,
			"SELECT id, bus, seat_no, firstname, lastname, cost, book_date, book_time, trip_date,
			        start_location, ending_location, start_route, end_route, issuer, booker, route_id
			 FROM booking WHERE {$where}
			 ORDER BY {$dateField} DESC, id DESC
			 LIMIT {$perPage} OFFSET {$offset}"
		);
		while ($lq && ($row = mysqli_fetch_assoc($lq))) {
			$start = (string) ($row['start_location'] ?? $row['start_route'] ?? '');
			$end = (string) ($row['ending_location'] ?? $row['end_route'] ?? '');
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'bus' => (string) ($row['bus'] ?? ''),
				'seat_no' => (string) ($row['seat_no'] ?? ''),
				'fullname' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['lastname'] ?? '')),
				'cost' => (float) ($row['cost'] ?? 0),
				'book_date' => (string) ($row['book_date'] ?? ''),
				'book_time' => (string) ($row['book_time'] ?? ''),
				'trip_date' => (string) ($row['trip_date'] ?? ''),
				'start_location' => $start,
				'ending_location' => $end,
				'route_label' => trim($start . ($end !== '' ? ' → ' . $end : '')),
				'issuer' => (string) ($row['issuer'] ?? ''),
				'booker' => (string) ($row['booker'] ?? ''),
				'route_id' => (int) ($row['route_id'] ?? 0),
			];
		}

		return [
			'items' => $items,
			'total' => $total,
			'summary' => [
				'tickets' => $total,
				'total_sales' => $sumCost,
			],
			'booker' => $booker,
			'route_id' => $routeId,
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}
}
