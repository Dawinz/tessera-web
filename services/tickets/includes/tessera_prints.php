<?php
/**
 * Ticket print documents and seat transfers (T-P2).
 * Same booking / route_day / manifest rows the classic print pages used.
 */
declare(strict_types=1);

if (!function_exists('tessera_prints_esc')) {
	function tessera_prints_esc(mysqli $db, string $value): string
	{
		return $db->real_escape_string($value);
	}
}

if (!function_exists('tessera_prints_rows')) {
	/**
	 * @return list<array<string,mixed>>
	 */
	function tessera_prints_rows(mysqli $db, string $sql): array
	{
		$out = [];
		$q = $db->query($sql);
		if (!$q) {
			return [];
		}
		while ($row = $q->fetch_assoc()) {
			$out[] = $row;
		}
		return $out;
	}
}

if (!function_exists('tessera_prints_company')) {
	/**
	 * @return array{name:string,logo_url:string}
	 */
	function tessera_prints_company(): array
	{
		$name = 'Operator';
		$logo = '';
		if (!function_exists('tessera_tenant_row')) {
			$tenant = dirname(__DIR__) . '/includes/tessera_tenant.php';
			if (is_file($tenant)) {
				require_once $tenant;
			}
		}
		if (function_exists('tessera_tenant_row')) {
			$row = tessera_tenant_row();
			if (is_array($row)) {
				$display = trim((string) ($row['display_name'] ?? ''));
				$legal = trim((string) ($row['legal_name'] ?? ''));
				$name = $display !== '' ? $display : ($legal !== '' ? $legal : $name);
				$logo = (string) ($row['logo_url'] ?? '');
			}
		}
		return ['name' => $name, 'logo_url' => $logo];
	}
}

if (!function_exists('tessera_prints_doc')) {
	/**
	 * @param list<array{label:string,value:string}> $meta
	 * @param list<string> $columns
	 * @param list<list<string>> $rows
	 * @param list<int> $rowIds
	 * @param array<string,mixed> $extra
	 * @return array<string,mixed>
	 */
	function tessera_prints_doc(
		string $kind,
		string $title,
		array $meta,
		array $columns,
		array $rows,
		string $notes = '',
		array $rowIds = [],
		bool $selectable = false,
		array $extra = []
	): array {
		return array_merge([
			'kind' => $kind,
			'title' => $title,
			'company' => tessera_prints_company(),
			'meta' => $meta,
			'columns' => $columns,
			'rows' => $rows,
			'row_ids' => $rowIds,
			'notes' => $notes,
			'empty' => $rows === [] && $meta === [],
			'selectable' => $selectable,
		], $extra);
	}
}

if (!function_exists('tessera_prints_date')) {
	function tessera_prints_date(string $raw): string
	{
		$raw = trim($raw);
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
			return $raw;
		}
		$ts = strtotime($raw);
		return $ts ? date('Y-m-d', $ts) : '';
	}
}

if (!function_exists('tessera_prints_id_list')) {
	/**
	 * @return list<int>
	 */
	function tessera_prints_id_list(string $raw): array
	{
		$ids = [];
		foreach (preg_split('/\s*,\s*/', trim($raw)) ?: [] as $part) {
			$id = (int) $part;
			if ($id > 0) {
				$ids[] = $id;
			}
		}
		return array_values(array_unique($ids));
	}
}

if (!function_exists('tessera_prints_alive')) {
	function tessera_prints_alive(): string
	{
		return tessera_sql_tenant_eq() . " AND (delete_book='' OR delete_book IS NULL)";
	}
}

if (!function_exists('tessera_prints_route_label')) {
	function tessera_prints_route_label(mysqli $db, string $routeId): string
	{
		$routeId = trim($routeId);
		if ($routeId === '') {
			return '';
		}
		$id = tessera_prints_esc($db, $routeId);
		$rows = tessera_prints_rows($db, "SELECT route_name, start_route, end_route, contact_no1, contact_no2, currency, fare_price FROM routes WHERE route_id='{$id}' LIMIT 1");
		if ($rows === []) {
			return $routeId;
		}
		$name = trim((string) ($rows[0]['route_name'] ?? ''));
		if ($name !== '') {
			return $name;
		}
		return trim((string) ($rows[0]['start_route'] ?? '') . ' → ' . (string) ($rows[0]['end_route'] ?? ''));
	}
}

if (!function_exists('tessera_prints_route_row')) {
	/**
	 * @return array<string,mixed>|null
	 */
	function tessera_prints_route_row(mysqli $db, string $routeId): ?array
	{
		$id = tessera_prints_esc($db, trim($routeId));
		if ($id === '') {
			return null;
		}
		$rows = tessera_prints_rows(
			$db,
			"SELECT route_id, route_name, start_route, end_route, contact_no1, contact_no2, currency, fare_price
			 FROM routes WHERE route_id='{$id}' LIMIT 1"
		);
		return $rows[0] ?? null;
	}
}

