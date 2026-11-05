<?php
/**
 * Shared Agent daily charge helpers (T-P0-07).
 * Report: bookings sold by a booker in a date range (cost > 0, not deleted).
 */
declare(strict_types=1);

if (!function_exists('tessera_agent_charge_can_pick_user')) {
	function tessera_agent_charge_can_pick_user(mysqli $db, string $username): bool
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

if (!function_exists('tessera_agent_charge_users')) {
	/**
	 * @return list<string>
	 */
	function tessera_agent_charge_users(mysqli $db): array
	{
		$users = [];
		$q = mysqli_query($db, "SELECT username FROM users WHERE status='active' OR status='' OR status IS NULL ORDER BY username ASC LIMIT 500");
		if (!$q) {
			$q = mysqli_query($db, 'SELECT username FROM users ORDER BY username ASC LIMIT 500');
		}
		while ($q && ($row = mysqli_fetch_assoc($q))) {
			$name = trim((string) ($row['username'] ?? ''));
			if ($name !== '') {
				$users[] = $name;
			}
		}
		return $users;
	}
}

if (!function_exists('tessera_agent_charge_resolve_booker')) {
	/**
	 * Agents always see themselves; admins may pick another booker.
	 */
	function tessera_agent_charge_resolve_booker(mysqli $db, string $sessionUser, string $requested): string
	{
		$requested = trim($requested);
		if (!tessera_agent_charge_can_pick_user($db, $sessionUser)) {
			return $sessionUser;
		}
		if ($requested === '') {
			return $sessionUser;
		}
		return $requested;
	}
}

if (!function_exists('tessera_agent_charge_payload')) {
	/**
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array<string,mixed>,booker:string,date_from:string,date_to:string}
	 */
	function tessera_agent_charge_payload(
		mysqli $db,
		string $booker,
		string $dateFrom,
		string $dateTo,
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

		$page = max(1, $page);
		$perPage = max(1, min(200, $perPage));
		$offset = ($page - 1) * $perPage;

		$bookerEsc = mysqli_real_escape_string($db, $booker);
		$fromEsc = mysqli_real_escape_string($db, $dateFrom);
		$toEsc = mysqli_real_escape_string($db, $dateTo);
		$tenant = tessera_sql_tenant_eq();

		$where = "$tenant AND booker='$bookerEsc' AND book_date >= '$fromEsc' AND book_date <= '$toEsc'
			AND cost != 0 AND (delete_book='' OR delete_book IS NULL)";

		$total = 0;
		$sumCost = 0.0;
		$cq = mysqli_query($db, "SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS s FROM booking WHERE $where");
		if ($cq && ($cr = mysqli_fetch_assoc($cq))) {
			$total = (int) ($cr['c'] ?? 0);
			$sumCost = (float) ($cr['s'] ?? 0);
		}

		$items = [];
		$sql = "SELECT id, seat_no, firstname, lastname, book_date, book_time, bus, issuer, booker,
			cost, start_route, end_route, start_location, ending_location, phone_no, status
			FROM booking WHERE $where
			ORDER BY id DESC
			LIMIT $offset, $perPage";
		$res = mysqli_query($db, $sql);
		while ($res && ($row = mysqli_fetch_assoc($res))) {
			$firstname = (string) ($row['firstname'] ?? '');
			$lastname = (string) ($row['lastname'] ?? '');
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'seat_no' => (string) ($row['seat_no'] ?? ''),
				'fullname' => trim($firstname . ' ' . $lastname),
				'phone' => (string) ($row['phone_no'] ?? ''),
				'book_date' => (string) ($row['book_date'] ?? ''),
				'book_time' => (string) ($row['book_time'] ?? ''),
				'bus' => (string) ($row['bus'] ?? ''),
				'issuer' => (string) ($row['issuer'] ?? ''),
				'booker' => (string) ($row['booker'] ?? ''),
				'cost' => (float) ($row['cost'] ?? 0),
				'start_route' => (string) (($row['start_route'] ?: $row['start_location']) ?? ''),
				'end_route' => (string) (($row['end_route'] ?: $row['ending_location']) ?? ''),
				'status' => (string) ($row['status'] ?? ''),
				'print_url' => '/tickets/print_ticket_table.php?id=' . (int) ($row['id'] ?? 0),
			];
		}

		return [
			'items' => $items,
			'total' => $total,
			'summary' => [
				'ticket_count' => $total,
				'total_sales' => $sumCost,
				'total_sales_label' => number_format($sumCost, 0) . ' Tsh',
			],
			'booker' => $booker,
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}
}
