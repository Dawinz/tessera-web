<?php
/**
 * Cargo ops helpers: home KPIs, my luggages, deletes audit, luggage edit/status, vehicle report.
 * Charge variants live in tessera_cargo_user_charges.php.
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_ops_esc')) {
	function tessera_cargo_ops_esc(mysqli $db, string $value): string
	{
		return $db->real_escape_string($value);
	}
}

if (!function_exists('tessera_cargo_ops_date')) {
	function tessera_cargo_ops_date(string $raw, string $fallback): string
	{
		$raw = trim($raw);
		return preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw) ? $raw : $fallback;
	}
}

if (!function_exists('tessera_cargo_ops_date_label')) {
	function tessera_cargo_ops_date_label(string $raw): string
	{
		$ts = strtotime($raw);
		return $ts ? date('d M Y', $ts) : $raw;
	}
}

if (!function_exists('tessera_cargo_ops_decode')) {
	function tessera_cargo_ops_decode(string $stored): string
	{
		$stored = trim($stored);
		if ($stored === '') {
			return '';
		}
		$decoded = base64_decode($stored, true);
		if ($decoded !== false && $decoded !== '' && preg_match('//u', $decoded)
			&& !preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $decoded)) {
			return $decoded;
		}
		return $stored;
	}
}

if (!function_exists('tessera_cargo_ops_assigned')) {
	/** @return list<string> */
	function tessera_cargo_ops_assigned(mysqli $db, string $username): array
	{
		if (!function_exists('tessera_cargo_luggages_assigned_routes')) {
			require_once __DIR__ . '/tessera_cargo_luggages_list.php';
		}
		return tessera_cargo_luggages_assigned_routes($db, $username);
	}
}

