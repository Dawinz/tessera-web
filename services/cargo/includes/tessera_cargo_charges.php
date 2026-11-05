<?php
/**
 * Shared Cargo My charges helpers (C-P0-06).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_charges_can_pick_user')) {
	function tessera_cargo_charges_can_pick_user(mysqli $db, string $username): bool
	{
		$u = $db->real_escape_string($username);
		$role = '';
		$rq = $db->query("SELECT role FROM users WHERE username='{$u}' LIMIT 1");
		if ($rq && ($rr = $rq->fetch_assoc())) {
			$role = (string) ($rr['role'] ?? '');
		}
		if (stripos($role, 'super') !== false || strcasecmp($role, 'Administrator') === 0) {
			return true;
		}
		if (function_exists('tessera_user_role_code')) {
			$code = tessera_user_role_code($db, $username);
			return in_array($code, ['owner', 'manager'], true);
		}
		return false;
	}
}

if (!function_exists('tessera_cargo_charges_resolve_user')) {
	function tessera_cargo_charges_resolve_user(mysqli $db, string $sessionUser, string $requested): string
	{
		$requested = trim($requested);
		if (!tessera_cargo_charges_can_pick_user($db, $sessionUser)) {
			return $sessionUser;
		}
		return $requested !== '' ? $requested : $sessionUser;
	}
}

if (!function_exists('tessera_cargo_charges_filters')) {
	/**
	 * @return array{routes:list<string>,users:list<array{username:string,display_name:string}>,can_pick_user:bool}
	 */
	function tessera_cargo_charges_filters(mysqli $db, string $username): array
	{
		$u = $db->real_escape_string($username);
		$routes = [];
		$rq = $db->query(
			"SELECT route_full_name FROM assigned_routes WHERE username='{$u}' ORDER BY preference DESC, id DESC"
		);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$name = trim((string) ($row['route_full_name'] ?? ''));
				if ($name !== '' && !in_array($name, $routes, true)) {
					$routes[] = $name;
				}
			}
		}

		$canPick = tessera_cargo_charges_can_pick_user($db, $username);
		$users = [];
		if ($canPick) {
			$uq = $db->query(
				"SELECT username, fullname, firstname, surname FROM users ORDER BY username ASC LIMIT 500"
			);
			if ($uq) {
				while ($row = $uq->fetch_assoc()) {
					$un = trim((string) ($row['username'] ?? ''));
					if ($un === '') {
						continue;
					}
					$display = trim((string) ($row['fullname'] ?? ''));
					if ($display === '') {
						$display = trim(((string) ($row['firstname'] ?? '')) . ' ' . ((string) ($row['surname'] ?? '')));
					}
					if ($display === '') {
						$display = $un;
					}
					$users[] = ['username' => $un, 'display_name' => $display];
				}
			}
		} else {
			$uq = $db->query(
				"SELECT username, fullname, firstname, surname FROM users WHERE username='{$u}' LIMIT 1"
			);
			if ($uq && ($row = $uq->fetch_assoc())) {
				$display = trim((string) ($row['fullname'] ?? ''));
				if ($display === '') {
					$display = trim(((string) ($row['firstname'] ?? '')) . ' ' . ((string) ($row['surname'] ?? '')));
				}
				$users[] = [
					'username' => $username,
					'display_name' => $display !== '' ? $display : $username,
				];
			} else {
				$users[] = ['username' => $username, 'display_name' => $username];
			}
		}

		return [
			'routes' => $routes,
			'users' => $users,
			'can_pick_user' => $canPick,
			'me' => $username,
		];
	}
}

if (!function_exists('tessera_cargo_charges_decode_detail')) {
	function tessera_cargo_charges_decode_detail(string $stored): string
	{
		$stored = trim($stored);
		if ($stored === '') {
			return '—';
		}
		$decoded = base64_decode($stored, true);
		if ($decoded !== false && $decoded !== '' && preg_match('//u', $decoded)
			&& !preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $decoded)) {
			return $decoded;
		}
		return $stored;
	}
}