if (!function_exists('tessera_prints_user_name')) {
	function tessera_prints_user_name(mysqli $db, string $username): string
	{
		$username = trim($username);
		if ($username === '') {
			return '';
		}
		$u = tessera_prints_esc($db, $username);
		$rows = tessera_prints_rows($db, "SELECT firstname, surname FROM users WHERE username='{$u}' LIMIT 1");
		if ($rows === []) {
			return $username;
		}
		return trim((string) ($rows[0]['firstname'] ?? '') . ' ' . (string) ($rows[0]['surname'] ?? ''));
	}
}

if (!function_exists('tessera_prints_booking')) {
	/**
	 * @return array<string,mixed>|null
	 */
	function tessera_prints_booking(mysqli $db, int $id): ?array
	{
		if ($id < 1) {
			return null;
		}
		$alive = tessera_prints_alive();
		$rows = tessera_prints_rows(
			$db,
			"SELECT id, seat_no, firstname, lastname, phone_no, trip_date, bus, issuer, booker, cost,
				status, start_route, end_route, route_id, route_day_id, print_id, currency,
				start_location, ending_location, book_date, book_time, sub_route_id, amount_left
			 FROM booking WHERE id={$id} AND {$alive} LIMIT 1"
		);
		return $rows[0] ?? null;
	}
}

if (!function_exists('tessera_prints_ticket_block')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array{meta:list<array{label:string,value:string}>,line:list<string>}
	 */
	function tessera_prints_ticket_block(mysqli $db, array $row): array
	{
		$route = tessera_prints_route_row($db, (string) ($row['route_id'] ?? ''));
		$from = trim((string) ($row['start_location'] ?? ''));
		$to = trim((string) ($row['ending_location'] ?? ''));
		if ($from === '' && $route) {
			$from = (string) ($route['start_route'] ?? '');
		}
		if ($to === '' && $route) {
			$to = (string) ($route['end_route'] ?? '');
		}
		$name = trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['lastname'] ?? ''));
		$meta = [
			['label' => 'Ticket no', 'value' => (string) ($row['id'] ?? '')],
			['label' => 'Seat', 'value' => (string) ($row['seat_no'] ?? '')],
			['label' => 'Passenger', 'value' => $name],
			['label' => 'Phone', 'value' => (string) ($row['phone_no'] ?? '')],
			['label' => 'From', 'value' => $from],
			['label' => 'To', 'value' => $to],
			['label' => 'Trip date', 'value' => (string) ($row['trip_date'] ?? '')],
			['label' => 'Vehicle', 'value' => (string) ($row['bus'] ?? '')],
			['label' => 'Fare', 'value' => (string) ($row['cost'] ?? '')],
			['label' => 'Status', 'value' => (string) ($row['status'] ?? '')],
			['label' => 'Issued by', 'value' => tessera_prints_user_name($db, (string) ($row['issuer'] ?? ''))],
		];
		if ($route) {
			$phone = trim((string) ($route['contact_no1'] ?? ''));
			if ($phone !== '') {
				$meta[] = ['label' => 'Route phone', 'value' => $phone];
			}
		}
		$line = [
			(string) ($row['seat_no'] ?? ''),
			$name,
			$from . ($to !== '' ? ' → ' . $to : ''),
			(string) ($row['trip_date'] ?? ''),
			(string) ($row['bus'] ?? ''),
			(string) ($row['cost'] ?? ''),
		];
		return ['meta' => $meta, 'line' => $line];
	}
}

if (!function_exists('tessera_prints_trip')) {
	/**
	 * @return array<string,mixed>|null
	 */
	function tessera_prints_trip(mysqli $db, int $routeDayId): ?array
	{
		if ($routeDayId < 1) {
			return null;
		}
		$rows = tessera_prints_rows(
			$db,
			"SELECT id, route_id, bus, arrival, departure, `12hours` AS hours, day, status
			 FROM route_day WHERE id={$routeDayId} LIMIT 1"
		);
		if ($rows === []) {
			return null;
		}
		$trip = $rows[0];
		$route = tessera_prints_route_row($db, (string) ($trip['route_id'] ?? ''));
		$bus = (string) ($trip['bus'] ?? '');
		$crew = tessera_prints_rows($db, "SELECT driver, conductor, seats FROM vehicle WHERE name='" . tessera_prints_esc($db, $bus) . "' LIMIT 1");
		$trip['route_name'] = $route ? tessera_prints_route_label($db, (string) ($trip['route_id'] ?? '')) : '';
		$trip['start_route'] = $route['start_route'] ?? '';
		$trip['end_route'] = $route['end_route'] ?? '';
		$trip['fare_price'] = $route['fare_price'] ?? '';
		$trip['driver'] = $crew[0]['driver'] ?? '';
		$trip['conductor'] = $crew[0]['conductor'] ?? '';
		$trip['seats'] = (int) ($crew[0]['seats'] ?? 0);
		return $trip;
	}
}

