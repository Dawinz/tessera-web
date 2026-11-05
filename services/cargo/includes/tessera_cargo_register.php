<?php
/**
 * Shared Cargo register-luggage helpers (C-P0-01).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_register_today_summary')) {
	/**
	 * @return array{paid:float,to_be:float,total:float,date:string}
	 */
	function tessera_cargo_register_today_summary(mysqli $db, string $username): array
	{
		$u = $db->real_escape_string($username);
		date_default_timezone_set('Africa/Dar_es_Salaam');
		$today = date('Y-m-d');
		$d = $db->real_escape_string($today);

		$paid = 0.0;
		$toBe = 0.0;
		$q = $db->query(
			"SELECT COALESCE(SUM(paid),0) AS paid, COALESCE(SUM(to_be),0) AS to_be
			 FROM parcel_info
			 WHERE username='{$u}' AND date_insert='{$d}' AND open != 'yes'"
		);
		if ($q && ($row = $q->fetch_assoc())) {
			$paid = (float) ($row['paid'] ?? 0);
			$toBe = (float) ($row['to_be'] ?? 0);
		}
		return [
			'paid' => $paid,
			'to_be' => $toBe,
			'total' => $paid + $toBe,
			'date' => $today,
		];
	}
}

if (!function_exists('tessera_cargo_register_bootstrap')) {
	/**
	 * @return array{routes:list<array{name:string}>,country_codes:list<string>,summary:array{paid:float,to_be:float,total:float,date:string},agent:array{username:string,display_name:string}}
	 */
	function tessera_cargo_register_bootstrap(mysqli $db, string $username): array
	{
		$u = $db->real_escape_string($username);
		$routes = [];
		$rq = $db->query(
			"SELECT route_full_name FROM assigned_routes WHERE username='{$u}' ORDER BY id DESC"
		);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$name = trim((string) ($row['route_full_name'] ?? ''));
				if ($name !== '') {
					$routes[] = ['name' => $name];
				}
			}
		}

		$prefStart = '';
		$prefEnd = '';
		$pq = $db->query(
			"SELECT start_route, end_route FROM assigned_routes
			 WHERE username='{$u}' AND preference='1' ORDER BY id DESC LIMIT 1"
		);
		if ($pq && ($prow = $pq->fetch_assoc())) {
			$prefStart = (string) ($prow['start_route'] ?? '');
			$prefEnd = (string) ($prow['end_route'] ?? '');
		}

		$codes = [];
		$orderField = $prefEnd !== '' ? $prefEnd : $prefStart;
		if ($orderField !== '') {
			$of = $db->real_escape_string($orderField);
			$cq = $db->query("SELECT code FROM country_code ORDER BY FIELD(route_name,'{$of}') DESC");
		} else {
			$cq = $db->query('SELECT code FROM country_code ORDER BY id ASC');
		}
		if ($cq) {
			while ($crow = $cq->fetch_assoc()) {
				$code = trim((string) ($crow['code'] ?? ''));
				if ($code !== '' && !in_array($code, $codes, true)) {
					$codes[] = $code;
				}
			}
		}
		if ($codes === []) {
			$codes = ['+255'];
		}

		$display = $username;
		$uq = $db->query("SELECT firstname, surname FROM users WHERE username='{$u}' LIMIT 1");
		if ($uq && ($urow = $uq->fetch_assoc())) {
			$display = trim(((string) ($urow['firstname'] ?? '')) . ' ' . ((string) ($urow['surname'] ?? '')));
			if ($display === '') {
				$display = $username;
			}
		}

		return [
			'routes' => $routes,
			'country_codes' => $codes,
			'summary' => tessera_cargo_register_today_summary($db, $username),
			'agent' => [
				'username' => $username,
				'display_name' => $display,
			],
		];
	}
}