if (!function_exists('tessera_cargo_ops_home')) {
	/**
	 * Monthly charge KPIs for assigned routes (classic Cargo home).
	 *
	 * @return array<string,mixed>
	 */
	function tessera_cargo_ops_home(mysqli $db, string $username, string $route): array
	{
		$assigned = tessera_cargo_ops_assigned($db, $username);
		$route = trim($route);
		if ($route !== '' && $assigned !== [] && !in_array($route, $assigned, true)) {
			$route = '';
		}
		if ($route === '' && $assigned !== []) {
			$route = $assigned[0];
		}

		$monthKey = date('m-Y');
		$monthLabel = date('F Y');
		$monthEsc = tessera_cargo_ops_esc($db, $monthKey);

		$locationSql = '';
		if ($assigned !== []) {
			$ors = [];
			foreach ($assigned as $name) {
				$ors[] = "location='" . tessera_cargo_ops_esc($db, $name) . "'";
			}
			$locationSql = ' AND (' . implode(' OR ', $ors) . ')';
		}

		$routes = [];
		$rq = $db->query(
			"SELECT location,
			 COALESCE(SUM(paid),0) AS paid,
			 COALESCE(SUM(to_be),0) AS to_be,
			 COALESCE(SUM(no_paid),0) AS no_paid,
			 COALESCE(SUM(no_to_be),0) AS no_to_be,
			 COALESCE(SUM(no_due),0) AS no_due
			 FROM monthly_charges
			 WHERE date_insert='{$monthEsc}' {$locationSql}
			 GROUP BY location
			 ORDER BY (COALESCE(SUM(paid),0)+COALESCE(SUM(to_be),0)) DESC
			 LIMIT 24"
		);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$loc = trim((string) ($row['location'] ?? ''));
				if ($loc === '') {
					continue;
				}
				$paid = (float) ($row['paid'] ?? 0);
				$toBe = (float) ($row['to_be'] ?? 0);
				$receipts = (int) ($row['no_paid'] ?? 0) + (int) ($row['no_to_be'] ?? 0) + (int) ($row['no_due'] ?? 0);
				$routes[] = [
					'route' => $loc,
					'paid' => $paid,
					'to_be' => $toBe,
					'total' => $paid + $toBe,
					'receipts' => $receipts,
				];
			}
		}

		$kpi = ['total' => 0.0, 'paid' => 0.0, 'to_be' => 0.0, 'receipts' => 0];
		if ($route !== '') {
			foreach ($routes as $row) {
				if ($row['route'] === $route) {
					$kpi = [
						'total' => (float) $row['total'],
						'paid' => (float) $row['paid'],
						'to_be' => (float) $row['to_be'],
						'receipts' => (int) $row['receipts'],
					];
					break;
				}
			}
			if ($kpi['total'] == 0.0 && $kpi['receipts'] === 0) {
				$locEsc = tessera_cargo_ops_esc($db, $route);
				$kq = $db->query(
					"SELECT COALESCE(SUM(paid),0) AS paid, COALESCE(SUM(to_be),0) AS to_be,
					 COALESCE(SUM(no_paid),0) AS no_paid, COALESCE(SUM(no_to_be),0) AS no_to_be,
					 COALESCE(SUM(no_due),0) AS no_due
					 FROM monthly_charges WHERE date_insert='{$monthEsc}' AND location='{$locEsc}'"
				);
				if ($kq && ($kr = $kq->fetch_assoc())) {
					$paid = (float) ($kr['paid'] ?? 0);
					$toBe = (float) ($kr['to_be'] ?? 0);
					$kpi = [
						'total' => $paid + $toBe,
						'paid' => $paid,
						'to_be' => $toBe,
						'receipts' => (int) ($kr['no_paid'] ?? 0) + (int) ($kr['no_to_be'] ?? 0) + (int) ($kr['no_due'] ?? 0),
					];
				}
			}
		} else {
			foreach ($routes as $row) {
				$kpi['total'] += (float) $row['total'];
				$kpi['paid'] += (float) $row['paid'];
				$kpi['to_be'] += (float) $row['to_be'];
				$kpi['receipts'] += (int) $row['receipts'];
			}
		}

		$daily = [];
		if ($route !== '') {
			$locEsc = tessera_cargo_ops_esc($db, $route);
			$dq = $db->query(
				"SELECT date_insert,
				 COALESCE(SUM(no_paid),0) AS no_paid,
				 COALESCE(SUM(no_to_be),0) AS no_to_be,
				 COALESCE(SUM(no_due),0) AS no_due
				 FROM daily_charges
				 WHERE location='{$locEsc}'
				 GROUP BY date_insert
				 ORDER BY date_insert DESC
				 LIMIT 30"
			);
			if ($dq) {
				while ($row = $dq->fetch_assoc()) {
					$day = (string) ($row['date_insert'] ?? '');
					$daily[] = [
						'date' => $day,
						'date_label' => tessera_cargo_ops_date_label($day),
						'paid_receipts' => (int) ($row['no_paid'] ?? 0),
						'to_be_receipts' => (int) ($row['no_to_be'] ?? 0),
						'due_receipts' => (int) ($row['no_due'] ?? 0),
					];
				}
			}
		}

		$routeOptions = $assigned !== [] ? $assigned : array_map(static function ($row) {
			return (string) $row['route'];
		}, $routes);

		return [
			'month' => $monthKey,
			'month_label' => $monthLabel,
			'route' => $route,
			'routes' => $routeOptions,
			'kpis' => $kpi,
			'by_route' => $routes,
			'daily' => $daily,
		];
	}
}

if (!function_exists('tessera_cargo_ops_my_luggages')) {
	/**
	 * @param array{q?:string,route?:string,store?:string,date_from?:string,date_to?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int}
	 */
	function tessera_cargo_ops_my_luggages(mysqli $db, string $username, array $filters, int $page, int $perPage): array
	{
		if (!function_exists('tessera_cargo_luggages_list')) {
			require_once __DIR__ . '/tessera_cargo_luggages_list.php';
		}
		$filters['owner'] = $username;
		$payload = tessera_cargo_luggages_list($db, $username, $filters, $page, $perPage);
		foreach ($payload['items'] as &$item) {
			$id = (int) ($item['id'] ?? 0);
			$item['view_url'] = '/operator/app.php?module=cargo&page=view.php&u=' . $id;
			$item['edit_url'] = '/operator/app.php?module=cargo&page=update_luggage.php&u=' . $id;
		}
		unset($item);
		return $payload;
	}
}