if (!function_exists('tessera_prints_passengers')) {
	/**
	 * @return list<array<string,mixed>>
	 */
	function tessera_prints_passengers(mysqli $db, int $routeDayId, string $tripDate, bool $paidOnly = true): array
	{
		if ($routeDayId < 1) {
			return [];
		}
		$alive = tessera_prints_alive();
		$dateSql = '';
		$date = tessera_prints_date($tripDate);
		if ($date !== '') {
			$dateSql = " AND trip_date='" . tessera_prints_esc($db, $date) . "'";
		}
		$status = $paidOnly ? " AND status<>'booked'" : '';
		return tessera_prints_rows(
			$db,
			"SELECT id, seat_no, firstname, lastname, phone_no, trip_date, bus, cost, status,
				start_location, ending_location, route_id, issuer, amount_left
			 FROM booking WHERE route_day_id={$routeDayId} {$dateSql} {$status} AND {$alive}
			 ORDER BY seat_no ASC, id ASC"
		);
	}
}

if (!function_exists('tessera_prints_passenger_doc')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_prints_passenger_doc(mysqli $db, string $kind, array $query, bool $selectable): array
	{
		$titles = [
			'manifest' => 'Passenger manifest',
			'manifest_whole' => 'Passenger manifest',
			'manifest_whole2' => 'Passenger manifest',
			'pos' => 'Trip manifest',
			'multiple_manifest' => 'Tickets from manifest',
		];
		$id = (int) ($query['id'] ?? $query['route_day_id'] ?? 0);
		$date = (string) ($query['trip_date'] ?? '');
		$trip = tessera_prints_trip($db, $id);
		if ($trip === null) {
			return tessera_prints_doc($kind, $titles[$kind] ?? 'Manifest', [], ['Seat', 'Passenger', 'Route'], [], 'Open this from a trip on the manifest.');
		}
		$people = tessera_prints_passengers($db, $id, $date !== '' ? $date : (string) ($query['trip_date'] ?? ''));
		$meta = [
			['label' => 'Trip date', 'value' => $date !== '' ? $date : ''],
			['label' => 'Vehicle', 'value' => (string) ($trip['bus'] ?? '')],
			['label' => 'Route', 'value' => (string) ($trip['route_name'] ?? '')],
			['label' => 'Driver', 'value' => (string) ($trip['driver'] ?? '')],
			['label' => 'Conductor', 'value' => (string) ($trip['conductor'] ?? '')],
			['label' => 'Departs', 'value' => (string) ($trip['departure'] ?? $trip['hours'] ?? '')],
		];
		$columns = $kind === 'manifest_whole2'
			? ['#', 'Seat', 'Passenger', 'Phone', 'From', 'To', 'Status', 'Fare']
			: ['Seat', 'Passenger', 'Phone', 'Route', 'Status', 'Fare'];
		$rows = [];
		$ids = [];
		$n = 0;
		foreach ($people as $person) {
			$n++;
			$name = trim((string) ($person['firstname'] ?? '') . ' ' . (string) ($person['lastname'] ?? ''));
			$from = (string) ($person['start_location'] ?? '');
			$to = (string) ($person['ending_location'] ?? '');
			$route = trim($from . ($to !== '' ? ' → ' . $to : ''));
			if ($route === '') {
				$route = (string) ($trip['route_name'] ?? '');
			}
			$status = (string) ($person['status'] ?? '');
			if ((float) ($person['amount_left'] ?? 0) > 0) {
				$status .= ' · balance due';
			}
			$ids[] = (int) ($person['id'] ?? 0);
			if ($kind === 'manifest_whole2') {
				$rows[] = [(string) $n, (string) ($person['seat_no'] ?? ''), $name, (string) ($person['phone_no'] ?? ''), $from, $to, $status, (string) ($person['cost'] ?? '')];
			} else {
				$rows[] = [(string) ($person['seat_no'] ?? ''), $name, (string) ($person['phone_no'] ?? ''), $route, $status, (string) ($person['cost'] ?? '')];
			}
		}
		$notes = $rows === [] ? 'No paid passengers on this trip.' : count($rows) . ' passengers';
		return tessera_prints_doc($kind, $titles[$kind] ?? 'Manifest', $meta, $columns, $rows, $notes, $ids, $selectable, [
			'route_day_id' => $id,
			'trip_date' => tessera_prints_date($date),
		]);
	}
}

