<?php
/**
 * Remaining Tickets P1 report helpers (T-P1-05…T-P1-10).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_ticket_sales_summary.php';
require_once __DIR__ . '/tessera_agent_daily_charge.php';

if (!function_exists('tessera_tickets_normalize_dates')) {
	/** @return array{0:string,1:string} */
	function tessera_tickets_normalize_dates(string $from, string $to): array
	{
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
			$from = date('Y-m-d');
		}
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
			$to = date('Y-m-d');
		}
		if ($from > $to) {
			return [$to, $from];
		}
		return [$from, $to];
	}
}

if (!function_exists('tessera_my_ticket_info_payload')) {
	/**
	 * Booking list / passenger info search.
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array<string,float|int>}
	 */
	function tessera_my_ticket_info_payload(
		mysqli $db,
		string $dateFrom,
		string $dateTo,
		string $q = '',
		int $page = 1,
		int $perPage = 100
	): array {
		[$dateFrom, $dateTo] = tessera_tickets_normalize_dates($dateFrom, $dateTo);
		$page = max(1, $page);
		$perPage = max(1, min(200, $perPage));
		$offset = ($page - 1) * $perPage;
		$tenant = tessera_sql_tenant_eq();
		$fromEsc = mysqli_real_escape_string($db, $dateFrom);
		$toEsc = mysqli_real_escape_string($db, $dateTo);
		$where = "{$tenant} AND trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}'
			AND (delete_book='' OR delete_book IS NULL)";
		$q = trim($q);
		if ($q !== '') {
			$like = mysqli_real_escape_string($db, '%' . $q . '%');
			$where .= " AND (bus LIKE '{$like}' OR seat_no LIKE '{$like}' OR firstname LIKE '{$like}'
				OR lastname LIKE '{$like}' OR phone_no LIKE '{$like}' OR CAST(id AS CHAR) LIKE '{$like}'
				OR issuer LIKE '{$like}')";
		}
		$total = 0;
		$sum = 0.0;
		$cq = mysqli_query($db, "SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS s FROM booking WHERE {$where}");
		if ($cq && ($cr = mysqli_fetch_assoc($cq))) {
			$total = (int) ($cr['c'] ?? 0);
			$sum = (float) ($cr['s'] ?? 0);
		}
		$items = [];
		$lq = mysqli_query(
			$db,
			"SELECT id, seat_no, firstname, lastname, cost, trip_date, book_date, book_time, bus, status,
			        phone_no, issuer, route_day_id, route_id, start_route, end_route, start_location, ending_location
			 FROM booking WHERE {$where} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}"
		);
		while ($lq && ($row = mysqli_fetch_assoc($lq))) {
			$id = (int) ($row['id'] ?? 0);
			$items[] = [
				'id' => $id,
				'seat_no' => (string) ($row['seat_no'] ?? ''),
				'fullname' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['lastname'] ?? '')),
				'cost' => (float) ($row['cost'] ?? 0),
				'trip_date' => (string) ($row['trip_date'] ?? ''),
				'book_date' => (string) ($row['book_date'] ?? ''),
				'book_time' => (string) ($row['book_time'] ?? ''),
				'bus' => (string) ($row['bus'] ?? ''),
				'status' => (string) ($row['status'] ?? ''),
				'phone' => (string) ($row['phone_no'] ?? ''),
				'issuer' => (string) ($row['issuer'] ?? ''),
				'route_label' => trim((string) (($row['start_route'] ?: $row['start_location']) ?? '') . ' → ' . (string) (($row['end_route'] ?: $row['ending_location']) ?? '')),
				'print_url' => '/tickets/print_multiple_ticket.php?id=' . $id,
			];
		}
		return [
			'items' => $items,
			'total' => $total,
			'summary' => ['tickets' => $total, 'total_sales' => $sum],
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}
}