if (!function_exists('tessera_cargo_ops_deleted')) {
	/**
	 * @return array{items:list<array<string,mixed>>,total:int}
	 */
	function tessera_cargo_ops_deleted(mysqli $db, string $q, int $page, int $perPage): array
	{
		$where = '1';
		$q = trim($q);
		if ($q !== '') {
			$like = '%' . tessera_cargo_ops_esc($db, $q) . '%';
			$where = "(s_name LIKE '{$like}' OR r_name LIKE '{$like}' OR code LIKE '{$like}' OR notification LIKE '{$like}' OR what_changed LIKE '{$like}')";
		}

		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM notification WHERE {$where}");
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$total = (int) ($cr['c'] ?? 0);
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$items = [];
		$list = $db->query("SELECT * FROM notification WHERE {$where} ORDER BY id DESC LIMIT {$offset}, {$perPage}");
		if ($list) {
			while ($row = $list->fetch_assoc()) {
				$note = (string) ($row['notification'] ?? '');
				$items[] = [
					'id' => (int) ($row['id'] ?? 0),
					'sender_name' => trim((string) ($row['s_name'] ?? '')),
					'receiver_name' => trim((string) ($row['r_name'] ?? '')),
					'code' => (string) ($row['code'] ?? ''),
					'date' => (string) ($row['date'] ?? ''),
					'amount' => (float) ($row['amount'] ?? 0),
					'paid' => (float) ($row['paid'] ?? 0),
					'to_pay' => (float) ($row['topay'] ?? 0),
					'what_changed' => trim(strip_tags((string) ($row['what_changed'] ?? ''))),
					'notification' => $note,
					'uploader' => (string) ($row['uploader'] ?? ''),
					'actor' => (string) ($row['deleted'] ?? ''),
					'when' => (string) ($row['deleted_date'] ?? ''),
					'status' => (string) ($row['status'] ?? ''),
				];
			}
		}

		return ['items' => $items, 'total' => $total];
	}
}

if (!function_exists('tessera_cargo_ops_parcel_row')) {
	/** @return array<string,mixed>|null */
	function tessera_cargo_ops_parcel_row(mysqli $db, int $id, string $code = ''): ?array
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$tenant = tessera_sql_tenant_eq();
		if ($id > 0) {
			$sql = "SELECT * FROM parcel_info WHERE id={$id} AND {$tenant} LIMIT 1";
		} elseif ($code !== '') {
			$codeEsc = tessera_cargo_ops_esc($db, $code);
			$sql = "SELECT * FROM parcel_info WHERE code='{$codeEsc}' AND {$tenant} ORDER BY id DESC LIMIT 1";
		} else {
			return null;
		}
		$q = $db->query($sql);
		if (!$q) {
			return null;
		}
		$row = $q->fetch_assoc();
		return $row ?: null;
	}
}

if (!function_exists('tessera_cargo_ops_parcel_public')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_cargo_ops_parcel_public(array $row): array
	{
		$id = (int) ($row['id'] ?? 0);
		$code = (string) ($row['code'] ?? '');
		return [
			'id' => $id,
			'code' => $code,
			'sender_name' => (string) ($row['s_name'] ?? ''),
			'sender_phone' => (string) ($row['s_no'] ?? ''),
			'receiver_name' => (string) ($row['r_name'] ?? ''),
			'receiver_phone' => (string) ($row['r_no'] ?? ''),
			'route' => (string) ($row['account'] ?? ''),
			'status' => (string) ($row['status'] ?? ''),
			'amount' => (float) ($row['amount'] ?? 0),
			'paid' => (float) ($row['paid'] ?? 0),
			'to_be' => (float) ($row['to_be'] ?? 0),
			'due' => (float) ($row['due'] ?? 0),
			'loan' => (float) ($row['loan'] ?? 0),
			'quantity' => (string) ($row['question'] ?? '1'),
			'scanned' => (string) ($row['scanned'] ?? ''),
			'store' => (string) ($row['store'] ?? ''),
			'weight_name' => (string) ($row['weight_name'] ?? 'kg'),
			'weight_value' => (string) ($row['weight_value'] ?? ''),
			'luggage_value' => (string) ($row['lugg_value'] ?? ''),
			'detail' => tessera_cargo_ops_decode((string) ($row['detail'] ?? '')),
			'date_insert' => (string) ($row['date_insert'] ?? ''),
			'date_label' => tessera_cargo_ops_date_label((string) ($row['date_insert'] ?? '')),
			'time' => trim((string) ($row['time'] ?? '')),
			'uploader' => (string) ($row['uploader'] ?? ''),
			'print_url' => '/cargo/print_ticket_mulitple.php?code=' . rawurlencode($code),
			'edit_url' => '/operator/app.php?module=cargo&page=update_luggage.php&u=' . $id,
			'view_url' => '/operator/app.php?module=cargo&page=view.php&u=' . $id,
		];
	}
}