if (!function_exists('tessera_prints_tickets_doc')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_prints_tickets_doc(mysqli $db, string $kind, array $query, string $username): array
	{
		$titles = [
			'ticket' => 'Ticket',
			'a4' => 'Ticket (A4)',
			'ticket_table' => 'Ticket',
			'booking_search' => 'Booking search',
			'bulk' => 'Bulk tickets',
			'multiple' => 'Tickets',
		];
		$bookings = [];
		$ids = tessera_prints_id_list((string) ($query['ids'] ?? ''));
		$id = (int) ($query['id'] ?? 0);
		if ($ids !== []) {
			foreach ($ids as $one) {
				$row = tessera_prints_booking($db, $one);
				if ($row) {
					$bookings[] = $row;
				}
			}
		} elseif ($kind === 'bulk') {
			$u = tessera_prints_esc($db, $username);
			$queued = tessera_prints_rows($db, "SELECT detail FROM printing WHERE attribute='{$u}'");
			foreach ($queued as $item) {
				$row = tessera_prints_booking($db, (int) ($item['detail'] ?? 0));
				if ($row && (string) ($row['issuer'] ?? '') === $username) {
					$bookings[] = $row;
				}
			}
		} elseif ($kind === 'multiple' && trim((string) ($query['print_id'] ?? '')) !== '') {
			$printId = tessera_prints_esc($db, (string) $query['print_id']);
			$alive = tessera_prints_alive();
			$bookings = tessera_prints_rows(
				$db,
				"SELECT id, seat_no, firstname, lastname, phone_no, trip_date, bus, issuer, booker, cost,
					status, start_route, end_route, route_id, route_day_id, print_id, currency,
					start_location, ending_location, book_date, book_time, sub_route_id, amount_left
				 FROM booking WHERE print_id='{$printId}' AND {$alive} ORDER BY seat_no ASC"
			);
		} elseif ($kind === 'booking_search' && trim((string) ($query['q'] ?? '')) !== '' && $id < 1) {
			$like = '%' . tessera_prints_esc($db, trim((string) $query['q'])) . '%';
			$alive = tessera_prints_alive();
			$bookings = tessera_prints_rows(
				$db,
				"SELECT id, seat_no, firstname, lastname, phone_no, trip_date, bus, issuer, booker, cost,
					status, start_route, end_route, route_id, route_day_id, print_id, currency,
					start_location, ending_location, book_date, book_time, sub_route_id, amount_left
				 FROM booking WHERE {$alive} AND (firstname LIKE '{$like}' OR lastname LIKE '{$like}'
					OR phone_no LIKE '{$like}' OR CAST(id AS CHAR) LIKE '{$like}')
				 ORDER BY id DESC LIMIT 40"
			);
		} elseif ($id > 0) {
			$row = tessera_prints_booking($db, $id);
			if ($row) {
				$bookings[] = $row;
			}
		}

		$columns = ['Seat', 'Passenger', 'Route', 'Date', 'Vehicle', 'Fare'];
		$rows = [];
		$rowIds = [];
		$meta = [];
		foreach ($bookings as $booking) {
			$block = tessera_prints_ticket_block($db, $booking);
			$rows[] = $block['line'];
			$rowIds[] = (int) ($booking['id'] ?? 0);
			if (count($bookings) === 1) {
				$meta = $block['meta'];
			}
		}
		$notes = '';
		if ($bookings === []) {
			$notes = $kind === 'bulk'
				? 'Nothing is queued to print for this sign-in.'
				: 'No ticket matched this link. Open it from the book list.';
		} elseif (count($bookings) > 1) {
			$notes = count($bookings) . ' tickets';
			$meta = [['label' => 'Tickets', 'value' => (string) count($bookings)]];
		}
		return tessera_prints_doc($kind, $titles[$kind] ?? 'Ticket', $meta, $columns, $rows, $notes, $rowIds, false);
	}
}

if (!function_exists('tessera_prints_seat_chart')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_prints_seat_chart(mysqli $db, array $query): array
	{
		$id = (int) ($query['id'] ?? $query['route_day_id'] ?? 0);
		$date = (string) ($query['trip_date'] ?? '');
		$trip = tessera_prints_trip($db, $id);
		if ($trip === null) {
			return tessera_prints_doc('seat_chart', 'Passenger seat chart', [], ['Seat', 'Passenger', 'Status'], [], 'A trip is required.');
		}
		$capacity = (int) ($trip['seats'] ?? 0);
		if ($capacity < 1) {
			$capacity = 49;
		}
		if ($capacity > 80) {
			$capacity = 80;
		}
		$taken = [];
		foreach (tessera_prints_passengers($db, $id, $date, false) as $person) {
			$seat = trim((string) ($person['seat_no'] ?? ''));
			if ($seat === '') {
				continue;
			}
			$taken[$seat] = trim((string) ($person['firstname'] ?? '') . ' ' . (string) ($person['lastname'] ?? ''));
		}
		$rows = [];
		for ($n = 1; $n <= $capacity; $n++) {
			$key = (string) $n;
			$name = $taken[$key] ?? '';
			$rows[] = [$key, $name !== '' ? $name : '—', $name !== '' ? 'Taken' : 'Free'];
		}
		$meta = [
			['label' => 'Vehicle', 'value' => (string) ($trip['bus'] ?? '')],
			['label' => 'Route', 'value' => (string) ($trip['route_name'] ?? '')],
			['label' => 'Trip date', 'value' => tessera_prints_date($date)],
			['label' => 'Taken', 'value' => (string) count($taken)],
		];
		return tessera_prints_doc('seat_chart', 'Passenger seat chart', $meta, ['Seat', 'Passenger', 'Status'], $rows, '', [], false, [
			'seats' => $rows,
		]);
	}
}

