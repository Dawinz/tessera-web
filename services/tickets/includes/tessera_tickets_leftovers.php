<?php
/**
 * Tickets P3 leftovers: hire invoice, earnings profile, pinned seats.
 */
declare(strict_types=1);

if (!function_exists('tessera_tickets_hire_invoice')) {
	/**
	 * Preview only — same fields as classic generate_invoice.php. Does not write a booking.
	 *
	 * @param array<string,mixed> $input
	 * @return array<string,mixed>
	 */
	function tessera_tickets_hire_invoice(array $input): array
	{
		$bus = trim((string) ($input['bus'] ?? ''));
		$route = trim((string) ($input['route'] ?? ''));
		$date = trim((string) ($input['date'] ?? ''));
		$time = trim((string) ($input['time'] ?? ''));
		$class = trim((string) ($input['class'] ?? ''));
		$seats = max(0, (int) ($input['seats'] ?? 0));
		$fare = (float) ($input['fare'] ?? 0);
		if (!is_finite($fare) || $fare < 0) {
			$fare = 0.0;
		}

		$company = '';
		if (!function_exists('tessera_settings_company_get')) {
			$helper = __DIR__ . '/tessera_settings_company.php';
			if (is_file($helper)) {
				require_once $helper;
			}
		}
		if (function_exists('tessera_settings_company_get')) {
			$got = tessera_settings_company_get();
			if (!empty($got['ok']) && is_array($got['item'] ?? null)) {
				$company = trim((string) ($got['item']['display_name'] ?? ''));
				if ($company === '') {
					$company = trim((string) ($got['item']['legal_name'] ?? ''));
				}
			}
		}

		$trip = DateTime::createFromFormat('!Y-m-d', $date);
		$dateLabel = $trip instanceof DateTime ? $trip->format('D, j M Y') : $date;
		$detailParts = array_filter([$dateLabel, $time, $class, $bus !== '' ? 'Bus ' . $bus : ''], static function ($part) {
			return $part !== '';
		});

		$items = [];
		if ($seats > 0 || $fare > 0) {
			$qty = max(1, $seats);
			$items[] = [
				'description' => 'Bus hire — ' . ($route !== '' ? $route : 'Private trip'),
				'detail' => implode(' · ', $detailParts),
				'qty' => $qty,
				'unit' => $fare,
				'amount' => $qty * $fare,
			];
		}
		$total = 0.0;
		foreach ($items as $item) {
			$total += (float) $item['amount'];
		}

		$issued = date('Y-m-d');
		return [
			'company' => $company,
			'invoice_no' => 'INV-' . date('Ymd') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT),
			'issue_date' => $issued,
			'due_date' => date('Y-m-d', strtotime('+7 days')),
			'bus' => $bus,
			'route' => $route,
			'date' => $date,
			'time' => $time,
			'class' => $class,
			'seats' => $seats,
			'fare' => $fare,
			'items' => $items,
			'total' => $total,
		];
	}
}