if (!function_exists('tessera_cargo_charges_list')) {
	/**
	 * @param array{q?:string,route?:string,user?:string,date_from?:string,date_to?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array{paid:float,to_be:float,loan:float,amount:float,count:int},seller:string,can_pick_user:bool}
	 */
	function tessera_cargo_charges_list(
		mysqli $db,
		string $sessionUser,
		array $filters,
		int $page,
		int $perPage
	): array {
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}

		$seller = tessera_cargo_charges_resolve_user($db, $sessionUser, (string) ($filters['user'] ?? ''));
		$route = trim((string) ($filters['route'] ?? ''));
		$dateFrom = trim((string) ($filters['date_from'] ?? ''));
		$dateTo = trim((string) ($filters['date_to'] ?? ''));
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
			$dateFrom = date('Y-m-01');
		}
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
			$dateTo = date('Y-m-t');
		}

		$parts = [
			tessera_sql_tenant_eq(),
			"username='" . $db->real_escape_string($seller) . "'",
			"date_insert >= '" . $db->real_escape_string($dateFrom) . "'",
			"date_insert <= '" . $db->real_escape_string($dateTo) . "'",
			"open != 'yes'",
		];
		if ($route !== '') {
			$parts[] = "account='" . $db->real_escape_string($route) . "'";
		} else {
			/* Scope to assigned routes when no route picked. */
			$assigned = [];
			$u = $db->real_escape_string($sessionUser);
			$aq = $db->query("SELECT route_full_name FROM assigned_routes WHERE username='{$u}'");
			if ($aq) {
				while ($ar = $aq->fetch_assoc()) {
					$name = trim((string) ($ar['route_full_name'] ?? ''));
					if ($name !== '') {
						$assigned[] = "account='" . $db->real_escape_string($name) . "'";
					}
				}
			}
			if ($assigned !== []) {
				$parts[] = '(' . implode(' OR ', $assigned) . ')';
			}
		}

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(code LIKE '{$like}' OR s_name LIKE '{$like}' OR r_name LIKE '{$like}' OR status LIKE '{$like}')";
		}

		$where = implode(' AND ', $parts);

		$summary = ['paid' => 0.0, 'to_be' => 0.0, 'loan' => 0.0, 'amount' => 0.0, 'count' => 0];
		$sq = $db->query(
			"SELECT COUNT(*) AS c, COALESCE(SUM(paid),0) AS paid, COALESCE(SUM(to_be),0) AS to_be,
			 COALESCE(SUM(loan),0) AS loan, COALESCE(SUM(amount),0) AS amount
			 FROM parcel_info WHERE {$where}"
		);
		if ($sq && ($sr = $sq->fetch_assoc())) {
			$summary = [
				'paid' => (float) ($sr['paid'] ?? 0),
				'to_be' => (float) ($sr['to_be'] ?? 0),
				'loan' => (float) ($sr['loan'] ?? 0),
				'amount' => (float) ($sr['amount'] ?? 0),
				'count' => (int) ($sr['c'] ?? 0),
			];
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$items = [];
		$listQ = $db->query(
			"SELECT id, code, s_name, r_name, account, date_insert, time, detail, amount, paid, to_be, loan,
			 status, weight_value, weight_name, scanned, question
			 FROM parcel_info WHERE {$where} ORDER BY date_insert DESC, id DESC LIMIT {$offset}, {$perPage}"
		);
		if ($listQ) {
			while ($row = $listQ->fetch_assoc()) {
				$loan = (float) ($row['loan'] ?? 0);
				$amount = (float) ($row['amount'] ?? 0);
				$date = (string) ($row['date_insert'] ?? '');
				$time = (string) ($row['time'] ?? '');
				$items[] = [
					'id' => (int) ($row['id'] ?? 0),
					'code' => (string) ($row['code'] ?? ''),
					'sender_name' => (string) ($row['s_name'] ?? ''),
					'receiver_name' => (string) ($row['r_name'] ?? ''),
					'route' => (string) ($row['account'] ?? ''),
					'date_insert' => $date,
					'date_label' => trim($date . ($time !== '' ? ' at ' . $time : '')),
					'detail' => tessera_cargo_charges_decode_detail((string) ($row['detail'] ?? '')),
					'amount' => $amount,
					'loan' => $loan,
					'loan_total' => $loan + $amount,
					'paid' => (float) ($row['paid'] ?? 0),
					'to_be' => (float) ($row['to_be'] ?? 0),
					'quantity' => trim((string) ($row['scanned'] ?? '') . '/' . (string) ($row['question'] ?? '')),
					'weight' => trim((string) ($row['weight_value'] ?? '') . ' ' . (string) ($row['weight_name'] ?? '')),
					'payment_status' => (string) ($row['status'] ?? ''),
					'print_url' => '/cargo/print_ticket_mulitple.php?code=' . rawurlencode((string) ($row['code'] ?? '')),
				];
			}
		}

		return [
			'items' => $items,
			'total' => $summary['count'],
			'summary' => $summary,
			'seller' => $seller,
			'can_pick_user' => tessera_cargo_charges_can_pick_user($db, $sessionUser),
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}
}