if (!function_exists('tessera_cargo_register_allocate_code')) {
	/**
	 * @return array{ok:bool,code?:string,error?:string}
	 */
	function tessera_cargo_register_allocate_code(mysqli $db, string $route, string $initial, string $agentInitial): array
	{
		$rte = $db->real_escape_string($route);
		$select = $db->query("SELECT code FROM code WHERE location='{$rte}' LIMIT 1");
		if ($select && $select->num_rows > 0) {
			$row = $select->fetch_assoc();
			$next = ((int) ($row['code'] ?? 0)) + 1;
			$codePlus = $initial . $next . $agentInitial;
			$upd = $db->query("UPDATE code SET code='{$next}' WHERE location='{$rte}'");
			if (!$upd) {
				return ['ok' => false, 'error' => 'Could not allocate parcel code'];
			}
		} else {
			$codePlus = $initial . '1' . $agentInitial;
			$ins = $db->query("INSERT INTO code (code, location) VALUES ('1', '{$rte}')");
			if (!$ins) {
				return ['ok' => false, 'error' => 'Could not create parcel code sequence'];
			}
		}

		$codeEsc = $db->real_escape_string($codePlus);
		$dup = $db->query("SELECT id FROM parcel_info WHERE code='{$codeEsc}' AND account='{$rte}' LIMIT 1");
		if ($dup && $dup->num_rows > 0) {
			return ['ok' => false, 'error' => 'Parcel code collision — retry'];
		}
		return ['ok' => true, 'code' => $codePlus];
	}
}

if (!function_exists('tessera_cargo_register_apply_store_charges')) {
	function tessera_cargo_register_apply_store_charges(
		mysqli $db,
		string $username,
		string $status,
		float $amount,
		float $amountPaid,
		float $dueTotal,
		string $route
	): void {
		date_default_timezone_set('Africa/Dar_es_Salaam');
		$dateInsert = date('Y-m-d');
		$month = date('m-Y');
		$u = $db->real_escape_string($username);
		$rte = $db->real_escape_string($route);
		$di = $db->real_escape_string($dateInsert);
		$mo = $db->real_escape_string($month);

		$store = $db->query("SELECT paid, to_be FROM store WHERE location='{$rte}' AND username='{$u}' LIMIT 1");
		$daily = $db->query(
			"SELECT paid, to_be, no_paid, no_to_be, no_due FROM daily_charges
			 WHERE date_insert='{$di}' AND location='{$rte}' AND username='{$u}' LIMIT 1"
		);
		$monthly = $db->query(
			"SELECT paid, to_be, no_paid, no_to_be, no_due FROM monthly_charges
			 WHERE date_insert='{$mo}' AND location='{$rte}' AND username='{$u}' LIMIT 1"
		);

		$addPaid = 0.0;
		$addToBe = 0.0;
		$incPaid = 0;
		$incToBe = 0;
		$incDue = 0;

		if ($status === 'paid') {
			$addPaid = $amount;
			$incPaid = 1;
		} elseif ($status === 'To be Payed') {
			$addToBe = $amount;
			$incToBe = 1;
		} elseif ($status === 'Due payment') {
			$addPaid = $amountPaid;
			$addToBe = $dueTotal;
			$incDue = 1;
		} else {
			return;
		}

		if ($store && $store->num_rows > 0) {
			$s = $store->fetch_assoc();
			$newPaid = (float) ($s['paid'] ?? 0) + $addPaid;
			$newToBe = (float) ($s['to_be'] ?? 0) + $addToBe;
			$db->query("UPDATE store SET paid='{$newPaid}', to_be='{$newToBe}' WHERE location='{$rte}' AND username='{$u}'");
		} else {
			$db->query(
				"INSERT INTO store (paid, to_be, location, username) VALUES ('{$addPaid}', '{$addToBe}', '{$rte}', '{$u}')"
			);
		}

		if ($daily && $daily->num_rows > 0) {
			$d = $daily->fetch_assoc();
			$newPaid = (float) ($d['paid'] ?? 0) + $addPaid;
			$newToBe = (float) ($d['to_be'] ?? 0) + $addToBe;
			$np = (int) ($d['no_paid'] ?? 0) + $incPaid;
			$nt = (int) ($d['no_to_be'] ?? 0) + $incToBe;
			$nd = (int) ($d['no_due'] ?? 0) + $incDue;
			$db->query(
				"UPDATE daily_charges SET paid='{$newPaid}', to_be='{$newToBe}',
				 no_paid='{$np}', no_to_be='{$nt}', no_due='{$nd}', month='{$mo}'
				 WHERE date_insert='{$di}' AND location='{$rte}' AND username='{$u}'"
			);
		} else {
			$db->query(
				"INSERT INTO daily_charges (paid, to_be, location, date_insert, no_paid, no_to_be, no_due, month, username)
				 VALUES ('{$addPaid}', '{$addToBe}', '{$rte}', '{$di}', '{$incPaid}', '{$incToBe}', '{$incDue}', '{$mo}', '{$u}')"
			);
		}

		if ($monthly && $monthly->num_rows > 0) {
			$m = $monthly->fetch_assoc();
			$newPaid = (float) ($m['paid'] ?? 0) + $addPaid;
			$newToBe = (float) ($m['to_be'] ?? 0) + $addToBe;
			$np = (int) ($m['no_paid'] ?? 0) + $incPaid;
			$nt = (int) ($m['no_to_be'] ?? 0) + $incToBe;
			$nd = (int) ($m['no_due'] ?? 0) + $incDue;
			$db->query(
				"UPDATE monthly_charges SET paid='{$newPaid}', to_be='{$newToBe}',
				 no_paid='{$np}', no_to_be='{$nt}', no_due='{$nd}'
				 WHERE date_insert='{$mo}' AND location='{$rte}' AND username='{$u}'"
			);
		} else {
			$db->query(
				"INSERT INTO monthly_charges (paid, to_be, location, date_insert, no_paid, no_to_be, no_due, username)
				 VALUES ('{$addPaid}', '{$addToBe}', '{$rte}', '{$mo}', '{$incPaid}', '{$incToBe}', '{$incDue}', '{$u}')"
			);
		}
	}
}

