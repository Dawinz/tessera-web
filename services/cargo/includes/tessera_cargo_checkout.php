<?php
/**
 * Shared Cargo destination checkout helpers (C-P0-05).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_checkout_filters')) {
	/**
	 * @return array{routes:list<string>}
	 */
	function tessera_cargo_checkout_filters(mysqli $db, string $username): array
	{
		$u = $db->real_escape_string($username);
		$routes = [];
		$q = $db->query(
			"SELECT route_full_name FROM assigned_routes WHERE username='{$u}' ORDER BY preference DESC, id DESC"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$name = trim((string) ($row['route_full_name'] ?? ''));
				if ($name !== '' && !in_array($name, $routes, true)) {
					$routes[] = $name;
				}
			}
		}
		return ['routes' => $routes];
	}
}

if (!function_exists('tessera_cargo_checkout_decode_detail')) {
	function tessera_cargo_checkout_decode_detail(string $stored): string
	{
		$stored = trim($stored);
		if ($stored === '') {
			return '—';
		}
		$decoded = base64_decode($stored, true);
		$detail = $stored;
		if ($decoded !== false && $decoded !== '' && preg_match('//u', $decoded)
			&& !preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $decoded)
			&& preg_match('/[\p{L}\p{N}\s]/u', $decoded)) {
			$detail = $decoded;
		}
		$detail = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $detail) ?? '');
		if ($detail === '' || !preg_match('/[\p{L}\p{N}]/u', $detail)) {
			return '—';
		}
		return $detail;
	}
}

if (!function_exists('tessera_cargo_checkout_build_where')) {
	/**
	 * @param array{q?:string,route?:string,checked?:string} $filters
	 */
	function tessera_cargo_checkout_build_where(mysqli $db, array $filters): string
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$parts = [tessera_sql_tenant_eq()];

		$route = trim((string) ($filters['route'] ?? ''));
		if ($route !== '') {
			$parts[] = "parcel_route='" . $db->real_escape_string($route) . "'";
		}

		$checked = strtolower(trim((string) ($filters['checked'] ?? '')));
		if ($checked === 'out') {
			$parts[] = "checked='out'";
		} elseif ($checked === 'pending') {
			$parts[] = "(checked='' OR checked IS NULL)";
		}

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(code LIKE '{$like}' OR username LIKE '{$like}' OR bus LIKE '{$like}' OR sender_no LIKE '{$like}' OR reciever_no LIKE '{$like}')";
		}

		return implode(' AND ', $parts);
	}
}

if (!function_exists('tessera_cargo_checkout_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_cargo_checkout_row_to_item(array $row): array
	{
		$id = (int) ($row['id'] ?? 0);
		$code = (string) ($row['code'] ?? '');
		$checked = trim((string) ($row['checked'] ?? ''));
		$status = (string) ($row['status'] ?? '');
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

		$date = (string) ($row['trip_date'] ?? '');
		$dateLabel = $date;
		$ts = strtotime($date);
		if ($ts) {
			$dateLabel = date('d M Y', $ts);
		}

		$weight = trim((string) ($row['weight_value'] ?? '') . (string) ($row['weight_name'] ?? ''));
		$qLeft = (string) ($row['q_left'] ?? '');
		$qty = (string) ($row['quantity'] ?? '');

		return [
			'id' => $id,
			'code' => $code,
			'owner' => (string) ($row['username'] ?? ''),
			'weight' => $weight !== '' ? $weight : '—',
			'detail' => tessera_cargo_checkout_decode_detail((string) ($row['detail'] ?? '')),
			'amount' => (float) ($row['amount'] ?? 0),
			'to_be' => (float) ($row['to_be'] ?? 0),
			'paid' => (float) ($row['paid'] ?? 0),
			'vehicle' => (string) ($row['bus'] ?? ''),
			'trip_date' => $date,
			'date_label' => $dateLabel,
			'quantity_left' => trim($qLeft . ($qty !== '' ? ' / ' . $qty : '')),
			'route' => (string) ($row['parcel_route'] ?? ''),
			'payment_status' => $status,
			'payment_label' => $payLabel,
			'payment_badge' => $payBadge,
			'checked' => $checked === 'out',
			'checked_label' => $checked === 'out' ? 'Out' : 'Pending',
			'print_url' => '/cargo/qrcode/print_recieved.php?code=' . rawurlencode($code) . '&id=' . $id,
		];
	}
}

if (!function_exists('tessera_cargo_checkout_list')) {
	/**
	 * @param array{q?:string,route?:string,checked?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int}
	 */
	function tessera_cargo_checkout_list(mysqli $db, array $filters, int $page, int $perPage): array
	{
		$where = tessera_cargo_checkout_build_where($db, $filters);
		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM manifest WHERE {$where}");
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$total = (int) ($cr['c'] ?? 0);
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$items = [];
		$sql = "SELECT id, code, username, amount, trip_date, bus, paid, to_be, status, q_left, quantity,
			detail, checked, parcel_route, weight_name, weight_value, sender_no, reciever_no
			FROM manifest WHERE {$where} ORDER BY id DESC LIMIT {$offset}, {$perPage}";
		$q = $db->query($sql);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$items[] = tessera_cargo_checkout_row_to_item($row);
			}
		}
		return ['items' => $items, 'total' => $total];
	}
}

