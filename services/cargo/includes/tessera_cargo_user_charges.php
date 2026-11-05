<?php
/**
 * Shared Cargo user-charge reports (detailed, daily summary, multi-user, manifest lines).
 * Detailed parcel rows reuse tessera_cargo_charges_list.
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_user_charges_range')) {
	/** @return array{0:string,1:string} */
	function tessera_cargo_user_charges_range(string $from, string $to): array
	{
		if (!function_exists('tessera_cargo_ops_date')) {
			require_once __DIR__ . '/tessera_cargo_ops.php';
		}
		$from = tessera_cargo_ops_date($from, date('Y-m-01'));
		$to = tessera_cargo_ops_date($to, date('Y-m-t'));
		if ($from > $to) {
			$swap = $from;
			$from = $to;
			$to = $swap;
		}
		return [$from, $to];
	}
}

if (!function_exists('tessera_cargo_user_charges_route_sql')) {
	function tessera_cargo_user_charges_route_sql(mysqli $db, string $sessionUser, string $route, string $column): string
	{
		if (!function_exists('tessera_cargo_ops_assigned')) {
			require_once __DIR__ . '/tessera_cargo_ops.php';
		}
		$route = trim($route);
		if ($route !== '') {
			return $column . "='" . $db->real_escape_string($route) . "'";
		}
		$assigned = tessera_cargo_ops_assigned($db, $sessionUser);
		if ($assigned === []) {
			return '1';
		}
		$ors = [];
		foreach ($assigned as $name) {
			$ors[] = $column . "='" . $db->real_escape_string($name) . "'";
		}
		return '(' . implode(' OR ', $ors) . ')';
	}
}

if (!function_exists('tessera_cargo_user_charges_daily')) {
	/**
	 * One seller per day, or every seller on the route when $allUsers is true.
	 *
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array{paid:float,to_be:float,count:int,amount:float},seller:string,can_pick_user:bool,date_from:string,date_to:string,route:string}
	 */
	function tessera_cargo_user_charges_daily(
		mysqli $db,
		string $sessionUser,
		array $filters,
		bool $allUsers,
		int $page,
		int $perPage
	): array {
		if (!function_exists('tessera_cargo_charges_resolve_user')) {
			require_once __DIR__ . '/tessera_cargo_charges.php';
		}
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}

		[$from, $to] = tessera_cargo_user_charges_range(
			(string) ($filters['date_from'] ?? ''),
			(string) ($filters['date_to'] ?? '')
		);
		$route = trim((string) ($filters['route'] ?? ''));
		$seller = $allUsers
			? ''
			: tessera_cargo_charges_resolve_user($db, $sessionUser, (string) ($filters['user'] ?? ''));

		$parts = [
			tessera_sql_tenant_eq(),
			"open != 'yes'",
			"date_insert >= '" . $db->real_escape_string($from) . "'",
			"date_insert <= '" . $db->real_escape_string($to) . "'",
			tessera_cargo_user_charges_route_sql($db, $sessionUser, $route, 'account'),
		];
		if (!$allUsers) {
			$parts[] = "username='" . $db->real_escape_string($seller) . "'";
		}
		$where = implode(' AND ', $parts);
		$selectUser = $allUsers ? 'username' : "'" . $db->real_escape_string($seller) . "' AS username";
		$group = $allUsers ? 'date_insert, username, account' : 'date_insert, account';

		$summary = ['paid' => 0.0, 'to_be' => 0.0, 'count' => 0, 'amount' => 0.0];
		$sq = $db->query(
			"SELECT COALESCE(SUM(paid),0) AS paid, COALESCE(SUM(to_be),0) AS to_be,
			 COALESCE(SUM(amount),0) AS amount
			 FROM parcel_info WHERE {$where}"
		);
		$cq = $db->query(
			"SELECT COUNT(*) AS c FROM (
			   SELECT date_insert FROM parcel_info WHERE {$where} GROUP BY {$group}
			 ) t"
		);
		if ($sq && ($sr = $sq->fetch_assoc())) {
			$summary['paid'] = (float) ($sr['paid'] ?? 0);
			$summary['to_be'] = (float) ($sr['to_be'] ?? 0);
			$summary['amount'] = (float) ($sr['amount'] ?? 0);
		}
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$summary['count'] = (int) ($cr['c'] ?? 0);
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;
		$items = [];
		$lq = $db->query(
			"SELECT {$selectUser}, date_insert, account,
			 COUNT(*) AS receipts,
			 COALESCE(SUM(paid),0) AS paid,
			 COALESCE(SUM(to_be),0) AS to_be,
			 COALESCE(SUM(amount),0) AS amount
			 FROM parcel_info WHERE {$where}
			 GROUP BY {$group}
			 ORDER BY date_insert DESC, username ASC
			 LIMIT {$offset}, {$perPage}"
		);
		if ($lq) {
			while ($row = $lq->fetch_assoc()) {
				$paid = (float) ($row['paid'] ?? 0);
				$toBe = (float) ($row['to_be'] ?? 0);
				$day = (string) ($row['date_insert'] ?? '');
				$items[] = [
					'username' => (string) ($row['username'] ?? ''),
					'date' => $day,
					'date_label' => function_exists('tessera_cargo_ops_date_label')
						? tessera_cargo_ops_date_label($day)
						: $day,
					'route' => (string) ($row['account'] ?? $route),
					'receipts' => (int) ($row['receipts'] ?? 0),
					'paid' => $paid,
					'to_be' => $toBe,
					'amount' => $paid + $toBe,
				];
			}
		}

		return [
			'items' => $items,
			'total' => $summary['count'],
			'summary' => $summary,
			'seller' => $seller,
			'can_pick_user' => tessera_cargo_charges_can_pick_user($db, $sessionUser),
			'date_from' => $from,
			'date_to' => $to,
			'route' => $route,
		];
	}
}

