<?php
/**
 * Shared Cargo check / load luggages helpers (C-P0-07).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_load_require_tenant')) {
	function tessera_cargo_load_require_tenant(): void
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
	}
}

if (!function_exists('tessera_cargo_load_num')) {
	function tessera_cargo_load_num($value): float
	{
		if (is_int($value) || is_float($value)) {
			return (float) $value;
		}
		$clean = preg_replace('/[^0-9.\-]+/', '', (string) $value);
		if ($clean === null || $clean === '' || $clean === '-' || $clean === '.') {
			return 0.0;
		}
		return (float) $clean;
	}
}

if (!function_exists('tessera_cargo_load_status_meta')) {
	/**
	 * @return array{badge:string,label:string}
	 */
	function tessera_cargo_load_status_meta(string $status): array
	{
		if ($status === 'paid') {
			return ['badge' => 'paid', 'label' => 'Paid'];
		}
		if ($status === 'To be Payed') {
			return ['badge' => 'warn', 'label' => 'To be paid'];
		}
		if ($status === 'Due payment') {
			return ['badge' => 'due', 'label' => 'Due'];
		}
		return ['badge' => 'neutral', 'label' => $status !== '' ? $status : '—'];
	}
}

if (!function_exists('tessera_cargo_load_resolve_context')) {
	/**
	 * Resolve manifest context from bus+date (or latest open trip).
	 *
	 * @return array{ok:bool,error?:string,context?:array<string,mixed>}
	 */
	function tessera_cargo_load_resolve_context(mysqli $db, string $bus = '', string $date = ''): array
	{
		tessera_cargo_load_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		$bus = trim($bus);
		$date = trim($date);

		$row = null;
		if ($bus !== '' && $date !== '' && (int) date('Y', strtotime($date) ?: 0) >= 2000) {
			$b = $db->real_escape_string($bus);
			$d = $db->real_escape_string($date);
			$q = $db->query(
				"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, to_pay, paid, due, remark_test, remark_html
				 FROM vehicle_name WHERE {$tenant} AND Name='{$b}' AND date='{$d}' LIMIT 1"
			);
			if ($q) {
				$row = $q->fetch_assoc() ?: null;
			}
		}

		if ($row === null) {
			$q = $db->query(
				"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, to_pay, paid, due, remark_test, remark_html
				 FROM vehicle_name WHERE {$tenant} AND date >= '2020-01-01'
				 ORDER BY date DESC, id DESC LIMIT 1"
			);
			if ($q) {
				$row = $q->fetch_assoc() ?: null;
			}
		}

		if ($row === null) {
			return ['ok' => false, 'error' => 'No manifest found'];
		}

		$name = (string) ($row['Name'] ?? '');
		$tripDate = (string) ($row['date'] ?? '');
		$route = (string) ($row['Route'] ?? '');
		$locked = ((string) ($row['disable2'] ?? '')) === 'disabled';
		$dateLabel = $tripDate;
		$ts = strtotime($tripDate);
		if ($ts) {
			$dateLabel = date('jS M Y', $ts);
		}

		$remarkPlain = '';
		$remarkRaw = (string) ($row['remark_test'] ?? '');
		if ($remarkRaw !== '') {
			$decoded = base64_decode($remarkRaw, true);
			if ($decoded !== false) {
				$remarkPlain = trim(html_entity_decode(strip_tags($decoded), ENT_QUOTES, 'UTF-8'));
			}
		}

		$from = '';
		$to = '';
		if (stripos($route, ' to ') !== false) {
			$parts = preg_split('/\s+to\s+/i', $route, 2);
			$from = trim((string) ($parts[0] ?? ''));
			$to = trim((string) ($parts[1] ?? ''));
		}

		$qs = http_build_query([
			'bus' => $name,
			'date' => $tripDate,
			'id' => (string) ($row['code'] ?? ''),
			'open' => (string) ($row['status'] ?? ''),
			'driver' => (string) ($row['Driver'] ?? ''),
			'conductor' => (string) ($row['Conductor'] ?? ''),
			'manifest_id' => (string) ($row['id'] ?? ''),
			'route_name' => $route,
			'currency' => (string) ($row['currency'] ?? ''),
		]);

		return [
			'ok' => true,
			'context' => [
				'id' => (int) ($row['id'] ?? 0),
				'code' => (string) ($row['code'] ?? ''),
				'vehicle' => $name,
				'date' => $tripDate,
				'date_label' => $dateLabel,
				'driver' => (string) ($row['Driver'] ?? ''),
				'conductor' => (string) ($row['Conductor'] ?? ''),
				'route' => $route,
				'route_from' => $from,
				'route_to' => $to,
				'status' => (string) ($row['status'] ?? ''),
				'currency' => (string) ($row['currency'] ?? ''),
				'locked' => $locked,
				'to_pay' => tessera_cargo_load_num($row['to_pay'] ?? 0),
				'paid' => tessera_cargo_load_num($row['paid'] ?? 0),
				'due' => tessera_cargo_load_num($row['due'] ?? 0),
				'remark' => $remarkPlain,
				'view_url' => '/cargo/vehicle_report_new.php?' . http_build_query([
					'date' => $tripDate,
					'date2' => $tripDate,
					'date3' => $name,
					'open' => (string) ($row['status'] ?? ''),
					'route' => $route,
					'driver' => (string) ($row['Driver'] ?? ''),
					'Conductor' => (string) ($row['Conductor'] ?? ''),
					'code' => (string) ($row['code'] ?? ''),
				]),
				'manifest_url' => '/operator/app.php?module=cargo&page=vehicle_view.php',
				'load_path' => '/operator/app.php?module=cargo&page=check_luggages.php&' . $qs,
			],
		];
	}
}