if (!function_exists('tessera_prints_vehicle_list')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_prints_vehicle_list(mysqli $db, string $username, array $query): array
	{
		$start = trim(str_replace('_', ' ', (string) ($query['starting'] ?? $query['start'] ?? '')));
		$end = trim(str_replace('_', ' ', (string) ($query['ending'] ?? $query['end'] ?? '')));
		$date = tessera_prints_date((string) ($query['date'] ?? $query['trip_date'] ?? ''));
		$u = tessera_prints_esc($db, $username);
		$where = "username='{$u}'";
		if ($start !== '') {
			$where .= " AND start_route='" . tessera_prints_esc($db, $start) . "'";
		}
		if ($end !== '') {
			$where .= " AND end_route='" . tessera_prints_esc($db, $end) . "'";
		}
		$assigned = tessera_prints_rows($db, "SELECT route_id, start_route, end_route, fare_price FROM assigned_routes WHERE {$where} LIMIT 20");
		$rows = [];
		$dayName = $date !== '' ? date('l', strtotime($date)) : '';
		foreach ($assigned as $route) {
			$routeId = tessera_prints_esc($db, (string) ($route['route_id'] ?? ''));
			$daySql = $dayName !== '' ? " AND day='" . tessera_prints_esc($db, $dayName) . "'" : '';
			$trips = tessera_prints_rows(
				$db,
				"SELECT id, bus, departure, `12hours` AS hours FROM route_day
				 WHERE route_id='{$routeId}' AND status='active' {$daySql} ORDER BY departure ASC LIMIT 12"
			);
			foreach ($trips as $trip) {
				$bus = (string) ($trip['bus'] ?? '');
				$capacity = 0;
				$fleet = tessera_prints_rows($db, "SELECT seats FROM vehicle WHERE name='" . tessera_prints_esc($db, $bus) . "' LIMIT 1");
				if ($fleet !== []) {
					$capacity = (int) ($fleet[0]['seats'] ?? 0);
				}
				$booked = 0;
				if ($date !== '') {
					$count = tessera_prints_rows(
						$db,
						"SELECT COUNT(*) AS c FROM booking WHERE " . tessera_prints_alive() . "
						 AND route_day_id=" . (int) $trip['id'] . " AND trip_date='" . tessera_prints_esc($db, $date) . "' AND status<>'booked'"
					);
					$booked = (int) ($count[0]['c'] ?? 0);
				}
				$free = $capacity > 0 ? max(0, $capacity - $booked) : 0;
				$rows[] = [
					$bus,
					(string) ($route['start_route'] ?? '') . ' → ' . (string) ($route['end_route'] ?? ''),
					(string) ($trip['hours'] ?? $trip['departure'] ?? ''),
					(string) $booked,
					$capacity > 0 ? (string) $free : '—',
				];
			}
		}
		$meta = [];
		if ($start !== '' || $end !== '' || $date !== '') {
			$meta = [
				['label' => 'From', 'value' => $start],
				['label' => 'To', 'value' => $end],
				['label' => 'Date', 'value' => $date],
			];
		}
		$notes = $rows === [] ? 'No active vehicles for this route and date.' : '';
		return tessera_prints_doc('vehicles', 'Vehicle transfer list', $meta, ['Vehicle', 'Route', 'Time', 'Booked', 'Free'], $rows, $notes);
	}
}