if (!function_exists('tessera_tickets_earnings_profile')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_tickets_earnings_profile(mysqli $db, string $username): array
	{
		date_default_timezone_set('Africa/Dar_es_Salaam');
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$month = date('m');
		$year = date('Y');
		$u = $db->real_escape_string($username);
		$tenant = tessera_sql_tenant_eq();

		$person = [
			'username' => $username,
			'firstname' => '',
			'surname' => '',
			'phone' => '',
			'found' => false,
		];
		$uq = $db->query(
			"SELECT username, firstname, surname, number FROM users WHERE username='{$u}' LIMIT 1"
		);
		if ($uq && ($row = $uq->fetch_assoc())) {
			$person['found'] = true;
			$person['firstname'] = (string) ($row['firstname'] ?? '');
			$person['surname'] = (string) ($row['surname'] ?? '');
			$person['phone'] = (string) ($row['number'] ?? '');
		}

		$monthTotal = 0.0;
		$tickets = 0;
		$sum = $db->query(
			"SELECT SUM(cost) AS cost, COUNT(*) AS tickets FROM booking
			 WHERE {$tenant} AND booker='{$u}' AND month='{$month}' AND year='{$year}'"
		);
		if ($sum && ($srow = $sum->fetch_assoc())) {
			$monthTotal = (float) ($srow['cost'] ?? 0);
			$tickets = (int) ($srow['tickets'] ?? 0);
		}

		$items = [];
		$lq = $db->query(
			"SELECT id, seat_no, firstname, lastname, cost, trip_date, bus, status
			 FROM booking
			 WHERE {$tenant} AND booker='{$u}' AND month='{$month}' AND year='{$year}'
			 ORDER BY id DESC LIMIT 8"
		);
		if ($lq) {
			while ($row = $lq->fetch_assoc()) {
				$items[] = [
					'id' => (int) ($row['id'] ?? 0),
					'seat_no' => (string) ($row['seat_no'] ?? ''),
					'passenger' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['lastname'] ?? '')),
					'cost' => (float) ($row['cost'] ?? 0),
					'trip_date' => (string) ($row['trip_date'] ?? ''),
					'bus' => (string) ($row['bus'] ?? ''),
					'status' => (string) ($row['status'] ?? ''),
				];
			}
		}

		return [
			'person' => $person,
			'month' => $month,
			'year' => $year,
			'month_label' => date('F Y'),
			'month_total' => $monthTotal,
			'tickets' => $tickets,
			'items' => $items,
		];
	}
}

if (!function_exists('tessera_tickets_pinned_list')) {
	/**
	 * @return array{items:list<array<string,mixed>>,total:int}
	 */
	function tessera_tickets_pinned_list(mysqli $db, string $username, int $limit): array
	{
		$limit = max(1, min(100, $limit));
		$u = $db->real_escape_string($username);
		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM pin WHERE username='{$u}'");
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$total = (int) ($cr['c'] ?? 0);
		}

		$items = [];
		$q = $db->query(
			"SELECT id, route_id, route_day_id, date, day FROM pin
			 WHERE username='{$u}' ORDER BY id DESC LIMIT {$limit}"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$routeId = (int) ($row['route_id'] ?? 0);
				$start = '';
				$end = '';
				if ($routeId > 0) {
					$rq = $db->query(
						"SELECT start_route, end_route FROM assigned_routes WHERE route_id={$routeId} LIMIT 1"
					);
					if ($rq && ($rr = $rq->fetch_assoc())) {
						$start = (string) ($rr['start_route'] ?? '');
						$end = (string) ($rr['end_route'] ?? '');
					}
				}
				$routeDayId = (int) ($row['route_day_id'] ?? 0);
				$date = (string) ($row['date'] ?? '');
				$label = trim($start . ($end !== '' ? ' → ' . $end : ''));
				$book = '/operator/app.php?module=tickets&page=schedule.php';
				if ($routeDayId > 0) {
					$book = '/operator/app.php?module=tickets&page=schedule_book.php&route_day_id=' . $routeDayId;
				}
				$items[] = [
					'id' => (int) ($row['id'] ?? 0),
					'route_id' => $routeId,
					'route_day_id' => $routeDayId,
					'date' => $date,
					'day' => (string) ($row['day'] ?? ''),
					'route' => $label !== '' ? $label : ('Route ' . $routeId),
					'book_url' => $book,
				];
			}
		}

		return ['items' => $items, 'total' => $total];
	}
}

if (!function_exists('tessera_tickets_pinned_delete')) {
	/**
	 * @return array{ok:bool,error?:string}
	 */
	function tessera_tickets_pinned_delete(mysqli $db, string $username, int $id): array
	{
		if ($id < 1) {
			return ['ok' => false, 'error' => 'Pinned seat id is required'];
		}
		$u = $db->real_escape_string($username);
		$db->query("DELETE FROM pin WHERE id={$id} AND username='{$u}' LIMIT 1");
		if ($db->affected_rows < 1) {
			return ['ok' => false, 'error' => 'Pinned seat was not found'];
		}
		return ['ok' => true];
	}
}