if (!function_exists('tessera_cargo_ops_on_manifest')) {
	function tessera_cargo_ops_on_manifest(mysqli $db, string $code): bool
	{
		if ($code === '') {
			return false;
		}
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$codeEsc = tessera_cargo_ops_esc($db, $code);
		$tenant = tessera_sql_tenant_eq();
		$q = $db->query("SELECT id FROM manifest WHERE code='{$codeEsc}' AND {$tenant} LIMIT 1");
		return $q && $q->num_rows > 0;
	}
}

if (!function_exists('tessera_cargo_ops_luggage_update')) {
	/**
	 * Classic update_registered_luggage.php parity: block when the code is on a manifest.
	 *
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,status?:int,item?:array<string,mixed>}
	 */
	function tessera_cargo_ops_luggage_update(mysqli $db, string $actor, array $input): array
	{
		$id = (int) ($input['id'] ?? 0);
		$row = tessera_cargo_ops_parcel_row($db, $id);
		if (!$row) {
			return ['ok' => false, 'error' => 'Luggage not found', 'status' => 404];
		}

		$code = (string) ($row['code'] ?? '');
		if (tessera_cargo_ops_on_manifest($db, $code)) {
			return ['ok' => false, 'error' => 'This luggage is on a manifest. Remove it before editing.', 'status' => 409];
		}

		$sender = trim((string) ($input['sender_name'] ?? ''));
		$receiver = trim((string) ($input['receiver_name'] ?? ''));
		$route = trim((string) ($input['route'] ?? ''));
		$status = trim((string) ($input['status'] ?? ''));
		$detail = trim((string) ($input['detail'] ?? ''));
		$amountRaw = preg_replace('/[^0-9.]+/', '', (string) ($input['amount'] ?? '')) ?? '';
		$qtyRaw = preg_replace('/[^0-9]+/', '', (string) ($input['quantity'] ?? '')) ?? '';
		$dueRaw = preg_replace('/[^0-9.]+/', '', (string) ($input['due'] ?? '0')) ?? '0';
		$loanRaw = preg_replace('/[^0-9.]+/', '', (string) ($input['loan'] ?? '0')) ?? '0';

		if ($sender === '' || $receiver === '') {
			return ['ok' => false, 'error' => 'Sender and receiver names are required', 'status' => 422];
		}
		if ($route === '') {
			return ['ok' => false, 'error' => 'Route is required', 'status' => 422];
		}
		if (!in_array($status, ['paid', 'To be Payed', 'Due payment'], true)) {
			return ['ok' => false, 'error' => 'Choose a payment status', 'status' => 422];
		}
		if ($amountRaw === '' || (float) $amountRaw < 0) {
			return ['ok' => false, 'error' => 'Amount is required', 'status' => 422];
		}
		if ($qtyRaw === '' || (int) $qtyRaw < 1) {
			return ['ok' => false, 'error' => 'Quantity is required', 'status' => 422];
		}
		if ($detail === '') {
			return ['ok' => false, 'error' => 'Detail is required', 'status' => 422];
		}

		$amount = (float) $amountRaw;
		$duePaid = (float) $dueRaw;
		if ($duePaid > $amount) {
			$duePaid = $amount;
		}
		$qty = (int) $qtyRaw;
		$loan = (float) $loanRaw;
		$senderPhone = trim((string) ($input['sender_phone'] ?? ''));
		$receiverPhone = trim((string) ($input['receiver_phone'] ?? ''));
		$weightName = trim((string) ($input['weight_name'] ?? 'kg'));
		if (!in_array($weightName, ['kg', 'g'], true)) {
			$weightName = 'kg';
		}
		$weightValue = trim((string) ($input['weight_value'] ?? ''));
		$luggageValue = trim((string) ($input['luggage_value'] ?? ''));

		$paid = 0.0;
		$toBe = $amount;
		$dueStore = '';
		if ($status === 'paid') {
			$paid = $amount;
			$toBe = 0.0;
		} elseif ($status === 'Due payment') {
			$paid = $duePaid;
			$toBe = max(0, $amount - $duePaid);
			$dueStore = (string) $duePaid;
		}

		$changes = [];
		$pairs = [
			'Sender' => [(string) ($row['s_name'] ?? ''), $sender],
			'Receiver' => [(string) ($row['r_name'] ?? ''), $receiver],
			'Route' => [(string) ($row['account'] ?? ''), $route],
			'Payment' => [(string) ($row['status'] ?? ''), $status],
			'Amount' => [(string) ($row['amount'] ?? ''), (string) $amount],
			'Quantity' => [(string) ($row['question'] ?? ''), (string) $qty],
		];
		foreach ($pairs as $label => $pair) {
			if ((string) $pair[0] !== (string) $pair[1]) {
				$changes[] = $label . ': ' . $pair[0] . ' → ' . $pair[1];
			}
		}
		$what = $changes === [] ? 'Saved without field changes' : implode('; ', $changes);

		$e = static function (string $v) use ($db): string {
			return tessera_cargo_ops_esc($db, $v);
		};
		$detailStored = $e(base64_encode($detail));
		$actorEsc = $e($actor);
		$editedNote = $e('Edited by ' . $actor);
		$tenant = tessera_sql_tenant_eq();

		$sql = "UPDATE parcel_info SET
			s_name='" . $e($sender) . "',
			s_no='" . $e($senderPhone) . "',
			r_name='" . $e($receiver) . "',
			r_no='" . $e($receiverPhone) . "',
			account='" . $e($route) . "',
			detail='{$detailStored}',
			status='" . $e($status) . "',
			amount='" . $e((string) $amount) . "',
			question='" . $e((string) $qty) . "',
			scanned='" . $e((string) $qty) . "',
			edited='{$actorEsc}',
			due='" . $e($dueStore) . "',
			to_be='" . $e((string) $toBe) . "',
			paid='" . $e((string) $paid) . "',
			paid_remain='" . $e((string) $paid) . "',
			to_be_remain='" . $e((string) $toBe) . "',
			quantity_remain='" . $e((string) $qty) . "',
			r_surname='{$editedNote}',
			updated='changed',
			loan='" . $e((string) $loan) . "',
			weight_name='" . $e($weightName) . "',
			weight_value='" . $e($weightValue) . "',
			lugg_value='" . $e($luggageValue) . "'
			WHERE id={$id} AND {$tenant} LIMIT 1";

		if (!$db->query($sql)) {
			return ['ok' => false, 'error' => 'Could not save luggage', 'status' => 500];
		}

		date_default_timezone_set('Africa/Dar_es_Salaam');
		$when = date('Y-m-d') . ' at ' . date('h:i A');
		$uploader = (string) ($row['uploader'] ?? '');
		$owner = (string) ($row['username'] ?? '');
		$db->query(
			"INSERT INTO notification
			(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,topay,paid,amount,uploader,date,username,notification,what_changed)
			VALUES (
				'Updated','{$actorEsc}','" . $e($when) . "','" . $e($sender) . "','" . $e($receiver) . "',
				'{$id}','" . $e($code) . "','" . $e((string) $qty) . "','" . $e((string) $toBe) . "',
				'" . $e((string) $paid) . "','" . $e((string) $amount) . "','" . $e($uploader) . "',
				'" . $e(date('Y-m-d')) . "','" . $e($owner) . "','Change','" . $e($what) . "'
			)"
		);

		$fresh = tessera_cargo_ops_parcel_row($db, $id);
		return [
			'ok' => true,
			'item' => $fresh ? tessera_cargo_ops_parcel_public($fresh) : tessera_cargo_ops_parcel_public($row),
		];
	}
}