if (!function_exists('tessera_cargo_checkout_bump_checked_manifest')) {
	function tessera_cargo_checkout_bump_checked_manifest(mysqli $db, string $bus, string $date): void
	{
		$busEsc = $db->real_escape_string($bus);
		$dateEsc = $db->real_escape_string($date);
		$q = $db->query("SELECT times FROM checked_manifest WHERE name='{$busEsc}' AND trip_date='{$dateEsc}' LIMIT 1");
		if ($q && $q->num_rows > 0) {
			$row = $q->fetch_assoc();
			$times = ((int) ($row['times'] ?? 0)) + 1;
			if ($times >= 5) {
				$db->query(
					"UPDATE vehicle_name SET disable='disabled' WHERE date='{$dateEsc}' AND Name='{$busEsc}'"
				);
			}
			$db->query(
				"UPDATE checked_manifest SET times='{$times}' WHERE name='{$busEsc}' AND trip_date='{$dateEsc}'"
			);
		} else {
			$db->query(
				"INSERT INTO checked_manifest (name, trip_date, status, times) VALUES ('{$busEsc}', '{$dateEsc}', '', '1')"
			);
		}
	}
}

if (!function_exists('tessera_cargo_checkout_toggle')) {
	/**
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>,state?:string}
	 */
	function tessera_cargo_checkout_toggle(mysqli $db, string $username, int $id): array
	{
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Invalid parcel id'];
		}
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$tenant = tessera_sql_tenant_eq();
		$gq = $db->query("SELECT * FROM manifest WHERE id={$id} AND {$tenant} LIMIT 1");
		$row = $gq ? $gq->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Parcel not found on manifest'];
		}

		$checked = trim((string) ($row['checked'] ?? ''));
		$date = (string) ($row['trip_date'] ?? '');
		$bus = (string) ($row['bus'] ?? '');
		$paid = (float) ($row['paid'] ?? 0);
		$toBe = (float) ($row['to_be'] ?? 0);
		$amount = (float) ($row['amount'] ?? 0);
		$busEsc = $db->real_escape_string($bus);
		$dateEsc = $db->real_escape_string($date);
		$userEsc = $db->real_escape_string($username);
		$now = $db->real_escape_string(date('Y-m-d') . ' ' . date('h:i A'));

		if ($checked === '') {
			$upd = $db->query(
				"UPDATE manifest SET checked='out', username_taken='{$userEsc}', timetaken='{$now}' WHERE id={$id}"
			);
			if (!$upd) {
				return ['ok' => false, 'error' => 'Checkout failed: ' . $db->error];
			}
			$vq = $db->query("SELECT l_paid, l_to_be, l_amount FROM vehicle_name WHERE Name='{$busEsc}' AND date='{$dateEsc}' LIMIT 1");
			if ($vq && ($v = $vq->fetch_assoc())) {
				$addPaid = (float) ($v['l_paid'] ?? 0) + $paid;
				$addToBe = (float) ($v['l_to_be'] ?? 0) + $toBe;
				$addAmt = (float) ($v['l_amount'] ?? 0) + $amount;
				$db->query(
					"UPDATE vehicle_name SET l_paid='{$addPaid}', l_to_be='{$addToBe}', l_amount='{$addAmt}'
					 WHERE date='{$dateEsc}' AND Name='{$busEsc}'"
				);
			}
			tessera_cargo_checkout_bump_checked_manifest($db, $bus, $date);
			$state = 'out';
		} else {
			$upd = $db->query("UPDATE manifest SET checked='', username_taken='', timetaken='' WHERE id={$id}");
			if (!$upd) {
				return ['ok' => false, 'error' => 'Uncheck failed: ' . $db->error];
			}
			$vq = $db->query("SELECT l_paid, l_to_be, l_amount FROM vehicle_name WHERE Name='{$busEsc}' AND date='{$dateEsc}' LIMIT 1");
			if ($vq && ($v = $vq->fetch_assoc())) {
				$addPaid = (float) ($v['l_paid'] ?? 0) - $paid;
				$addToBe = (float) ($v['l_to_be'] ?? 0) - $toBe;
				$addAmt = (float) ($v['l_amount'] ?? 0) - $amount;
				$db->query(
					"UPDATE vehicle_name SET l_paid='{$addPaid}', l_to_be='{$addToBe}', l_amount='{$addAmt}'
					 WHERE date='{$dateEsc}' AND Name='{$busEsc}'"
				);
			}
			$state = 'pending';
		}

		$gq2 = $db->query("SELECT * FROM manifest WHERE id={$id} LIMIT 1");
		$grow = $gq2 ? $gq2->fetch_assoc() : $row;
		return [
			'ok' => true,
			'state' => $state,
			'item' => tessera_cargo_checkout_row_to_item($grow),
		];
	}
}
