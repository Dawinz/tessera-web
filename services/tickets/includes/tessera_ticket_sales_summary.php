<?php
/**
 * Ticket sales summary helpers (T-P1-01).
 * Classic: ticket_sales_summary.php + fetch_ticket_sales.php
 */
declare(strict_types=1);

if (!function_exists('tessera_ticket_sales_is_admin')) {
	function tessera_ticket_sales_is_admin(mysqli $db, string $username): bool
	{
		$userEsc = mysqli_real_escape_string($db, $username);
		$role = '';
		$rq = mysqli_query($db, "SELECT role FROM users WHERE username='$userEsc' LIMIT 1");
		if ($rq && ($rr = mysqli_fetch_assoc($rq))) {
			$role = (string) ($rr['role'] ?? '');
		}
		if (in_array($role, ['Super Administrator', 'Administrator'], true)) {
			return true;
		}
		if (function_exists('tessera_user_role_code')) {
			$code = tessera_user_role_code($db, $username);
			return in_array($code, ['owner', 'manager'], true);
		}
		return false;
	}
}

if (!function_exists('tessera_ticket_sales_routes')) {
	/**
	 * Routes the user may filter (assigned, or all for admin).
	 *
	 * @return list<array{id:int,name:string,start:string,end:string}>
	 */
	function tessera_ticket_sales_routes(mysqli $db, string $username): array
	{
		$tenant = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1';
		$out = [];
		$seen = [];

		if (tessera_ticket_sales_is_admin($db, $username)) {
			$q = mysqli_query(
				$db,
				"SELECT id, route_name, start_route, end_route FROM routes
				 WHERE {$tenant} AND (route_type='Main Route' OR route_type='' OR route_type IS NULL)
				 ORDER BY route_name ASC LIMIT 500"
			);
		} else {
			$userEsc = mysqli_real_escape_string($db, $username);
			$q = mysqli_query(
				$db,
				"SELECT r.id, r.route_name, r.start_route, r.end_route
				 FROM assigned_routes a
				 INNER JOIN routes r ON r.route_name = a.route_name
				 WHERE a.username='{$userEsc}' AND {$tenant}
				 GROUP BY r.id
				 ORDER BY r.route_name ASC
				 LIMIT 500"
			);
			if (!$q || mysqli_num_rows($q) === 0) {
				/* Fallback: assigned route_id when non-zero. */
				$q = mysqli_query(
					$db,
					"SELECT r.id, r.route_name, r.start_route, r.end_route
					 FROM assigned_routes a
					 INNER JOIN routes r ON (r.id = a.route_id OR r.route_id = a.route_id)
					 WHERE a.username='{$userEsc}' AND a.route_id != 0 AND {$tenant}
					 GROUP BY r.id
					 ORDER BY r.route_name ASC
					 LIMIT 500"
				);
			}
		}

		while ($q && ($row = mysqli_fetch_assoc($q))) {
			$id = (int) ($row['id'] ?? 0);
			if ($id <= 0 || isset($seen[$id])) {
				continue;
			}
			$seen[$id] = true;
			$name = trim((string) ($row['route_name'] ?? ''));
			if ($name === '') {
				$name = trim((string) ($row['start_route'] ?? '') . ' → ' . (string) ($row['end_route'] ?? ''));
			}
			$out[] = [
				'id' => $id,
				'name' => $name,
				'start' => (string) ($row['start_route'] ?? ''),
				'end' => (string) ($row['end_route'] ?? ''),
			];
		}

		return $out;
	}
}

if (!function_exists('tessera_ticket_sales_payload')) {
	/**
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array<string,mixed>,route_id:int,date_from:string,date_to:string}
	 */
	function tessera_ticket_sales_payload(
		mysqli $db,
		string $username,
		int $routeId,
		string $dateFrom,
		string $dateTo,
		string $q = '',
		int $page = 1,
		int $perPage = 100
	): array {
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

		$allowed = tessera_ticket_sales_routes($db, $username);
		$allowedIds = array_map(static fn(array $r): int => (int) $r['id'], $allowed);
		if ($routeId > 0 && $allowedIds !== [] && !in_array($routeId, $allowedIds, true)) {
			$routeId = 0;
		}
		if ($routeId <= 0 && $allowedIds !== []) {
			$routeId = $allowedIds[0];
		}

		$page = max(1, $page);
		$perPage = max(1, min(200, $perPage));
		$offset = ($page - 1) * $perPage;

		$tenant = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1';
		$fromEsc = mysqli_real_escape_string($db, $dateFrom);
		$toEsc = mysqli_real_escape_string($db, $dateTo);

		$where = "{$tenant} AND book_date >= '{$fromEsc}' AND book_date <= '{$toEsc}'
			AND cost != 0 AND (delete_book='' OR delete_book IS NULL)";
		if ($routeId > 0) {
			$where .= ' AND route_id=' . (int) $routeId;
		} elseif ($allowedIds !== []) {
			$where .= ' AND route_id IN (' . implode(',', $allowedIds) . ')';
		} else {
			return [
				'items' => [],
				'total' => 0,
				'summary' => ['tickets' => 0, 'total_sales' => 0.0],
				'route_id' => 0,
				'date_from' => $dateFrom,
				'date_to' => $dateTo,
			];
		}

		$q = trim($q);
		if ($q !== '') {
			$like = mysqli_real_escape_string($db, '%' . $q . '%');
			$where .= " AND (bus LIKE '{$like}' OR start_location LIKE '{$like}' OR ending_location LIKE '{$like}'
				OR firstname LIKE '{$like}' OR lastname LIKE '{$like}' OR issuer LIKE '{$like}'
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
			        start_location, ending_location, issuer, vehicle, route_id
			 FROM booking WHERE {$where}
			 ORDER BY book_date DESC, id DESC
			 LIMIT {$perPage} OFFSET {$offset}"
		);
		while ($lq && ($row = mysqli_fetch_assoc($lq))) {
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'bus' => (string) ($row['bus'] ?? $row['vehicle'] ?? ''),
				'seat_no' => (string) ($row['seat_no'] ?? ''),
				'fullname' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['lastname'] ?? '')),
				'cost' => (float) ($row['cost'] ?? 0),
				'book_date' => (string) ($row['book_date'] ?? ''),
				'book_time' => (string) ($row['book_time'] ?? ''),
				'trip_date' => (string) ($row['trip_date'] ?? ''),
				'start_location' => (string) ($row['start_location'] ?? ''),
				'ending_location' => (string) ($row['ending_location'] ?? ''),
				'issuer' => (string) ($row['issuer'] ?? ''),
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
			'route_id' => $routeId,
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}
}