if (!function_exists('tessera_prints_physical')) {
	/**
	 * Hire / counter ticket sheet. Classic page prints a preview; it does not write bookings.
	 *
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_prints_physical(mysqli $db, array $query): array
	{
		$id = (int) ($query['id'] ?? $query['route_day_id'] ?? 0);
		$trip = $id > 0 ? tessera_prints_trip($db, $id) : null;
		$date = tessera_prints_date((string) ($query['date'] ?? $query['trip_date'] ?? ''));
		$upcoming = tessera_prints_rows(
			$db,
			"SELECT d.id, d.bus, d.route_id, d.departure, d.`12hours` AS hours
			 FROM route_day d WHERE d.status='active' ORDER BY d.id DESC LIMIT 25"
		);
		$trips = [];
		foreach ($upcoming as $row) {
			$trips[] = [
				'id' => (int) ($row['id'] ?? 0),
				'bus' => (string) ($row['bus'] ?? ''),
				'route' => tessera_prints_route_label($db, (string) ($row['route_id'] ?? '')),
				'route_id' => (string) ($row['route_id'] ?? ''),
				'departure' => (string) ($row['hours'] ?? $row['departure'] ?? ''),
			];
		}
		$seats = [];
		if ($trip) {
			$capacity = (int) ($trip['seats'] ?? 0);
			if ($capacity < 1) {
				$capacity = 45;
			}
			if ($capacity > 70) {
				$capacity = 70;
			}
			$taken = [];
			foreach (tessera_prints_passengers($db, $id, $date, false) as $person) {
				$taken[trim((string) ($person['seat_no'] ?? ''))] = true;
			}
			for ($n = 1; $n <= $capacity; $n++) {
				$seats[] = ['no' => (string) $n, 'taken' => !empty($taken[(string) $n])];
			}
		}
		$meta = [];
		if ($trip) {
			$meta = [
				['label' => 'Vehicle', 'value' => (string) ($query['bus'] ?? $trip['bus'] ?? '')],
				['label' => 'Route', 'value' => (string) ($query['route'] ?? $trip['route_name'] ?? '')],
				['label' => 'Date', 'value' => $date],
				['label' => 'Fare', 'value' => (string) ($query['fare'] ?? $trip['fare_price'] ?? '')],
			];
		}
		return tessera_prints_doc('physical', 'Physical booking', $meta, ['Seat', 'State'], [], $trip ? 'Select free seats, then print the hire sheet.' : 'Pick a trip to build a hire sheet.', [], false, [
			'trips' => $trips,
			'trip' => $trip ? [
				'id' => (int) ($trip['id'] ?? 0),
				'bus' => (string) ($query['bus'] ?? $trip['bus'] ?? ''),
				'route' => (string) ($query['route'] ?? $trip['route_name'] ?? ''),
				'date' => $date,
				'departure' => (string) ($query['time'] ?? $trip['hours'] ?? $trip['departure'] ?? ''),
				'fare' => (string) ($query['fare'] ?? $trip['fare_price'] ?? ''),
				'class_name' => (string) ($query['class'] ?? ''),
			] : null,
			'seats' => $seats,
		]);
	}
}

if (!function_exists('tessera_prints_luggage_doc')) {
	/**
	 * Ticket + luggage uses the parcel row classic print_ticket_luggage.php loaded by code.
	 *
	 * @return array<string,mixed>
	 */
	function tessera_prints_luggage_doc(mysqli $db, string $code): array
	{
		$code = trim($code);
		$row = null;
		if ($code !== '') {
			$c = tessera_prints_esc($db, $code);
			$found = tessera_prints_rows($db, "SELECT code, s_name, s_surname, s_no, r_name, r_surname, r_no, account, amount, due, paid, bus, trip_date, store, status, detail FROM parcel_info WHERE code='{$c}' LIMIT 1");
			$row = $found[0] ?? null;
			if ($row === null && function_exists('sr_db_config')) {
				$cfg = sr_db_config('cargo');
				$cargo = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], (int) $cfg['port']);
				if ($cargo instanceof mysqli) {
					mysqli_set_charset($cargo, 'utf8mb4');
					$found = tessera_prints_rows($cargo, "SELECT code, s_name, s_surname, s_no, r_name, r_surname, r_no, account, amount, due, paid, bus, trip_date, store, status, detail FROM parcel_info WHERE code='{$c}' LIMIT 1");
					$row = $found[0] ?? null;
					$cargo->close();
				}
			}
		}
		if ($row === null) {
			return tessera_prints_doc('ticket_luggage', 'Ticket and luggage', [], ['Field', 'Value'], [], 'No luggage matched this code.');
		}
		$detail = (string) ($row['detail'] ?? '');
		$decoded = base64_decode($detail, true);
		if ($decoded !== false && $decoded !== '') {
			$detail = $decoded;
		}
		$meta = [
			['label' => 'Code', 'value' => (string) ($row['code'] ?? '')],
			['label' => 'Sender', 'value' => trim((string) ($row['s_name'] ?? '') . ' ' . (string) ($row['s_surname'] ?? ''))],
			['label' => 'Sender phone', 'value' => (string) ($row['s_no'] ?? '')],
			['label' => 'Receiver', 'value' => trim((string) ($row['r_name'] ?? '') . ' ' . (string) ($row['r_surname'] ?? ''))],
			['label' => 'Receiver phone', 'value' => (string) ($row['r_no'] ?? '')],
			['label' => 'Route', 'value' => (string) ($row['account'] ?? '')],
			['label' => 'Vehicle', 'value' => (string) ($row['bus'] ?? '')],
			['label' => 'Trip date', 'value' => (string) ($row['trip_date'] ?? '')],
			['label' => 'Amount', 'value' => (string) ($row['amount'] ?? '')],
			['label' => 'Paid', 'value' => (string) ($row['paid'] ?? '')],
			['label' => 'Due', 'value' => (string) ($row['due'] ?? '')],
			['label' => 'Store', 'value' => (string) ($row['store'] ?? '')],
		];
		$rows = [['Description', $detail]];
		return tessera_prints_doc('ticket_luggage', 'Ticket and luggage', $meta, ['Field', 'Value'], $rows);
	}
}

