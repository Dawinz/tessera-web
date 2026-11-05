<?php
/**
 * Shared Cargo All-luggages list helpers (C-P0-02).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_luggages_assigned_routes')) {
	/**
	 * @return list<string>
	 */
	function tessera_cargo_luggages_assigned_routes(mysqli $db, string $username): array
	{
		$u = $db->real_escape_string($username);
		$out = [];
		$q = $db->query(
			"SELECT route_full_name FROM assigned_routes WHERE username='{$u}' ORDER BY preference DESC, id DESC"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$name = trim((string) ($row['route_full_name'] ?? ''));
				if ($name !== '' && !in_array($name, $out, true)) {
					$out[] = $name;
				}
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_cargo_luggages_filters')) {
	/**
	 * @return array{routes:list<string>}
	 */
	function tessera_cargo_luggages_filters(mysqli $db, string $username): array
	{
		return [
			'routes' => tessera_cargo_luggages_assigned_routes($db, $username),
		];
	}
}

if (!function_exists('tessera_cargo_luggages_build_where')) {
	/**
	 * @param array{q?:string,route?:string,store?:string,date_from?:string,date_to?:string} $filters
	 * @param list<string> $assignedRoutes
	 * @return array{where:string,order:string}
	 */
	function tessera_cargo_luggages_build_where(mysqli $db, array $filters, array $assignedRoutes): array
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$parts = [tessera_sql_tenant_eq()];

		$route = trim((string) ($filters['route'] ?? ''));
		if ($route !== '' && strcasecmp($route, 'Select Route') !== 0) {
			$parts[] = "account='" . $db->real_escape_string($route) . "'";
		} elseif ($assignedRoutes !== []) {
			$ors = [];
			foreach ($assignedRoutes as $name) {
				$ors[] = "account='" . $db->real_escape_string($name) . "'";
			}
			$parts[] = '(' . implode(' OR ', $ors) . ')';
		}

		$store = strtolower(trim((string) ($filters['store'] ?? '')));
		if ($store === 'not_out') {
			/* Store inventory default: still in warehouse (store + half_out). */
			$parts[] = "store <> 'out'";
		} elseif (in_array($store, ['store', 'half_out', 'out'], true)) {
			$parts[] = "store='" . $db->real_escape_string($store) . "'";
		}

		$dateFrom = trim((string) ($filters['date_from'] ?? ''));
		$dateTo = trim((string) ($filters['date_to'] ?? ''));
		$order = 'date_insert DESC, id DESC';
		if ($dateFrom !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
			$parts[] = "date_insert >= '" . $db->real_escape_string($dateFrom) . "'";
			$order = 'date_insert ASC, id ASC';
		}
		if ($dateTo !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
			$parts[] = "date_insert <= '" . $db->real_escape_string($dateTo) . "'";
			$order = 'date_insert ASC, id ASC';
		}

		$owner = trim((string) ($filters['owner'] ?? ''));
		if ($owner !== '') {
			$parts[] = "username='" . $db->real_escape_string($owner) . "'";
		}

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(code LIKE '{$like}' OR s_name LIKE '{$like}' OR r_name LIKE '{$like}' OR s_no LIKE '{$like}' OR r_no LIKE '{$like}')";
		}

		return [
			'where' => implode(' AND ', $parts),
			'order' => $order,
		];
	}
}

if (!function_exists('tessera_cargo_luggages_decode_detail')) {
	function tessera_cargo_luggages_decode_detail(string $detail): string
	{
		$raw = trim($detail);
		if ($raw === '') {
			return '';
		}
		$decoded = base64_decode($raw, true);
		if ($decoded !== false && $decoded !== '' && preg_match('//u', $decoded)) {
			return $decoded;
		}
		return $raw;
	}
}

if (!function_exists('tessera_cargo_luggages_qty_label')) {
	function tessera_cargo_luggages_qty_label($question, $scanned): string
	{
		$qty = (string) $question;
		$scan = (string) $scanned;
		if ($qty === $scan || $scan === '' || $scan === '0') {
			return $qty !== '' ? $qty : '1';
		}
		return $scan . ' out of ' . $qty;
	}
}