if (!function_exists('tessera_cargo_load_parcel_row')) {
	/**
	 * @return array<string,mixed>|null
	 */
	function tessera_cargo_load_parcel_row(mysqli $db, string $code): ?array
	{
		tessera_cargo_load_require_tenant();
		$code = trim($code);
		if ($code === '') {
			return null;
		}
		$c = $db->real_escape_string($code);
		$tenant = tessera_sql_tenant_eq();
		$q = $db->query(
			"SELECT id, store, code, username, lugg_value, weight_name, weight_value, date_insert, trip_date,
				s_no, r_name, r_no, amount, initial, bus, account, detail, due, scanned, question,
				amount_remain, status, paid, to_be, answer, payment_status, paid_remain, to_be_remain
			 FROM parcel_info WHERE {$tenant} AND code='{$c}' LIMIT 1"
		);
		if (!$q) {
			return null;
		}
		$row = $q->fetch_assoc();
		return $row ?: null;
	}
}

if (!function_exists('tessera_cargo_load_lookup')) {
	/**
	 * Authenticate a luggage code for loading onto a manifest.
	 *
	 * @return array{ok:bool,error?:string,parcel?:array<string,mixed>}
	 */
	function tessera_cargo_load_lookup(mysqli $db, string $code, string $vehicle, string $date): array
	{
		tessera_cargo_load_require_tenant();
		$code = trim($code);
		$vehicle = trim($vehicle);
		$date = trim($date);
		if ($code === '') {
			return ['ok' => false, 'error' => 'Enter a luggage code'];
		}
		if ($vehicle === '' || $date === '') {
			return ['ok' => false, 'error' => 'Manifest vehicle and date are required'];
		}

		$tenant = tessera_sql_tenant_eq();
		$v = $db->real_escape_string($vehicle);
		$d = $db->real_escape_string($date);
		$mq = $db->query(
			"SELECT Name, date, disable2 FROM vehicle_name
			 WHERE {$tenant} AND Name='{$v}' AND date='{$d}' AND (disable2='' OR disable2 IS NULL) LIMIT 1"
		);
		if (!$mq || !$mq->fetch_assoc()) {
			return ['ok' => false, 'error' => 'Manifest is locked or not found'];
		}

		$row = tessera_cargo_load_parcel_row($db, $code);
		if ($row === null) {
			return ['ok' => false, 'error' => 'Luggage not found'];
		}

		$scanned = (int) tessera_cargo_load_num($row['scanned'] ?? 0);
		if ($scanned <= 0) {
			$trip = (string) ($row['trip_date'] ?? '');
			$bus = (string) ($row['bus'] ?? '');
			$when = $trip !== '' ? date('dS-m-Y', strtotime($trip) ?: time()) : '—';
			return [
				'ok' => false,
				'error' => 'Fully dispatched — loaded on ' . $when . ($bus !== '' ? ' bus ' . $bus : ''),
			];
		}

		$status = (string) ($row['status'] ?? '');
		$meta = tessera_cargo_load_status_meta($status);
		$qty = (int) tessera_cargo_load_num($row['question'] ?? 0);
		$paid = tessera_cargo_load_num($row['paid'] ?? 0);
		$toBe = tessera_cargo_load_num($row['to_be'] ?? 0);
		$amount = tessera_cargo_load_num($row['amount'] ?? 0);
		$paidRemain = tessera_cargo_load_num($row['paid_remain'] ?? 0);
		$toBeRemain = tessera_cargo_load_num($row['to_be_remain'] ?? 0);
		$store = (string) ($row['store'] ?? '');

		return [
			'ok' => true,
			'parcel' => [
				'id' => (int) ($row['id'] ?? 0),
				'code' => (string) ($row['code'] ?? ''),
				'status' => $status,
				'status_badge' => $meta['badge'],
				'status_label' => $meta['label'],
				'qty' => $qty,
				'qty_in_store' => $scanned,
				'paid' => $paid,
				'to_be' => $toBe,
				'amount' => $amount,
				'paid_remain' => $paidRemain,
				'to_be_remain' => $toBeRemain,
				'currency' => (string) ($row['initial'] ?? ''),
				'route' => (string) ($row['account'] ?? ''),
				'receiver' => (string) ($row['r_name'] ?? ''),
				'store' => $store,
				'partial' => $store === 'half_out',
				'detail' => (string) ($row['detail'] ?? ''),
				/* Raw snapshot for load commit (mirrors legacy sessionStorage parcel_info). */
				'snapshot' => [
					'store' => $store,
					'id' => (int) ($row['id'] ?? 0),
					'code' => (string) ($row['code'] ?? ''),
					'username' => (string) ($row['username'] ?? ''),
					'lugg_value' => $row['lugg_value'] ?? '',
					'weight_name' => (string) ($row['weight_name'] ?? ''),
					'weight_value' => (string) ($row['weight_value'] ?? ''),
					'date_insert' => (string) ($row['date_insert'] ?? ''),
					'trip_date' => (string) ($row['trip_date'] ?? ''),
					's_no' => (string) ($row['s_no'] ?? ''),
					'r_name' => (string) ($row['r_name'] ?? ''),
					'r_no' => (string) ($row['r_no'] ?? ''),
					'amount' => $amount,
					'initial' => (string) ($row['initial'] ?? ''),
					'bus' => (string) ($row['bus'] ?? ''),
					'account' => (string) ($row['account'] ?? ''),
					'detail' => (string) ($row['detail'] ?? ''),
					'due' => tessera_cargo_load_num($row['due'] ?? 0),
					'scanned' => $scanned,
					'question' => $qty,
					'amount_remain' => tessera_cargo_load_num($row['amount_remain'] ?? 0),
					'status' => $status,
					'paid' => $paid,
					'to_be' => $toBe,
					'paid_remain' => $paidRemain,
					'to_be_remain' => $toBeRemain,
				],
			],
		];
	}
}