if (!function_exists('tessera_cargo_register_parcels')) {
	/**
	 * @param array{sender_name:string,sender_code:string,sender_phone:string,receivers:list<array<string,mixed>>} $payload
	 * @return array{ok:bool,error?:string,receipts?:list<array<string,mixed>>,summary?:array{paid:float,to_be:float,total:float,date:string}}
	 */
	function tessera_cargo_register_parcels(mysqli $db, string $username, array $payload): array
	{
		date_default_timezone_set('Africa/Dar_es_Salaam');
		$senderName = strtoupper(trim(str_replace("'", '_', (string) ($payload['sender_name'] ?? ''))));
		$senderCode = trim((string) ($payload['sender_code'] ?? '+255'));
		$senderPhone = preg_replace('/\D+/', '', (string) ($payload['sender_phone'] ?? '')) ?? '';
		$receivers = $payload['receivers'] ?? [];
		if (!is_array($receivers) || $receivers === []) {
			return ['ok' => false, 'error' => 'Add at least one receiver'];
		}
		if ($senderName === '') {
			return ['ok' => false, 'error' => 'Sender name is required'];
		}
		$len = strlen($senderPhone);
		if ($len < 9 || $len > 11) {
			return ['ok' => false, 'error' => 'Sender phone must be 9–11 digits'];
		}

		$uEsc = $db->real_escape_string($username);
		$agentInitial = 'X';
		$uq = $db->query("SELECT firstname, surname FROM users WHERE username='{$uEsc}' LIMIT 1");
		if ($uq && ($urow = $uq->fetch_assoc())) {
			$fn = (string) ($urow['firstname'] ?? '');
			if (strcasecmp($username, 'Nassir') === 0) {
				$agentInitial = 'N';
			} elseif ($fn !== '') {
				$agentInitial = strtoupper(substr($fn, 0, 1));
			}
		}

		$dateInsert = date('Y-m-d');
		$month = date('m-Y');
		$timeInsert = date('h:i A');
		$printTime = date('d-m-Y') . ' at ' . date('H:i');
		$printId = bin2hex(random_bytes(15)) . $timeInsert;
		$senderNo = $senderCode . $senderPhone;

		$receipts = [];
		$db->begin_transaction();
		try {
			foreach ($receivers as $idx => $recv) {
				if (!is_array($recv)) {
					throw new RuntimeException('Invalid receiver at row ' . ($idx + 1));
				}
				$rName = strtoupper(trim(str_replace("'", '_', (string) ($recv['name'] ?? ''))));
				$rCode = trim((string) ($recv['phone_code'] ?? $senderCode));
				$rPhone = preg_replace('/\D+/', '', (string) ($recv['phone'] ?? '')) ?? '';
				$route = trim((string) ($recv['route'] ?? ''));
				$status = trim((string) ($recv['payment_status'] ?? ''));
				$amount = (float) preg_replace('/[^0-9.]+/', '', (string) ($recv['amount'] ?? '0'));
				$quantity = (float) preg_replace('/[^0-9.]+/', '', (string) ($recv['quantity'] ?? '1'));
				$weightName = trim((string) ($recv['weight_unit'] ?? 'kg'));
				$weightValue = (float) preg_replace('/[^0-9.]+/', '', (string) ($recv['weight'] ?? '0'));
				$luggageValue = (float) preg_replace('/[^0-9.]+/', '', (string) ($recv['declared_value'] ?? '0'));
				$detailRaw = trim((string) ($recv['detail'] ?? 'NGUO'));
				if ($detailRaw === '') {
					$detailRaw = 'NGUO';
				}
				$detail = base64_encode(str_replace("'", '_', $detailRaw));
				$amountPaidPartial = (float) preg_replace('/[^0-9.]+/', '', (string) ($recv['amount_paid'] ?? '0'));

				$rLen = strlen($rPhone);
				if ($rName === '') {
					throw new RuntimeException('Receiver name required on row ' . ($idx + 1));
				}
				if ($rLen < 9 || $rLen > 11) {
					throw new RuntimeException('Receiver phone must be 9–11 digits on row ' . ($idx + 1));
				}
				if ($route === '' || strcasecmp($route, 'Choose Route') === 0) {
					throw new RuntimeException('Choose a route on row ' . ($idx + 1));
				}
				if (!in_array($status, ['paid', 'To be Payed', 'Due payment'], true)) {
					throw new RuntimeException('Choose payment status on row ' . ($idx + 1));
				}
				if ($amount <= 0) {
					throw new RuntimeException('Transport fee required on row ' . ($idx + 1));
				}
				if ($quantity <= 0) {
					$quantity = 1;
				}
				if ($status === 'Due payment') {
					if ($amountPaidPartial <= 0 || $amountPaidPartial >= $amount) {
						throw new RuntimeException('Due payment needs a partial paid amount on row ' . ($idx + 1));
					}
				}

				$paidVal = 0.0;
				$toBeVal = 0.0;
				$dueVal = 0.0;
				$answerVal = 0.0;
				$paidAmount = 0.0;
				$toBeAmount = 0.0;
				if ($status === 'paid') {
					$paidVal = $amount;
					$paidAmount = $amount;
				} elseif ($status === 'To be Payed') {
					$toBeVal = $amount;
					$toBeAmount = $amount;
					$answerVal = 0.0;
				} else {
					$dueTotal = $amount - $amountPaidPartial;
					$paidVal = $amountPaidPartial;
					$toBeVal = $dueTotal;
					$dueVal = $amountPaidPartial;
					$answerVal = $dueTotal;
					$paidAmount = $amountPaidPartial;
					$toBeAmount = $dueTotal;
				}

				$rteEsc = $db->real_escape_string($route);
				$rq = $db->query(
					"SELECT initial, currency, currency_symbol, to_be_paid_currency, contact_1, contact_2, address_1, address_2
					 FROM routes WHERE route_full_name='{$rteEsc}' LIMIT 1"
				);
				if (!$rq || $rq->num_rows === 0) {
					throw new RuntimeException('Unknown route on row ' . ($idx + 1));
				}
				$routeRow = $rq->fetch_assoc();
				$initial = (string) ($routeRow['initial'] ?? '');
				$currency = (string) ($routeRow['currency'] ?? '');
				$currencySymbol = (string) ($routeRow['currency_symbol'] ?? '');
				$toBePaidCurrency = (string) ($routeRow['to_be_paid_currency'] ?? '');
				$contact1 = (string) ($routeRow['contact_1'] ?? '');
				$contact2 = (string) ($routeRow['contact_2'] ?? '');
				$address1 = (string) ($routeRow['address_1'] ?? '');
				$address2 = (string) ($routeRow['address_2'] ?? '');
				if ($initial === '') {
					throw new RuntimeException('Route missing code initial on row ' . ($idx + 1));
				}

				$alloc = tessera_cargo_register_allocate_code($db, $route, $initial, $agentInitial);
				if (!$alloc['ok']) {
					throw new RuntimeException((string) ($alloc['error'] ?? 'Code allocation failed'));
				}
				$codePlus = (string) $alloc['code'];

				$rNo = $rCode . $rPhone;
				$fn = $db->real_escape_string($senderName);
				$rfn = $db->real_escape_string($rName);
				$snd = $db->real_escape_string($senderNo);
				$rno = $db->real_escape_string($rNo);
				$det = $db->real_escape_string($detail);
				$st = $db->real_escape_string($status);
				$codeEsc = $db->real_escape_string($codePlus);
				$di = $db->real_escape_string($dateInsert);
				$tm = $db->real_escape_string($timeInsert);
				$mo = $db->real_escape_string($month);
				$sym = $db->real_escape_string($currencySymbol);
				$cty = $db->real_escape_string($currency);
				$pc = $db->real_escape_string($printId);
				$wn = $db->real_escape_string($weightName !== '' ? $weightName : 'kg');
				$q = (string) $quantity;
				$amt = (string) $amount;
				$lag = (string) $luggageValue;
				$wval = (string) $weightValue;
				$paidS = (string) $paidVal;
				$toBeS = (string) $toBeVal;
				$dueS = (string) $dueVal;
				$answerS = (string) $answerVal;

				$insert = $db->query(
					"INSERT INTO parcel_info (
						s_name, s_surname, s_no, r_name, r_no, account, detail, status, amount, code,
						rec_status, question, store, answer, scanned, date_insert, time, uploader, username,
						paid, to_be, due, month, initial, country, cleared, print_code, payment_status,
						open, weight_name, weight_value, lugg_value, paid_remain, to_be_remain,
						quantity_remain, amount_remain, printed_time, edited
					) VALUES (
						'{$fn}','0','{$snd}','{$rfn}','{$rno}','{$rteEsc}','{$det}','{$st}','{$amt}','{$codeEsc}',
						'No','{$q}','store','{$answerS}','{$q}','{$di}','{$tm}','','{$uEsc}',
						'{$paidS}','{$toBeS}','{$dueS}','{$mo}','{$sym}','{$cty}','Not paid','{$pc}','{$paidS}',
						'open','{$wn}','{$wval}','{$lag}','{$paidS}','{$toBeS}',
						'{$q}','0','0','no'
					)"
				);
				if (!$insert) {
					throw new RuntimeException('Insert failed: ' . $db->error);
				}

				tessera_cargo_register_apply_store_charges(
					$db,
					$username,
					$status,
					$amount,
					$amountPaidPartial,
					$status === 'Due payment' ? ($amount - $amountPaidPartial) : 0.0,
					$route
				);

				$paymentLabel = $status === 'To be Payed' ? 'To be Paid' : ($status === 'paid' ? 'Paid' : $status);
				$receipts[] = [
					'code' => $codePlus,
					'route' => $route,
					'sender_name' => $senderName,
					'sender_phone' => $senderNo,
					'receiver_name' => $rName,
					'receiver_phone' => $rNo,
					'detail' => $detailRaw,
					'quantity' => $quantity,
					'payment_status' => $paymentLabel,
					'paid' => $paidAmount,
					'to_be' => $toBeAmount,
					'amount' => $amount,
					'weight' => $weightValue,
					'weight_unit' => $weightName !== '' ? $weightName : 'kg',
					'currency_symbol' => $currencySymbol,
					'declared_value' => $luggageValue,
					'print_time' => $printTime,
					'print_url' => '/cargo/print_ticket_mulitple.php?code=' . rawurlencode($codePlus),
					'address_1' => $address1,
					'address_2' => $address2,
					'contact_1' => $contact1,
					'contact_2' => $contact2,
					'to_be_paid_currency' => $toBePaidCurrency,
				];
			}

			$db->commit();
		} catch (Throwable $e) {
			$db->rollback();
			return ['ok' => false, 'error' => $e->getMessage()];
		}

		if (is_file(__DIR__ . '/tessera_cargo_notify.php')) {
			require_once __DIR__ . '/tessera_cargo_notify.php';
			if (function_exists('tessera_cargo_notify') && $receipts !== []) {
				$first = $receipts[0];
				tessera_cargo_notify(
					'cargo.registered',
					$senderPhone,
					(string) $first['code'],
					"Habari {$senderName}, mzigo wako {$first['code']} umesajiliwa."
				);
			}
		}

		foreach ($receipts as $receipt) {
			tessera_cargo_contact_remember(
				$db,
				(string) ($receipt['sender_name'] ?? ''),
				(string) ($receipt['sender_phone'] ?? ''),
				(string) ($receipt['receiver_name'] ?? ''),
				(string) ($receipt['receiver_phone'] ?? '')
			);
		}

		return [
			'ok' => true,
			'receipts' => $receipts,
			'summary' => tessera_cargo_register_today_summary($db, $username),
		];
	}
}