if (!function_exists('tessera_cargo_ops_status')) {
	/**
	 * @return array{item:?array<string,mixed>,manifest:list<array<string,mixed>>}
	 */
	function tessera_cargo_ops_status(mysqli $db, int $id, string $code): array
	{
		$row = tessera_cargo_ops_parcel_row($db, $id, $code);
		if (!$row) {
			return ['item' => null, 'manifest' => []];
		}
		$item = tessera_cargo_ops_parcel_public($row);
		$codeEsc = tessera_cargo_ops_esc($db, (string) $item['code']);
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$tenant = tessera_sql_tenant_eq();
		$manifest = [];
		$mq = $db->query(
			"SELECT id, bus, trip_date, parcel_route, status, checked, paid, to_be, amount, quantity, q_left,
			 username_taken, timetaken, parcel_uploader
			 FROM manifest WHERE code='{$codeEsc}' AND {$tenant} ORDER BY id DESC LIMIT 20"
		);
		if ($mq) {
			while ($m = $mq->fetch_assoc()) {
				$manifest[] = [
					'id' => (int) ($m['id'] ?? 0),
					'bus' => (string) ($m['bus'] ?? ''),
					'trip_date' => (string) ($m['trip_date'] ?? ''),
					'date_label' => tessera_cargo_ops_date_label((string) ($m['trip_date'] ?? '')),
					'route' => (string) ($m['parcel_route'] ?? ''),
					'status' => (string) ($m['status'] ?? ''),
					'checked' => (string) ($m['checked'] ?? ''),
					'paid' => (float) ($m['paid'] ?? 0),
					'to_be' => (float) ($m['to_be'] ?? 0),
					'amount' => (float) ($m['amount'] ?? 0),
					'quantity' => (string) ($m['q_left'] ?? '') . '/' . (string) ($m['quantity'] ?? ''),
					'taken_by' => (string) ($m['username_taken'] ?? ''),
					'taken_at' => (string) ($m['timetaken'] ?? ''),
					'uploader' => (string) ($m['parcel_uploader'] ?? ''),
				];
			}
		}
		$item['on_manifest'] = $manifest !== [];
		return ['item' => $item, 'manifest' => $manifest];
	}
}