if (!function_exists('tessera_cargo_load_exchange_rate')) {
	function tessera_cargo_load_exchange_rate(mysqli $db, string $from, string $to): float
	{
		$from = trim($from);
		$to = trim($to);
		if ($from === '' || $to === '' || $from === $to) {
			return 1.0;
		}
		$a = $db->real_escape_string($from);
		$b = $db->real_escape_string($to);
		$q = $db->query(
			"SELECT value FROM currency_value WHERE first_point='{$a}' AND second_point='{$b}' LIMIT 1"
		);
		if ($q && ($row = $q->fetch_assoc())) {
			$v = tessera_cargo_load_num($row['value'] ?? 1);
			return $v > 0 ? $v : 1.0;
		}
		return 1.0;
	}
}

if (!function_exists('tessera_cargo_load_validate')) {
	/**
	 * Port of legacy load_cargo.php varification().
	 *
	 * @return array{ok:bool,error?:string,code?:string}
	 */
	function tessera_cargo_load_validate(
		float $qty,
		float $scanned,
		float $paid,
		float $toBe,
		float $paidRemain,
		float $toBeRemain,
		string $status
	): array {
		if ($qty > $scanned) {
			return ['ok' => false, 'code' => 'EE001', 'error' => 'Load quantity is greater than quantity in store'];
		}
		if ($qty <= 0) {
			return ['ok' => false, 'code' => 'EE002', 'error' => 'Load quantity cannot be zero'];
		}
		if ($paid > $paidRemain) {
			return ['ok' => false, 'code' => 'EE007', 'error' => 'Paid amount is higher than remaining paid'];
		}
		if (($qty < $scanned) && ($paid == $paidRemain) && ($status === 'paid')) {
			return ['ok' => false, 'code' => 'EE009', 'error' => 'Quantity does not match remaining paid amount'];
		}
		if (($qty == $scanned) && ($paid < $paidRemain)) {
			return ['ok' => false, 'code' => 'EE010', 'error' => 'Quantity does not match remaining paid amount'];
		}
		if ($toBe > $toBeRemain) {
			return ['ok' => false, 'code' => 'EE005', 'error' => 'To be paid is higher than remaining to be paid'];
		}
		if (($qty == $scanned) && ($toBe < $toBeRemain)) {
			return ['ok' => false, 'code' => 'EE006', 'error' => 'To be paid does not match remaining to be paid'];
		}
		if (($qty < $scanned) && ($toBe == $toBeRemain) && ($status === 'To be Payed')) {
			return ['ok' => false, 'code' => 'EE008', 'error' => 'Partial load requires a matching to-be-paid split'];
		}
		if (($qty < $scanned) && ($toBe == $toBeRemain) && ($paid == $paidRemain) && ($status === 'Due payment')) {
			return ['ok' => false, 'code' => 'EE011', 'error' => 'Partial load requires a matching paid / to-be-paid split'];
		}
		return ['ok' => true];
	}
}