if (!function_exists('tessera_cargo_phone_local')) {
	/** Prefer last 9 digits for Tanzanian-style local numbers. */
	function tessera_cargo_phone_local(string $raw): string
	{
		$digits = preg_replace('/\D+/', '', $raw) ?? '';
		if (strlen($digits) > 9) {
			return substr($digits, -9);
		}
		return $digits;
	}
}

if (!function_exists('tessera_cargo_contact_remember')) {
	/**
	 * Upsert classic contact book row (parity with update_contact.php).
	 */
	function tessera_cargo_contact_remember(
		mysqli $db,
		string $senderName,
		string $senderPhone,
		string $receiverName,
		string $receiverPhone
	): void {
		$sName = trim($senderName);
		$rName = trim($receiverName);
		$sNo = tessera_cargo_phone_local($senderPhone);
		$rNo = tessera_cargo_phone_local($receiverPhone);
		if ($sName === '' || $rName === '' || strlen($sNo) !== 9 || strlen($rNo) !== 9) {
			return;
		}
		$sNameEsc = $db->real_escape_string($sName);
		$rNameEsc = $db->real_escape_string($rName);
		$sNoEsc = $db->real_escape_string($sNo);
		$rNoEsc = $db->real_escape_string($rNo);
		$exists = $db->query(
			"SELECT id FROM contact
			 WHERE s_name='{$sNameEsc}' AND s_no='{$sNoEsc}' AND r_name='{$rNameEsc}' AND r_no='{$rNoEsc}'
			 LIMIT 1"
		);
		if ($exists && $exists->num_rows > 0) {
			return;
		}
		$db->query(
			"INSERT INTO contact (s_name, s_no, r_name, r_no)
			 VALUES ('{$sNameEsc}', '{$sNoEsc}', '{$rNameEsc}', '{$rNoEsc}')"
		);
	}
}