if (!function_exists('tessera_deleted_tickets_payload')) {
	/** @return array{items:list<array<string,mixed>>,total:int} */
	function tessera_deleted_tickets_payload(mysqli $db, string $q = '', int $page = 1, int $perPage = 100): array
	{
		$page = max(1, $page);
		$perPage = max(1, min(200, $perPage));
		$offset = ($page - 1) * $perPage;
		$where = '1=1';
		$q = trim($q);
		if ($q !== '') {
			$like = mysqli_real_escape_string($db, '%' . $q . '%');
			$where .= " AND (seat_id LIKE '{$like}' OR seat_no LIKE '{$like}' OR username LIKE '{$like}'
				OR ticket_user LIKE '{$like}' OR ticket_deleter LIKE '{$like}' OR phone_no LIKE '{$like}'
				OR location LIKE '{$like}' OR trip_date LIKE '{$like}')";
		}
		$total = 0;
		$cq = mysqli_query($db, "SELECT COUNT(*) AS c FROM deleted WHERE {$where}");
		if ($cq && ($cr = mysqli_fetch_assoc($cq))) {
			$total = (int) ($cr['c'] ?? 0);
		}
		$items = [];
		$lq = mysqli_query(
			$db,
			"SELECT * FROM deleted WHERE {$where} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}"
		);
		while ($lq && ($row = mysqli_fetch_assoc($lq))) {
			$recovered = strtolower((string) ($row['status_deleted'] ?? '')) === 'recovered'
				|| stripos((string) ($row['button'] ?? ''), 'disabled') !== false;
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'seat_id' => (int) ($row['seat_id'] ?? 0),
				'seat_no' => (string) ($row['seat_no'] ?? ''),
				'username' => (string) ($row['username'] ?? ''),
				'ticket_user' => (string) ($row['ticket_user'] ?? ''),
				'ticket_deleter' => (string) ($row['ticket_deleter'] ?? ''),
				'delete_date' => (string) ($row['delete_date'] ?? ''),
				'trip_date' => (string) ($row['trip_date'] ?? ''),
				'phone_no' => (string) ($row['phone_no'] ?? ''),
				'location' => (string) ($row['location'] ?? ''),
				'reason' => (string) ($row['reason'] ?? ''),
				'recovered' => $recovered,
				'can_restore' => !$recovered,
			];
		}
		return ['items' => $items, 'total' => $total];
	}
}

if (!function_exists('tessera_deleted_tickets_restore')) {
	/** @return array{ok:bool,error?:string} */
	function tessera_deleted_tickets_restore(mysqli $db, string $username, int $deletedId): array
	{
		if ($deletedId <= 0) {
			return ['ok' => false, 'error' => 'Deleted row id required'];
		}
		if (!tessera_ticket_sales_is_admin($db, $username)) {
			/* Match classic: Super Administrator only — also allow Administrator via our helper. */
			$userEsc = mysqli_real_escape_string($db, $username);
			$rq = mysqli_query($db, "SELECT role FROM users WHERE username='{$userEsc}' LIMIT 1");
			$role = ($rq && ($rr = mysqli_fetch_assoc($rq))) ? (string) ($rr['role'] ?? '') : '';
			if ($role !== 'Super Administrator' && $role !== 'Administrator') {
				return ['ok' => false, 'error' => 'Only administrators can restore tickets'];
			}
		}
		$q = mysqli_query($db, "SELECT seat_id, status_deleted, button FROM deleted WHERE id={$deletedId} LIMIT 1");
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Deleted row not found'];
		}
		if (strtolower((string) ($row['status_deleted'] ?? '')) === 'recovered') {
			return ['ok' => false, 'error' => 'Already restored'];
		}
		$seatId = (int) ($row['seat_id'] ?? 0);
		if ($seatId <= 0) {
			return ['ok' => false, 'error' => 'Missing seat id'];
		}
		$tenant = tessera_sql_tenant_eq();
		$userEsc = mysqli_real_escape_string($db, $username);
		mysqli_query($db, "UPDATE booking SET delete_book='' WHERE {$tenant} AND id={$seatId} LIMIT 1");
		mysqli_query(
			$db,
			"UPDATE deleted SET button='disabled', status_deleted='recovered', recoverd_by='{$userEsc}' WHERE id={$deletedId} LIMIT 1"
		);
		return ['ok' => true];
	}
}