if (!function_exists('tessera_cargo_load_commit')) {
	/**
	 * Load a parcel (or partial qty) onto a vehicle manifest.
	 *
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,code?:string,item?:array<string,mixed>}
	 */
	function tessera_cargo_load_commit(mysqli $db, string $username, array $input): array
	{
		tessera_cargo_load_require_tenant();
		date_default_timezone_set('Africa/Dar_es_Salaam');

		$code = trim((string) ($input['code'] ?? ''));
		$vehicle = trim((string) ($input['vehicle'] ?? ''));
		$tripDate = trim((string) ($input['date'] ?? ''));
		$qty = tessera_cargo_load_num($input['qty'] ?? 0);
		$toBe = tessera_cargo_load_num($input['to_be'] ?? 0);
		$paid = tessera_cargo_load_num($input['paid'] ?? 0);

		if ($code === '' || $vehicle === '' || $tripDate === '') {
			return ['ok' => false, 'code' => 'EE000', 'error' => 'Code, vehicle, and date are required'];
		}

		$lookup = tessera_cargo_load_lookup($db, $code, $vehicle, $tripDate);
		if (empty($lookup['ok']) || empty($lookup['parcel']['snapshot'])) {
			return ['ok' => false, 'code' => 'EE000', 'error' => (string) ($lookup['error'] ?? 'Luggage not loadable')];
		}
		$parcel = $lookup['parcel']['snapshot'];
		$status = (string) ($parcel['status'] ?? '');
		$scanned = tessera_cargo_load_num($parcel['scanned'] ?? 0);
		$paidRemain = tessera_cargo_load_num($parcel['paid_remain'] ?? 0);
		$toBeRemain = tessera_cargo_load_num($parcel['to_be_remain'] ?? 0);

		$check = tessera_cargo_load_validate($qty, $scanned, $paid, $toBe, $paidRemain, $toBeRemain, $status);
		if (empty($check['ok'])) {
			return $check;
		}

		$tenant = tessera_sql_tenant_eq();
		$v = $db->real_escape_string($vehicle);
		$d = $db->real_escape_string($tripDate);
		$mq = $db->query(
			"SELECT to_pay, paid, due, currency FROM vehicle_name
			 WHERE {$tenant} AND Name='{$v}' AND date='{$d}' AND (disable2='' OR disable2 IS NULL) LIMIT 1"
		);
		$manifest = $mq ? $mq->fetch_assoc() : null;
		if (!$manifest) {
			return ['ok' => false, 'code' => 'EE000', 'error' => 'Manifest is locked or not found'];
		}

		$parcelCurrency = (string) ($parcel['initial'] ?? '');
		$manifestCurrency = (string) ($manifest['currency'] ?? '');
		$rate = tessera_cargo_load_exchange_rate($db, $parcelCurrency, $manifestCurrency);
		$finalToPay = $toBe * $rate;
		$finalPaid = $paid * $rate;
		$finalTotal = $finalPaid + $finalToPay;

		$manToPay = tessera_cargo_load_num($manifest['to_pay'] ?? 0) + $finalToPay;
		$manPaid = tessera_cargo_load_num($manifest['paid'] ?? 0) + $finalPaid;
		$manDue = tessera_cargo_load_num($manifest['due'] ?? 0) + $finalTotal;

		$qtyRemain = $scanned - $qty;
		$storeStatus = $qtyRemain == 0.0 ? 'out' : 'half_out';
		$toBeLeft = $toBeRemain - $toBe;
		$paidLeft = $paidRemain - $paid;
		$amount = $toBe + $paid;

		$dateInsert = date('Y-m-d') . ' at ' . date('h:i A');
		$c = $db->real_escape_string($code);
		$detail = $db->real_escape_string((string) ($parcel['detail'] ?? ''));
		$rName = $db->real_escape_string((string) ($parcel['r_name'] ?? ''));
		$account = $db->real_escape_string((string) ($parcel['account'] ?? ''));
		$parcelStatus = $db->real_escape_string($status);
		$currency = $db->real_escape_string($parcelCurrency);
		$uploader = $db->real_escape_string($username);
		$parcelDate = $db->real_escape_string((string) ($parcel['date_insert'] ?? ''));
		$weightName = $db->real_escape_string((string) ($parcel['weight_name'] ?? ''));
		$weightValue = $db->real_escape_string((string) ($parcel['weight_value'] ?? ''));
		$luggValue = $db->real_escape_string((string) ($parcel['lugg_value'] ?? ''));
		$senderNo = $db->real_escape_string((string) ($parcel['s_no'] ?? ''));
		$receiverNo = $db->real_escape_string((string) ($parcel['r_no'] ?? ''));
		$dateAdd = $db->real_escape_string($dateInsert);
		$parcelUploader = $db->real_escape_string((string) ($parcel['username'] ?? ''));
		$qtyOrig = tessera_cargo_load_num($parcel['question'] ?? 0);
		$tenantId = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 1);

		$db->begin_transaction();
		try {
			$updManifest = $db->query(
				"UPDATE vehicle_name SET to_pay={$manToPay}, paid={$manPaid}, due={$manDue}
				 WHERE {$tenant} AND Name='{$v}' AND date='{$d}' LIMIT 1"
			);
			if (!$updManifest) {
				throw new RuntimeException('Failed to update manifest totals');
			}

			$storeEsc = $db->real_escape_string($storeStatus);
			$updParcel = $db->query(
				"UPDATE parcel_info SET store='{$storeEsc}', scanned={$qtyRemain}, quantity_remain={$qtyRemain},
					paid_remain={$paidLeft}, to_be_remain={$toBeLeft}, bus='{$v}', trip_date='{$d}'
				 WHERE {$tenant} AND code='{$c}' LIMIT 1"
			);
			if (!$updParcel) {
				throw new RuntimeException('Failed to update parcel store state');
			}

			$ins = $db->query(
				"INSERT INTO manifest (
					code, detail, username, parcel_route, amount, trip_date, bus, paid, to_be, status, currency,
					uploader, q_left, quantity, parcel_date, quantity_insert, weight_name, weight_value, lugg_value,
					sender_no, reciever_no, date_insert, parcel_uploader, tenant_id
				) VALUES (
					'{$c}', '{$detail}', '{$rName}', '{$account}', {$amount}, '{$d}', '{$v}', {$paid}, {$toBe},
					'{$parcelStatus}', '{$currency}', '{$uploader}', {$qtyRemain}, {$qtyOrig}, '{$parcelDate}', {$qty},
					'{$weightName}', '{$weightValue}', '{$luggValue}', '{$senderNo}', '{$receiverNo}', '{$dateAdd}',
					'{$parcelUploader}', {$tenantId}
				)"
			);
			if (!$ins) {
				throw new RuntimeException('Failed to insert manifest line');
			}
			$newId = (int) $db->insert_id;
			$db->commit();
		} catch (Throwable $e) {
			$db->rollback();
			return ['ok' => false, 'code' => 'EE000', 'error' => $e->getMessage()];
		}

		return [
			'ok' => true,
			'item' => [
				'id' => $newId,
				'code' => $code,
				'qty' => $qty,
				'paid' => $paid,
				'to_be' => $toBe,
				'amount' => $amount,
				'qty_left' => $qtyRemain,
				'store' => $storeStatus,
			],
		];
	}
}