if (!function_exists('tessera_cargo_luggages_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_cargo_luggages_row_to_item(array $row): array
	{
		$id = (int) ($row['id'] ?? 0);
		$code = (string) ($row['code'] ?? '');
		$store = (string) ($row['store'] ?? 'store');
		$status = (string) ($row['status'] ?? '');
		$paid = (float) ($row['paid'] ?? 0);
		$toBe = (float) ($row['to_be'] ?? 0);
		$paidRemain = (float) ($row['paid_remain'] ?? 0);
		$toBeRemain = (float) ($row['to_be_remain'] ?? 0);
		$amount = (float) ($row['amount'] ?? 0);
		$isHalf = $store === 'half_out';

		$storeLabel = 'Store';
		if ($store === 'half_out') {
			$storeLabel = 'Half out';
		} elseif ($store === 'out') {
			$storeLabel = 'Out';
		}

		$payBadge = 'neutral';
		$payLabel = $status;
		if ($status === 'paid') {
			$payBadge = 'paid';
			$payLabel = 'Paid';
		} elseif ($status === 'To be Payed') {
			$payBadge = 'warn';
			$payLabel = 'To be paid';
		} elseif ($status === 'Due payment') {
			$payBadge = 'due';
			$payLabel = 'Due';
		}

		$dateInsert = (string) ($row['date_insert'] ?? '');
		$dateLabel = $dateInsert;
		$ts = strtotime($dateInsert);
		if ($ts) {
			$dateLabel = date('d M Y', $ts);
		}

		$weightVal = (string) ($row['weight_value'] ?? '');
		$weightName = (string) ($row['weight_name'] ?? '');
		$weight = trim($weightVal . $weightName);

		return [
			'id' => $id,
			'code' => $code,
			'sender_name' => (string) ($row['s_name'] ?? ''),
			'sender_phone' => (string) ($row['s_no'] ?? ''),
			'receiver_name' => (string) ($row['r_name'] ?? ''),
			'receiver_phone' => (string) ($row['r_no'] ?? ''),
			'route' => (string) ($row['account'] ?? ''),
			'date_insert' => $dateInsert,
			'date_label' => $dateLabel,
			'weight' => $weight,
			'detail' => tessera_cargo_luggages_decode_detail((string) ($row['detail'] ?? '')),
			'quantity' => tessera_cargo_luggages_qty_label($row['question'] ?? '1', $row['scanned'] ?? ''),
			'paid' => $isHalf ? $paidRemain : $paid,
			'to_be' => $isHalf ? $toBeRemain : $toBe,
			'amount' => $isHalf ? ($paidRemain + $toBeRemain) : ($paid + $toBe),
			'amount_original' => $amount,
			'store' => $store,
			'store_label' => $storeLabel,
			'payment_status' => $status,
			'payment_label' => $payLabel,
			'payment_badge' => $payBadge,
			'view_url' => '/cargo/view.php?u=' . $id,
			'edit_url' => '/cargo/update_luggage.php?u=' . $id,
			'print_url' => '/cargo/print_ticket_mulitple.php?code=' . rawurlencode($code),
		];
	}
}

if (!function_exists('tessera_cargo_luggages_list')) {
	/**
	 * @param array{q?:string,route?:string,store?:string,date_from?:string,date_to?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int}
	 */
	function tessera_cargo_luggages_list(
		mysqli $db,
		string $username,
		array $filters,
		int $page,
		int $perPage
	): array {
		$assigned = tessera_cargo_luggages_assigned_routes($db, $username);
		$built = tessera_cargo_luggages_build_where($db, $filters, $assigned);
		$where = $built['where'];
		$order = $built['order'];

		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM parcel_info WHERE {$where}");
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$total = (int) ($cr['c'] ?? 0);
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$items = [];
		$sql = "SELECT id, code, s_name, s_no, r_name, r_no, account, detail, status, amount, store,
			paid, to_be, paid_remain, to_be_remain, date_insert, weight_value, weight_name,
			question, scanned
			FROM parcel_info WHERE {$where} ORDER BY {$order} LIMIT {$offset}, {$perPage}";
		$q = $db->query($sql);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$items[] = tessera_cargo_luggages_row_to_item($row);
			}
		}

		return ['items' => $items, 'total' => $total];
	}
}