if (!function_exists('tessera_cargo_ops_vehicle_report')) {
	/**
	 * Trip header, route rollup, and manifest lines for vehicle_report_new / edit.
	 *
	 * @return array<string,mixed>
	 */
	function tessera_cargo_ops_vehicle_report(mysqli $db, string $date, string $bus, int $page, int $perPage): array
	{
		if (!function_exists('tessera_cargo_latest_trip')) {
			require_once dirname(__DIR__) . '/connect.php';
		}
		$date = trim($date);
		$bus = trim($bus);
		if ($date === '' || $bus === '') {
			$latest = function_exists('tessera_cargo_latest_trip') ? tessera_cargo_latest_trip($db) : null;
			if (is_array($latest)) {
				if ($date === '') {
					$date = (string) ($latest['date'] ?? '');
				}
				if ($bus === '') {
					$bus = (string) ($latest['Name'] ?? '');
				}
			}
		}

		$trips = [];
		$tq = $db->query(
			"SELECT Name, date, Driver, Conductor, code, Route
			 FROM vehicle_name
			 WHERE date >= '2020-01-01'
			 ORDER BY date DESC, Name ASC
			 LIMIT 40"
		);
		if ($tq) {
			while ($row = $tq->fetch_assoc()) {
				$trips[] = [
					'bus' => (string) ($row['Name'] ?? ''),
					'date' => (string) ($row['date'] ?? ''),
					'date_label' => tessera_cargo_ops_date_label((string) ($row['date'] ?? '')),
					'driver' => (string) ($row['Driver'] ?? ''),
					'conductor' => (string) ($row['Conductor'] ?? ''),
					'code' => (string) ($row['code'] ?? ''),
					'route' => (string) ($row['Route'] ?? ''),
				];
			}
		}

		$header = [
			'bus' => $bus,
			'date' => $date,
			'date_label' => tessera_cargo_ops_date_label($date),
			'driver' => '',
			'conductor' => '',
			'code' => '',
			'route' => '',
		];
		if ($date !== '' && $bus !== '') {
			$dEsc = tessera_cargo_ops_esc($db, $date);
			$bEsc = tessera_cargo_ops_esc($db, $bus);
			$hq = $db->query(
				"SELECT Name, date, Driver, Conductor, code, Route FROM vehicle_name
				 WHERE date='{$dEsc}' AND Name='{$bEsc}' LIMIT 1"
			);
			if ($hq && ($hr = $hq->fetch_assoc())) {
				$header = [
					'bus' => (string) ($hr['Name'] ?? $bus),
					'date' => (string) ($hr['date'] ?? $date),
					'date_label' => tessera_cargo_ops_date_label((string) ($hr['date'] ?? $date)),
					'driver' => (string) ($hr['Driver'] ?? ''),
					'conductor' => (string) ($hr['Conductor'] ?? ''),
					'code' => (string) ($hr['code'] ?? ''),
					'route' => (string) ($hr['Route'] ?? ''),
				];
			}
		}

		$byRoute = [];
		$summary = ['count' => 0, 'paid' => 0.0, 'to_be' => 0.0, 'amount' => 0.0, 'checked_out' => 0];
		$items = [];
		if ($date !== '' && $bus !== '') {
			if (!function_exists('tessera_sql_tenant_eq')) {
				require_once __DIR__ . '/tessera_tenant.php';
			}
			$tenant = tessera_sql_tenant_eq();
			$dEsc = tessera_cargo_ops_esc($db, $date);
			$bEsc = tessera_cargo_ops_esc($db, $bus);
			$where = "trip_date='{$dEsc}' AND bus='{$bEsc}' AND {$tenant}";

			$sq = $db->query(
				"SELECT COUNT(*) AS c,
				 COALESCE(SUM(paid),0) AS paid,
				 COALESCE(SUM(to_be),0) AS to_be,
				 COALESCE(SUM(amount),0) AS amount,
				 COALESCE(SUM(CASE WHEN checked='out' THEN 1 ELSE 0 END),0) AS checked_out
				 FROM manifest WHERE {$where}"
			);
			if ($sq && ($sr = $sq->fetch_assoc())) {
				$summary = [
					'count' => (int) ($sr['c'] ?? 0),
					'paid' => (float) ($sr['paid'] ?? 0),
					'to_be' => (float) ($sr['to_be'] ?? 0),
					'amount' => (float) ($sr['amount'] ?? 0),
					'checked_out' => (int) ($sr['checked_out'] ?? 0),
				];
			}

			$rq = $db->query(
				"SELECT parcel_route,
				 COUNT(*) AS c,
				 COALESCE(SUM(paid),0) AS paid,
				 COALESCE(SUM(to_be),0) AS to_be,
				 COALESCE(SUM(loan),0) AS loan
				 FROM manifest WHERE {$where}
				 GROUP BY parcel_route
				 ORDER BY parcel_route ASC"
			);
			if ($rq) {
				while ($row = $rq->fetch_assoc()) {
					$byRoute[] = [
						'route' => (string) ($row['parcel_route'] ?? ''),
						'count' => (int) ($row['c'] ?? 0),
						'paid' => (float) ($row['paid'] ?? 0),
						'to_be' => (float) ($row['to_be'] ?? 0),
						'loan' => (float) ($row['loan'] ?? 0),
					];
				}
			}

			$page = max(1, $page);
			$perPage = max(1, min(100, $perPage));
			$offset = ($page - 1) * $perPage;
			$lq = $db->query(
				"SELECT id, code, username, parcel_route, status, checked, detail, loan, paid, to_be, amount,
				 quantity, q_left, parcel_date, weight_name, weight_value, username_taken, timetaken, parcel_uploader
				 FROM manifest WHERE {$where} ORDER BY id DESC LIMIT {$offset}, {$perPage}"
			);
			if ($lq) {
				while ($row = $lq->fetch_assoc()) {
					$items[] = [
						'id' => (int) ($row['id'] ?? 0),
						'code' => (string) ($row['code'] ?? ''),
						'owner' => (string) ($row['username'] ?? ''),
						'route' => (string) ($row['parcel_route'] ?? ''),
						'status' => (string) ($row['status'] ?? ''),
						'checked' => (string) ($row['checked'] ?? ''),
						'detail' => tessera_cargo_ops_decode((string) ($row['detail'] ?? '')),
						'loan' => (float) ($row['loan'] ?? 0),
						'paid' => (float) ($row['paid'] ?? 0),
						'to_be' => (float) ($row['to_be'] ?? 0),
						'amount' => (float) ($row['amount'] ?? 0),
						'quantity' => (string) ($row['q_left'] ?? '') . '/' . (string) ($row['quantity'] ?? ''),
						'parcel_date' => (string) ($row['parcel_date'] ?? ''),
						'weight' => trim((string) ($row['weight_value'] ?? '') . (string) ($row['weight_name'] ?? '')),
						'taken_by' => (string) ($row['username_taken'] ?? ''),
						'taken_at' => (string) ($row['timetaken'] ?? ''),
						'uploader' => (string) ($row['parcel_uploader'] ?? ''),
					];
				}
			}
		}

		return [
			'header' => $header,
			'trips' => $trips,
			'by_route' => $byRoute,
			'summary' => $summary,
			'items' => $items,
			'total' => (int) $summary['count'],
		];
	}
}