if (!function_exists('tessera_cargo_load_items')) {
	/**
	 * Loaded lines on a bus+date manifest.
	 *
	 * @param array{q?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array{paid:float,to_be:float,amount:float,count:int}}
	 */
	function tessera_cargo_load_items(
		mysqli $db,
		string $vehicle,
		string $date,
		array $filters,
		int $page,
		int $perPage
	): array {
		tessera_cargo_load_require_tenant();
		$tenant = tessera_sql_tenant_eq();
		$v = $db->real_escape_string(trim($vehicle));
		$d = $db->real_escape_string(trim($date));
		$parts = ["{$tenant}", "trip_date='{$d}'", "bus='{$v}'"];

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(code LIKE '{$like}' OR username LIKE '{$like}' OR status LIKE '{$like}')";
		}
		$where = implode(' AND ', $parts);

		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM manifest WHERE {$where}");
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$total = (int) ($cr['c'] ?? 0);
		}

		$paidSum = 0.0;
		$toBeSum = 0.0;
		$sq = $db->query("SELECT COALESCE(SUM(paid),0) AS p, COALESCE(SUM(to_be),0) AS t FROM manifest WHERE {$where}");
		if ($sq && ($sr = $sq->fetch_assoc())) {
			$paidSum = tessera_cargo_load_num($sr['p'] ?? 0);
			$toBeSum = tessera_cargo_load_num($sr['t'] ?? 0);
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$items = [];
		$locked = false;
		$lq = $db->query(
			"SELECT disable2 FROM vehicle_name WHERE {$tenant} AND Name='{$v}' AND date='{$d}' LIMIT 1"
		);
		if ($lq && ($lr = $lq->fetch_assoc())) {
			$locked = ((string) ($lr['disable2'] ?? '')) === 'disabled';
		}

		$sql = "SELECT id, code, username, quantity_insert, to_be, paid, amount, status, currency, quantity, q_left
			FROM manifest WHERE {$where} ORDER BY id DESC LIMIT {$offset}, {$perPage}";
		$rq = $db->query($sql);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$status = (string) ($row['status'] ?? '');
				$meta = tessera_cargo_load_status_meta($status);
				$items[] = [
					'id' => (int) ($row['id'] ?? 0),
					'code' => (string) ($row['code'] ?? ''),
					'receiver' => (string) ($row['username'] ?? ''),
					'qty' => tessera_cargo_load_num($row['quantity_insert'] ?? 0),
					'to_be' => tessera_cargo_load_num($row['to_be'] ?? 0),
					'paid' => tessera_cargo_load_num($row['paid'] ?? 0),
					'amount' => tessera_cargo_load_num($row['amount'] ?? 0),
					'status' => $status,
					'status_badge' => $meta['badge'],
					'status_label' => $meta['label'],
					'currency' => (string) ($row['currency'] ?? ''),
					'can_remove' => !$locked,
					'view_url' => '/cargo/view.php?u=' . (int) ($row['id'] ?? 0),
				];
			}
		}

		return [
			'items' => $items,
			'total' => $total,
			'summary' => [
				'paid' => $paidSum,
				'to_be' => $toBeSum,
				'amount' => $paidSum + $toBeSum,
				'count' => $total,
			],
		];
	}
}