if (!function_exists('tessera_changed_seats_payload')) {
	/** @return array{items:list<array<string,mixed>>,total:int} */
	function tessera_changed_seats_payload(mysqli $db, string $q = '', int $page = 1, int $perPage = 100): array
	{
		$page = max(1, $page);
		$perPage = max(1, min(200, $perPage));
		$offset = ($page - 1) * $perPage;
		$where = '1=1';
		$q = trim($q);
		if ($q !== '') {
			$like = mysqli_real_escape_string($db, '%' . $q . '%');
			$where .= " AND (ticket_id LIKE '{$like}' OR username LIKE '{$like}'
				OR from_seat_number LIKE '{$like}' OR to_trip_number LIKE '{$like}'
				OR from_trip_date LIKE '{$like}' OR to_trip_date LIKE '{$like}')";
		}
		$total = 0;
		$cq = mysqli_query($db, "SELECT COUNT(*) AS c FROM changes WHERE {$where}");
		if ($cq && ($cr = mysqli_fetch_assoc($cq))) {
			$total = (int) ($cr['c'] ?? 0);
		}
		$items = [];
		$lq = mysqli_query(
			$db,
			"SELECT * FROM changes WHERE {$where} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}"
		);
		while ($lq && ($row = mysqli_fetch_assoc($lq))) {
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'ticket_id' => (string) ($row['ticket_id'] ?? ''),
				'username' => (string) ($row['username'] ?? ''),
				'from_trip_date' => (string) ($row['from_trip_date'] ?? ''),
				'to_trip_date' => (string) ($row['to_trip_date'] ?? ''),
				'from_seat' => (string) ($row['from_seat_number'] ?? ''),
				'to_seat' => (string) ($row['to_trip_number'] ?? ''),
				'changed_time' => (string) ($row['changed_time'] ?? ''),
				'summary' => 'Seat ' . (string) ($row['from_seat_number'] ?? '') . ' → ' . (string) ($row['to_trip_number'] ?? '')
					. ' · ' . (string) ($row['from_trip_date'] ?? '') . ' → ' . (string) ($row['to_trip_date'] ?? ''),
			];
		}
		return ['items' => $items, 'total' => $total];
	}
}