if (!function_exists('tessera_prints_document')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_prints_document(mysqli $db, string $username, string $kind, array $query): array
	{
		$kind = strtolower(trim($kind));
		return match ($kind) {
			'pos', 'manifest' => tessera_prints_passenger_doc($db, $kind === 'pos' ? 'pos' : 'manifest', $query, false),
			'manifest_whole' => tessera_prints_passenger_doc($db, 'manifest_whole', $query, true),
			'manifest_whole2' => tessera_prints_passenger_doc($db, 'manifest_whole2', $query, true),
			'multiple_manifest' => tessera_prints_passenger_doc($db, 'multiple_manifest', $query, false),
			'ticket', 'a4', 'ticket_table', 'booking_search', 'bulk', 'multiple' => tessera_prints_tickets_doc($db, $kind, $query, $username),
			'seat_chart' => tessera_prints_seat_chart($db, $query),
			'vehicles' => tessera_prints_vehicle_list($db, $username, $query),
			'physical' => tessera_prints_physical($db, $query),
			'ticket_luggage' => tessera_prints_luggage_doc($db, (string) ($query['id'] ?? $query['code'] ?? '')),
			default => tessera_prints_doc($kind, 'Print', [], [], [], 'Unknown print.'),
		};
	}
}

if (!function_exists('tessera_prints_transfer_board')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_prints_transfer_board(mysqli $db, array $query): array
	{
		$sourceId = (int) ($query['route_day_id'] ?? $query['id'] ?? $query['source_route_day_id'] ?? 0);
		$date = (string) ($query['trip_date'] ?? $query['date'] ?? '');
		$trip = $sourceId > 0 ? tessera_prints_trip($db, $sourceId) : null;
		$passengers = [];
		if ($trip) {
			foreach (tessera_prints_passengers($db, $sourceId, $date, false) as $person) {
				$passengers[] = [
					'id' => (int) ($person['id'] ?? 0),
					'seat' => (string) ($person['seat_no'] ?? ''),
					'name' => trim((string) ($person['firstname'] ?? '') . ' ' . (string) ($person['lastname'] ?? '')),
					'phone' => (string) ($person['phone_no'] ?? ''),
					'status' => (string) ($person['status'] ?? ''),
					'fare' => (string) ($person['cost'] ?? ''),
				];
			}
		}
		$targets = [];
		$listed = tessera_prints_rows(
			$db,
			"SELECT id, bus, route_id, departure, `12hours` AS hours FROM route_day WHERE status='active' ORDER BY id DESC LIMIT 40"
		);
		foreach ($listed as $row) {
			$rid = (int) ($row['id'] ?? 0);
			if ($rid === $sourceId) {
				continue;
			}
			$targets[] = [
				'id' => $rid,
				'bus' => (string) ($row['bus'] ?? ''),
				'route' => tessera_prints_route_label($db, (string) ($row['route_id'] ?? '')),
				'route_id' => (string) ($row['route_id'] ?? ''),
				'departure' => (string) ($row['hours'] ?? $row['departure'] ?? ''),
			];
		}
		return [
			'source' => $trip ? [
				'id' => $sourceId,
				'bus' => (string) ($trip['bus'] ?? ''),
				'route' => (string) ($trip['route_name'] ?? ''),
				'route_id' => (string) ($trip['route_id'] ?? ''),
				'trip_date' => tessera_prints_date($date),
			] : null,
			'passengers' => $passengers,
			'targets' => $targets,
			'company' => tessera_prints_company(),
		];
	}
}

if (!function_exists('tessera_prints_manifest_touch')) {
	function tessera_prints_manifest_touch(mysqli $db, int $routeDayId, string $routeId, string $tripDate): void
	{
		$date = tessera_prints_date($tripDate);
		if ($routeDayId < 1 || $date === '') {
			return;
		}
		$alive = tessera_prints_alive();
		$d = tessera_prints_esc($db, $date);
		$r = tessera_prints_esc($db, $routeId);
		$count = tessera_prints_rows(
			$db,
			"SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS amount FROM booking
			 WHERE {$alive} AND route_day_id={$routeDayId} AND trip_date='{$d}' AND status<>'booked'"
		);
		$seats = (int) ($count[0]['c'] ?? 0);
		$amount = (float) ($count[0]['amount'] ?? 0);
		$db->query(
			"UPDATE manifest SET seats={$seats}, amount={$amount}
			 WHERE route_day_id={$routeDayId} AND trip_date='{$d}' AND route_id='{$r}'"
		);
	}
}