if (!function_exists('tessera_cargo_load_remove')) {
	/**
	 * Unload a manifest line back to store (classic delete_manifest.php).
	 * @return array{ok:bool,error?:string,id?:int}
	 */
	function tessera_cargo_load_remove(mysqli $db, string $username, int $id): array
	{
		unset($username);
		tessera_cargo_load_require_tenant();
		if ($id < 1) {
			return ['ok' => false, 'error' => 'Line id is required'];
		}
		$tenant = tessera_sql_tenant_eq();
		$sel = $db->query(
			"SELECT id, currency, code, q_left, to_be, parcel_route, paid, bus, trip_date, quantity_insert
			 FROM manifest WHERE id={$id} AND {$tenant} LIMIT 1"
		);
		$row = $sel ? $sel->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Loaded line not found'];
		}

		$bus = (string) ($row['bus'] ?? '');
		$tripDate = (string) ($row['trip_date'] ?? '');
		$code = (string) ($row['code'] ?? '');
		$toBe = tessera_cargo_load_num($row['to_be'] ?? 0);
		$paid = tessera_cargo_load_num($row['paid'] ?? 0);
		$qtyInsert = tessera_cargo_load_num($row['quantity_insert'] ?? 0);
		$currency = (string) ($row['currency'] ?? '');

		$v = $db->real_escape_string($bus);
		$d = $db->real_escape_string($tripDate);
		$vq = $db->query(
			"SELECT to_pay, paid, due, currency, disable2 FROM vehicle_name
			 WHERE {$tenant} AND Name='{$v}' AND date='{$d}' LIMIT 1"
		);
		$vehicle = $vq ? $vq->fetch_assoc() : null;
		if (!$vehicle) {
			return ['ok' => false, 'error' => 'Manifest not found'];
		}
		if ((string) ($vehicle['disable2'] ?? '') === 'disabled') {
			return ['ok' => false, 'error' => 'Manifest is locked'];
		}

		$manifestCurrency = (string) ($vehicle['currency'] ?? '');
		$rate = tessera_cargo_load_exchange_rate($db, $currency, $manifestCurrency);
		$manifestPaid = $paid * $rate;
		$manifestToBe = $toBe * $rate;

		$c = $db->real_escape_string($code);
		$pq = $db->query(
			"SELECT paid_remain, to_be_remain, scanned, question FROM parcel_info
			 WHERE code='{$c}' AND {$tenant} LIMIT 1"
		);
		$parcel = $pq ? $pq->fetch_assoc() : null;
		if (!$parcel) {
			return ['ok' => false, 'error' => 'Parcel not found'];
		}

		$newToBe = tessera_cargo_load_num($parcel['to_be_remain'] ?? 0) + $toBe;
		$newPaid = tessera_cargo_load_num($parcel['paid_remain'] ?? 0) + $paid;
		$newQty = tessera_cargo_load_num($parcel['scanned'] ?? 0) + $qtyInsert;
		$origQty = tessera_cargo_load_num($parcel['question'] ?? 0);
		$store = $origQty == $newQty ? 'store' : 'half_out';

		$busToPay = tessera_cargo_load_num($vehicle['to_pay'] ?? 0) - $manifestToBe;
		$busPaid = tessera_cargo_load_num($vehicle['paid'] ?? 0) - $manifestPaid;
		$busDue = $busToPay + $busPaid;
		$storeEsc = $db->real_escape_string($store);

		$db->begin_transaction();
		try {
			if (!$db->query(
				"UPDATE parcel_info SET scanned={$newQty}, quantity_remain={$newQty},
					paid_remain={$newPaid}, to_be_remain={$newToBe}, store='{$storeEsc}'
				 WHERE code='{$c}' AND {$tenant} LIMIT 1"
			)) {
				throw new RuntimeException('Could not restore parcel');
			}
			if (!$db->query(
				"UPDATE vehicle_name SET to_pay={$busToPay}, paid={$busPaid}, due={$busDue}
				 WHERE {$tenant} AND Name='{$v}' AND date='{$d}' LIMIT 1"
			)) {
				throw new RuntimeException('Could not update manifest totals');
			}
			$db->query("INSERT INTO manifest_deleted (id2, code) VALUES ({$id}, '{$c}')");
			if (!$db->query("DELETE FROM manifest WHERE id={$id} AND {$tenant} LIMIT 1")) {
				throw new RuntimeException('Could not remove loaded line');
			}
			$db->commit();
		} catch (Throwable $e) {
			$db->rollback();
			return ['ok' => false, 'error' => $e->getMessage()];
		}

		return ['ok' => true, 'id' => $id];
	}
}