if (!function_exists('tessera_cargo_user_charges_manifest')) {
	/**
	 * Manifest lines for one uploader (per user on a trip, or the signed-in user across a bus/date range).
	 *
	 * @param array{scope?:string,user?:string,bus?:string,date?:string,date_from?:string,date_to?:string,q?:string} $filters
	 * @return array<string,mixed>
	 */
	function tessera_cargo_user_charges_manifest(
		mysqli $db,
		string $sessionUser,
		array $filters,
		int $page,
		int $perPage
	): array {
		if (!function_exists('tessera_cargo_charges_resolve_user')) {
			require_once __DIR__ . '/tessera_cargo_charges.php';
		}
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		if (!function_exists('tessera_cargo_ops_decode')) {
			require_once __DIR__ . '/tessera_cargo_ops.php';
		}

		$scope = strtolower(trim((string) ($filters['scope'] ?? 'mine')));
		$uploader = $scope === 'user'
			? tessera_cargo_charges_resolve_user($db, $sessionUser, (string) ($filters['user'] ?? ''))
			: $sessionUser;

		$single = trim((string) ($filters['date'] ?? ''));
		if ($single !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $single)) {
			$from = $single;
			$to = $single;
		} else {
			[$from, $to] = tessera_cargo_user_charges_range(
				(string) ($filters['date_from'] ?? ''),
				(string) ($filters['date_to'] ?? '')
			);
		}

		$bus = trim((string) ($filters['bus'] ?? ''));
		$parts = [
			tessera_sql_tenant_eq(),
			"parcel_uploader='" . $db->real_escape_string($uploader) . "'",
			"trip_date >= '" . $db->real_escape_string($from) . "'",
			"trip_date <= '" . $db->real_escape_string($to) . "'",
		];
		if ($bus !== '') {
			$parts[] = "bus='" . $db->real_escape_string($bus) . "'";
		}
		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(code LIKE '{$like}' OR username LIKE '{$like}' OR parcel_route LIKE '{$like}')";
		}
		$where = implode(' AND ', $parts);

		$summary = ['paid' => 0.0, 'to_be' => 0.0, 'amount' => 0.0, 'count' => 0];
		$sq = $db->query(
			"SELECT COUNT(*) AS c, COALESCE(SUM(paid),0) AS paid, COALESCE(SUM(to_be),0) AS to_be,
			 COALESCE(SUM(amount),0) AS amount FROM manifest WHERE {$where}"
		);
		if ($sq && ($sr = $sq->fetch_assoc())) {
			$summary = [
				'paid' => (float) ($sr['paid'] ?? 0),
				'to_be' => (float) ($sr['to_be'] ?? 0),
				'amount' => (float) ($sr['amount'] ?? 0),
				'count' => (int) ($sr['c'] ?? 0),
			];
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;
		$items = [];
		$lq = $db->query(
			"SELECT id, code, username, parcel_route, bus, trip_date, status, detail, paid, to_be, amount,
			 quantity, q_left, parcel_uploader
			 FROM manifest WHERE {$where} ORDER BY trip_date DESC, id DESC LIMIT {$offset}, {$perPage}"
		);
		if ($lq) {
			while ($row = $lq->fetch_assoc()) {
				$day = (string) ($row['trip_date'] ?? '');
				$items[] = [
					'id' => (int) ($row['id'] ?? 0),
					'code' => (string) ($row['code'] ?? ''),
					'owner' => (string) ($row['username'] ?? ''),
					'route' => (string) ($row['parcel_route'] ?? ''),
					'bus' => (string) ($row['bus'] ?? ''),
					'trip_date' => $day,
					'date_label' => tessera_cargo_ops_date_label($day),
					'status' => (string) ($row['status'] ?? ''),
					'detail' => tessera_cargo_ops_decode((string) ($row['detail'] ?? '')),
					'paid' => (float) ($row['paid'] ?? 0),
					'to_be' => (float) ($row['to_be'] ?? 0),
					'amount' => (float) ($row['amount'] ?? 0),
					'quantity' => (string) ($row['q_left'] ?? '') . '/' . (string) ($row['quantity'] ?? ''),
					'uploader' => (string) ($row['parcel_uploader'] ?? ''),
				];
			}
		}

		$buses = [];
		$bq = $db->query(
			"SELECT Name, MAX(date) AS last_date FROM vehicle_name
			 WHERE date >= '2020-01-01' GROUP BY Name ORDER BY last_date DESC LIMIT 60"
		);
		if ($bq) {
			while ($row = $bq->fetch_assoc()) {
				$name = trim((string) ($row['Name'] ?? ''));
				if ($name !== '') {
					$buses[] = $name;
				}
			}
		}

		return [
			'items' => $items,
			'total' => $summary['count'],
			'summary' => $summary,
			'seller' => $uploader,
			'can_pick_user' => tessera_cargo_charges_can_pick_user($db, $sessionUser),
			'date_from' => $from,
			'date_to' => $to,
			'bus' => $bus,
			'buses' => $buses,
			'scope' => $scope === 'user' ? 'user' : 'mine',
		];
	}
}