if (!function_exists('tessera_charge_summary_payload')) {
	/**
	 * Aggregated seller × route totals for a trip-date range.
	 * @return array{items:list<array<string,mixed>>,summary:array<string,float|int>,date_from:string,date_to:string}
	 */
	function tessera_charge_summary_payload(mysqli $db, string $dateFrom, string $dateTo): array
	{
		[$dateFrom, $dateTo] = tessera_tickets_normalize_dates($dateFrom, $dateTo);
		$tenant = tessera_sql_tenant_eq();
		$fromEsc = mysqli_real_escape_string($db, $dateFrom);
		$toEsc = mysqli_real_escape_string($db, $dateTo);
		$where = "{$tenant} AND trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}' AND cost != 0
			AND (delete_book='' OR delete_book IS NULL)";
		$items = [];
		$totalSales = 0.0;
		$sellers = 0;
		$q = mysqli_query(
			$db,
			"SELECT booker, route_id, COUNT(*) AS tickets, SUM(cost) AS sales
			 FROM booking WHERE {$where}
			 GROUP BY booker, route_id
			 ORDER BY booker ASC, sales DESC"
		);
		$routeCache = [];
		while ($q && ($row = mysqli_fetch_assoc($q))) {
			$booker = (string) ($row['booker'] ?? '');
			$routeId = (int) ($row['route_id'] ?? 0);
			$sales = (float) ($row['sales'] ?? 0);
			$tickets = (int) ($row['tickets'] ?? 0);
			$totalSales += $sales;
			if (!isset($routeCache[$routeId])) {
				$label = 'Route #' . $routeId;
				$rq = mysqli_query(
					$db,
					'SELECT start_route, end_route, route_name FROM routes WHERE id=' . $routeId . ' OR route_id=' . $routeId . ' LIMIT 1'
				);
				if ($rq && ($rr = mysqli_fetch_assoc($rq))) {
					$label = trim((string) ($rr['route_name'] ?? ''));
					if ($label === '') {
						$label = trim((string) ($rr['start_route'] ?? '') . ' → ' . (string) ($rr['end_route'] ?? ''));
					}
				}
				$routeCache[$routeId] = $label !== '' ? $label : ('Route #' . $routeId);
			}
			$items[] = [
				'booker' => $booker,
				'route_id' => $routeId,
				'route' => $routeCache[$routeId],
				'tickets' => $tickets,
				'sales' => $sales,
			];
		}
		$sellers = count(array_unique(array_map(static fn($i) => $i['booker'], $items)));
		return [
			'items' => $items,
			'summary' => [
				'sellers' => $sellers,
				'lines' => count($items),
				'total_sales' => $totalSales,
			],
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}
}

if (!function_exists('tessera_sales_summary_payload')) {
	/**
	 * Trip sales print/report for a route_day + trip_date (+ optional manifest).
	 * @return array{ok:bool,error?:string,context?:array<string,mixed>,items?:list<array<string,mixed>>,summary?:array<string,float|int>}
	 */
	function tessera_sales_summary_payload(
		mysqli $db,
		int $routeDayId,
		string $tripDate,
		int $manifestId = 0
	): array {
		if ($routeDayId <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tripDate)) {
			return ['ok' => false, 'error' => 'Route day and trip date are required'];
		}
		$tenant = tessera_sql_tenant_eq();
		$rdq = mysqli_query($db, "SELECT * FROM route_day WHERE id={$routeDayId} LIMIT 1");
		$rd = $rdq ? $rdq->fetch_assoc() : null;
		if (!$rd) {
			return ['ok' => false, 'error' => 'Trip template not found'];
		}
		$routeId = (int) ($rd['route_id'] ?? 0);
		$bus = (string) ($rd['bus'] ?? '');
		$start = '';
		$end = '';
		$rq = mysqli_query($db, "SELECT start_route, end_route, route_name, fare_price FROM routes WHERE id={$routeId} OR route_id={$routeId} LIMIT 1");
		if ($rq && ($rr = $rq->fetch_assoc())) {
			$start = (string) ($rr['start_route'] ?? '');
			$end = (string) ($rr['end_route'] ?? '');
		}
		$driver = '';
		$conductor = '';
		if ($bus !== '') {
			$bEsc = mysqli_real_escape_string($db, $bus);
			$vq = mysqli_query($db, "SELECT driver, conductor FROM vehicle WHERE name='{$bEsc}' LIMIT 1");
			if ($vq && ($vr = $vq->fetch_assoc())) {
				$driver = (string) ($vr['driver'] ?? '');
				$conductor = (string) ($vr['conductor'] ?? '');
			}
		}
		if ($manifestId > 0) {
			$mq = mysqli_query($db, "SELECT bus, driver, conductor, start_location, end_location FROM manifest WHERE id={$manifestId} LIMIT 1");
			if ($mq && ($mr = $mq->fetch_assoc())) {
				$bus = (string) ($mr['bus'] ?? $bus);
				$driver = (string) ($mr['driver'] ?? $driver);
				$conductor = (string) ($mr['conductor'] ?? $conductor);
				if (!empty($mr['start_location'])) {
					$start = (string) $mr['start_location'];
				}
				if (!empty($mr['end_location'])) {
					$end = (string) $mr['end_location'];
				}
			}
		}
		$dEsc = mysqli_real_escape_string($db, $tripDate);
		$bEsc = mysqli_real_escape_string($db, $bus);
		$where = "{$tenant} AND route_day_id={$routeDayId} AND trip_date='{$dEsc}'
			AND (delete_book='' OR delete_book IS NULL)";
		if ($bus !== '') {
			$where .= " AND bus='{$bEsc}'";
		}
		$items = [];
		$total = 0.0;
		$count = 0;
		$lq = mysqli_query(
			$db,
			"SELECT id, seat_no, firstname, lastname, cost, issuer, booker, phone_no, status
			 FROM booking WHERE {$where} ORDER BY seat_no ASC, id ASC"
		);
		while ($lq && ($row = mysqli_fetch_assoc($lq))) {
			$cost = (float) ($row['cost'] ?? 0);
			$total += $cost;
			$count++;
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'seat_no' => (string) ($row['seat_no'] ?? ''),
				'fullname' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['lastname'] ?? '')),
				'cost' => $cost,
				'issuer' => (string) ($row['issuer'] ?? ''),
				'booker' => (string) ($row['booker'] ?? ''),
				'phone' => (string) ($row['phone_no'] ?? ''),
				'status' => (string) ($row['status'] ?? ''),
			];
		}
		return [
			'ok' => true,
			'context' => [
				'route_day_id' => $routeDayId,
				'manifest_id' => $manifestId,
				'trip_date' => $tripDate,
				'bus' => $bus,
				'driver' => $driver,
				'conductor' => $conductor,
				'route' => trim($start . ($end !== '' ? ' → ' . $end : '')),
				'start' => $start,
				'end' => $end,
			],
			'items' => $items,
			'summary' => [
				'tickets' => $count,
				'total_sales' => $total,
			],
		];
	}
}