if (!function_exists('tessera_prints_transfer_apply')) {
	/**
	 * Move bookings onto another trip. Occupied target seats are left behind and reported.
	 *
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,moved?:int,skipped?:list<string>}
	 */
	function tessera_prints_transfer_apply(mysqli $db, string $username, array $input): array
	{
		$action = strtolower(trim((string) ($input['action'] ?? 'multiple')));
		if (!in_array($action, ['all', 'multiple', 'selected'], true)) {
			return ['ok' => false, 'error' => 'Action must be all, multiple, or selected'];
		}
		$sourceId = (int) ($input['source_route_day_id'] ?? $input['route_day_id'] ?? 0);
		$targetId = (int) ($input['target_route_day_id'] ?? 0);
		$sourceDate = tessera_prints_date((string) ($input['source_trip_date'] ?? $input['trip_date'] ?? ''));
		$targetDate = tessera_prints_date((string) ($input['target_trip_date'] ?? ''));
		if ($sourceId < 1 || $targetId < 1 || $sourceDate === '' || $targetDate === '') {
			return ['ok' => false, 'error' => 'Source trip, target trip, and both dates are required'];
		}
		if ($sourceId === $targetId && $sourceDate === $targetDate) {
			return ['ok' => false, 'error' => 'Pick a different trip'];
		}
		$target = tessera_prints_trip($db, $targetId);
		if ($target === null) {
			return ['ok' => false, 'error' => 'Target trip was not found'];
		}
		$wanted = tessera_prints_id_list(is_array($input['booking_ids'] ?? null)
			? implode(',', $input['booking_ids'])
			: (string) ($input['booking_ids'] ?? ''));
		$seatMap = [];
		if (is_array($input['seats'] ?? null)) {
			foreach ($input['seats'] as $key => $seat) {
				$seatMap[(int) $key] = trim((string) $seat);
			}
		}
		$people = tessera_prints_passengers($db, $sourceId, $sourceDate, false);
		if ($action !== 'all') {
			$people = array_values(array_filter($people, static function ($row) use ($wanted) {
				return in_array((int) ($row['id'] ?? 0), $wanted, true);
			}));
		}
		if ($people === []) {
			return ['ok' => false, 'error' => 'No passengers selected'];
		}
		$fare = (string) ($target['fare_price'] ?? '');
		$newBus = tessera_prints_esc($db, (string) ($target['bus'] ?? ''));
		$newRoute = tessera_prints_esc($db, (string) ($target['route_id'] ?? ''));
		$newDate = tessera_prints_esc($db, $targetDate);
		$alive = tessera_prints_alive();
		$moved = 0;
		$skipped = [];
		$nowDate = date('Y-m-d');
		$nowTime = date('H:i');
		$user = tessera_prints_esc($db, $username);
		$db->begin_transaction();
		try {
			foreach ($people as $person) {
				$bookingId = (int) ($person['id'] ?? 0);
				$seat = $seatMap[$bookingId] ?? (string) ($person['seat_no'] ?? '');
				$seat = trim($seat);
				if ($seat === '' || $bookingId < 1) {
					$skipped[] = (string) ($person['seat_no'] ?? $bookingId);
					continue;
				}
				$seatSql = tessera_prints_esc($db, $seat);
				$clash = tessera_prints_rows(
					$db,
					"SELECT id FROM booking WHERE {$alive} AND id<>{$bookingId} AND seat_no='{$seatSql}'
					 AND route_day_id={$targetId} AND trip_date='{$newDate}' LIMIT 1"
				);
				if ($clash !== []) {
					$skipped[] = $seat;
					continue;
				}
				$costSql = is_numeric($fare) && (float) $fare > 0
					? "'" . tessera_prints_esc($db, (string) $fare) . "'"
					: 'cost';
				$oldSeat = tessera_prints_esc($db, (string) ($person['seat_no'] ?? ''));
				$oldRoute = tessera_prints_esc($db, (string) ($person['route_id'] ?? ''));
				$oldDate = tessera_prints_esc($db, (string) ($person['trip_date'] ?? $sourceDate));
				$ok = $db->query(
					"UPDATE booking SET trip_date='{$newDate}', bus='{$newBus}', route_id='{$newRoute}',
						seat_no='{$seatSql}', route_day_id={$targetId}, cost={$costSql}
					 WHERE id={$bookingId} AND " . tessera_prints_alive()
				);
				if (!$ok) {
					throw new RuntimeException('Could not move seat ' . $seat);
				}
				$db->query(
					"INSERT INTO transit (ticket_id, old_route_day_id, old_route_id, old_trip_date, new_route_day_id, new_route_id, new_trip_date, old_seat, date_insert, time_insert, user, status)
					 VALUES ({$bookingId}, {$sourceId}, '{$oldRoute}', '{$oldDate}', {$targetId}, '{$newRoute}', '{$newDate}', '{$oldSeat}', '{$nowDate}', '{$nowTime}', '{$user}', 'Transfer')"
				);
				$moved++;
			}
			$sourceRoute = '';
			if ($people !== []) {
				$sourceRoute = (string) ($people[0]['route_id'] ?? '');
			}
			tessera_prints_manifest_touch($db, $sourceId, $sourceRoute, $sourceDate);
			tessera_prints_manifest_touch($db, $targetId, (string) ($target['route_id'] ?? ''), $targetDate);
			$db->commit();
		} catch (Throwable $e) {
			$db->rollback();
			return ['ok' => false, 'error' => $e->getMessage()];
		}
		if ($moved < 1) {
			return ['ok' => false, 'error' => 'Every selected seat is already taken on the target trip', 'skipped' => $skipped];
		}
		return ['ok' => true, 'moved' => $moved, 'skipped' => $skipped];
	}
}