if (!function_exists('tessera_cargo_contact_lookup')) {
	/**
	 * @return list<array{id:int,sender_name:string,sender_phone:string,receiver_name:string,receiver_phone:string}>
	 */
	function tessera_cargo_contact_lookup(mysqli $db, string $q, int $limit = 10): array
	{
		$q = preg_replace('/\D+/', '', $q) ?? '';
		if (strlen($q) < 2) {
			return [];
		}
		$limit = max(1, min(20, $limit));
		$esc = $db->real_escape_string($q);
		$items = [];
		$sql = $db->query(
			"SELECT id, s_name, s_no, r_name, r_no FROM contact
			 WHERE s_no LIKE '%{$esc}%' OR r_no LIKE '%{$esc}%'
			 ORDER BY id DESC LIMIT {$limit}"
		);
		if ($sql) {
			while ($row = $sql->fetch_assoc()) {
				$items[] = [
					'id' => (int) ($row['id'] ?? 0),
					'sender_name' => (string) ($row['s_name'] ?? ''),
					'sender_phone' => tessera_cargo_phone_local((string) ($row['s_no'] ?? '')),
					'receiver_name' => (string) ($row['r_name'] ?? ''),
					'receiver_phone' => tessera_cargo_phone_local((string) ($row['r_no'] ?? '')),
				];
			}
		}
		return $items;
	}
}
