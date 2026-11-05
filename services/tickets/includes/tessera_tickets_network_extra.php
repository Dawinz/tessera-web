<?php
/**
 * Tickets setup screens 8–15: currency, route contacts, route transit,
 * traveling times on a trip template, and route assignment.
 *
 * Currency has no tenant column (classic table is shared). Contacts, transits,
 * templates, and assignments are limited to the current tenant's routes.
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_tickets_network.php';

if (!function_exists('tessera_extra_currencies_list')) {
	function tessera_extra_clip(string $value, int $max): string
	{
		$value = trim($value);
		if ($max < 1 || $value === '') {
			return '';
		}
		if (function_exists('mb_substr')) {
			return mb_substr($value, 0, $max, 'UTF-8');
		}
		return substr($value, 0, $max);
	}

	/** Empty string is allowed. Null means the value is not a clock time. */
	function tessera_extra_clock(string $raw): ?string
	{
		$raw = trim($raw);
		if ($raw === '') {
			return '';
		}
		if (!preg_match('/^(\d{1,2}):(\d{2})(?::\d{2})?\s*([AP]M)?$/i', $raw, $m)) {
			return null;
		}
		$hour = (int) $m[1];
		$minute = (int) $m[2];
		if ($minute > 59) {
			return null;
		}
		if (!empty($m[3])) {
			$ampm = strtoupper($m[3]);
			if ($hour < 1 || $hour > 12) {
				return null;
			}
			if ($ampm === 'AM') {
				$hour = $hour === 12 ? 0 : $hour;
			} else {
				$hour = $hour === 12 ? 12 : $hour + 12;
			}
		} elseif ($hour > 23) {
			return null;
		}
		return sprintf('%02d:%02d', $hour, $minute);
	}

	function tessera_extra_rate($value): int
	{
		$clean = preg_replace('/[^0-9.]+/', '', (string) $value);
		if ($clean === null || $clean === '' || $clean === '.') {
			return 1;
		}
		$n = (int) round((float) $clean);
		if ($n <= 0) {
			return 1;
		}
		if ($n > 2000000000) {
			return 2000000000;
		}
		return $n;
	}

	function tessera_extra_flag($value): bool
	{
		if (is_bool($value)) {
			return $value;
		}
		if (is_int($value)) {
			return $value === 1;
		}
		return in_array(strtolower(trim((string) $value)), ['1', 'true', 'on', 'yes'], true);
	}

	function tessera_extra_country_name(string $stored): string
	{
		$stored = trim($stored);
		if (preg_match('/^\([^)]*\)\s*(.+)$/', $stored, $m)) {
			return trim($m[1]);
		}
		return $stored;
	}

	function tessera_extra_compose_country(string $initial, string $countryName, bool $main): string
	{
		$countryName = trim($countryName);
		$initial = trim($initial);
		if ($main) {
			return tessera_extra_clip($countryName, 200);
		}
		return tessera_extra_clip('(' . $initial . ')' . $countryName, 200);
	}

	/** @param array<string,mixed> $row */
	function tessera_extra_currency_item(array $row): array
	{
		$stored = (string) ($row['country'] ?? '');
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'status' => trim((string) ($row['status'] ?? '')),
			'rate' => (int) ($row['rate'] ?? 0),
			'initial' => (string) ($row['initial'] ?? ''),
			'country' => tessera_extra_country_name($stored),
			'country_stored' => $stored,
			'country_label' => $stored,
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_extra_currency_bootstrap(mysqli $db, array $query = []): array
	{
		$rows = tessera_network_rows($db, "SELECT name FROM country WHERE name <> '' ORDER BY name ASC LIMIT 300");
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load countries', 500);
		}
		$countries = [];
		foreach ($rows as $row) {
			$countries[] = ['name' => (string) $row['name']];
		}
		return tessera_network_ok(['countries' => $countries]);
	}

	/** @param array<string,mixed> $query */
	function tessera_extra_currencies_list(mysqli $db, array $query): array
	{
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['name', 'country', 'initial', 'status']);
		$rows = tessera_network_rows(
			$db,
			'SELECT id, name, status, rate, initial, country FROM currency WHERE 1=1' . $like['sql'] . ' ORDER BY id DESC LIMIT 300',
			$like['types'],
			$like['params']
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load currencies', 500);
		}
		return tessera_network_ok([
			'items' => array_map('tessera_extra_currency_item', $rows),
			'total' => count($rows),
		]);
	}

	function tessera_extra_country_row(mysqli $db, string $name): ?array
	{
		$name = trim($name);
		if ($name === '') {
			return null;
		}
		return tessera_network_one($db, 'SELECT name FROM country WHERE name = ? LIMIT 1', 's', [$name]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_currency_save(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		$name = tessera_extra_clip((string) ($body['name'] ?? ''), 80);
		$initial = tessera_extra_clip((string) ($body['initial'] ?? ''), 30);
		$countryName = trim((string) ($body['country'] ?? ''));
		if ($name === '' || $initial === '' || $countryName === '') {
			return tessera_network_fail('validation_error', 'Enter a currency name, country, and initial.', 422);
		}
		$countryRow = tessera_extra_country_row($db, $countryName);
		if (!$countryRow) {
			return tessera_network_fail('validation_error', 'Choose a country from the list.', 422);
		}
		$countryName = (string) $countryRow['name'];
		$rate = tessera_extra_rate($body['rate'] ?? 1);
		$statusIn = trim((string) ($body['status'] ?? ''));

		if ($id > 0) {
			$existing = tessera_network_one($db, 'SELECT id, status FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
			if (!$existing) {
				return tessera_network_fail('not_found', 'Currency not found.', 404);
			}
			$storedStatus = (string) ($existing['status'] ?? '');
			if ($statusIn !== '' && strcasecmp($statusIn, trim($storedStatus)) !== 0) {
				return tessera_network_fail('validation_error', 'Currency status cannot be changed.', 422);
			}
			$isMain = strcasecmp(trim($storedStatus), 'Main') === 0;
			$storedCountry = tessera_extra_compose_country($initial, $countryName, $isMain);
			$dup = tessera_network_one(
				$db,
				'SELECT id FROM currency WHERE country = ? AND id <> ? LIMIT 1',
				'si',
				[$storedCountry, $id]
			);
			if ($dup) {
				return tessera_network_fail('conflict', 'That currency is already registered.', 409);
			}
			$exec = tessera_network_exec(
				$db,
				'UPDATE currency SET name = ?, rate = ?, initial = ?, country = ? WHERE id = ? LIMIT 1',
				'sissi',
				[$name, $rate, $initial, $storedCountry, $id]
			);
			if (!$exec['ok']) {
				return tessera_network_fail('server_error', 'Could not save currency', 500);
			}
			$saved = tessera_network_one($db, 'SELECT id, name, status, rate, initial, country FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
			return tessera_network_ok(['item' => $saved ? tessera_extra_currency_item($saved) : ['id' => $id]]);
		}

		if ($statusIn === '') {
			$statusIn = 'Normal';
		}
		if (strcasecmp($statusIn, 'Main') === 0) {
			return tessera_network_fail('validation_error', 'The main currency is already registered.', 422);
		}
		if (strcasecmp($statusIn, 'Normal') !== 0) {
			return tessera_network_fail('validation_error', 'Choose a currency status.', 422);
		}
		$storedCountry = tessera_extra_compose_country($initial, $countryName, false);
		$dup = tessera_network_one($db, 'SELECT id FROM currency WHERE country = ? LIMIT 1', 's', [$storedCountry]);
		if ($dup) {
			return tessera_network_fail('conflict', 'That currency is already registered.', 409);
		}
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO currency (name, status, rate, initial, country) VALUES (?,?,?,?,?)',
			'ssiss',
			[$name, 'Normal', $rate, $initial, $storedCountry]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save currency', 500);
		}
		$newId = (int) $db->insert_id;
		$saved = tessera_network_one($db, 'SELECT id, name, status, rate, initial, country FROM currency WHERE id = ? LIMIT 1', 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_extra_currency_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_currency_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a currency to delete.', 422);
		}
		$row = tessera_network_one($db, 'SELECT id FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$row) {
			return tessera_network_fail('not_found', 'Currency not found.', 404);
		}
		$exec = tessera_network_exec($db, 'DELETE FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete currency', 500);
		}
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @return array<string,mixed>|null */
	function tessera_extra_route(mysqli $db, int $pk): ?array
	{
		if ($pk < 1) {
			return null;
		}
		$tenant = tessera_sql_tenant_eq();
		return tessera_network_one(
			$db,
			"SELECT id, route_id, fullname, start_route, end_route, route_name, fare_price, detail, currency, currency_symbol, route_type
			 FROM routes WHERE id = ? AND {$tenant} LIMIT 1",
			'i',
			[$pk]
		);
	}

	/** @param array<string,mixed> $row */
	function tessera_extra_contact_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'name' => (string) ($row['name'] ?? ''),
			'contact' => (string) ($row['contact'] ?? ''),
			'route_id' => (string) ($row['route_id'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_extra_contacts_list(mysqli $db, array $query): array
	{
		$tenantR = tessera_sql_tenant_eq('r');
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['c.name', 'c.contact', 'c.route_name']);
		$types = $like['types'];
		$params = $like['params'];
		$extra = '';
		$routePk = (int) ($query['route_pk'] ?? $query['route_id'] ?? 0);
		if ($routePk > 0) {
			$route = tessera_extra_route($db, $routePk);
			if (!$route) {
				return tessera_network_fail('not_found', 'Route not found.', 404);
			}
			$extra .= ' AND c.route_id = ?';
			$types .= 's';
			$params[] = (string) (int) $route['route_id'];
		}
		$rows = tessera_network_rows(
			$db,
			"SELECT c.id, c.route_name, c.name, c.contact, c.route_id
			 FROM route_contact c
			 WHERE EXISTS (SELECT 1 FROM routes r WHERE r.route_id = c.route_id AND {$tenantR})"
			. $extra . $like['sql'] . ' ORDER BY c.id DESC LIMIT 500',
			$types,
			$params
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load route contacts', 500);
		}
		return tessera_network_ok([
			'items' => array_map('tessera_extra_contact_item', $rows),
			'total' => count($rows),
		]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_contact_save(mysqli $db, array $body): array
	{
		$route = tessera_extra_route($db, (int) ($body['route_pk'] ?? $body['route_id'] ?? 0));
		$name = tessera_extra_clip((string) ($body['name'] ?? ''), 100);
		$contact = tessera_extra_clip((string) ($body['contact'] ?? ''), 100);
		if (!$route || $name === '' || $contact === '') {
			return tessera_network_fail('validation_error', 'Choose a route, a name, and a phone number.', 422);
		}
		$code = (string) (int) $route['route_id'];
		$routeName = tessera_extra_clip((string) ($route['fullname'] ?? ''), 100);
		$dup = tessera_network_one(
			$db,
			'SELECT id FROM route_contact WHERE route_id = ? AND contact = ? LIMIT 1',
			'ss',
			[$code, $contact]
		);
		if ($dup) {
			return tessera_network_fail('conflict', 'That contact is already on this route.', 409);
		}
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO route_contact (route_name, name, contact, route_id) VALUES (?,?,?,?)',
			'ssss',
			[$routeName, $name, $contact, $code]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save route contact', 500);
		}
		$newId = (int) $db->insert_id;
		$saved = tessera_network_one($db, 'SELECT id, route_name, name, contact, route_id FROM route_contact WHERE id = ? LIMIT 1', 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_extra_contact_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_contact_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a contact to delete.', 422);
		}
		$tenantR = tessera_sql_tenant_eq('r');
		$row = tessera_network_one(
			$db,
			"SELECT c.id FROM route_contact c
			 WHERE c.id = ? AND EXISTS (SELECT 1 FROM routes r WHERE r.route_id = c.route_id AND {$tenantR})
			 LIMIT 1",
			'i',
			[$id]
		);
		if (!$row) {
			return tessera_network_fail('not_found', 'Route contact not found.', 404);
		}
		$exec = tessera_network_exec($db, 'DELETE FROM route_contact WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete route contact', 500);
		}
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_extra_transit_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'route_prev' => (string) ($row['route_prev'] ?? ''),
			'route_id' => (int) ($row['route_id'] ?? 0),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_extra_transits_list(mysqli $db, array $query): array
	{
		$tenantR = tessera_sql_tenant_eq('r');
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['t.route_name', 't.route_prev']);
		$types = $like['types'];
		$params = $like['params'];
		$extra = '';
		$routePk = (int) ($query['route_pk'] ?? 0);
		if ($routePk > 0) {
			$route = tessera_extra_route($db, $routePk);
			if (!$route) {
				return tessera_network_fail('not_found', 'Route not found.', 404);
			}
			$extra .= ' AND t.route_id = ?';
			$types .= 'i';
			$params[] = (int) $route['route_id'];
		}
		$rows = tessera_network_rows(
			$db,
			"SELECT t.id, t.route_name, t.route_prev, t.route_id
			 FROM route_transit t
			 WHERE EXISTS (SELECT 1 FROM routes r WHERE r.route_id = t.route_id AND {$tenantR})"
			. $extra . $like['sql'] . ' ORDER BY t.id DESC LIMIT 500',
			$types,
			$params
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load route transits', 500);
		}
		return tessera_network_ok([
			'items' => array_map('tessera_extra_transit_item', $rows),
			'total' => count($rows),
		]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_transit_save(mysqli $db, array $body): array
	{
		$route = tessera_extra_route($db, (int) ($body['route_pk'] ?? 0));
		$other = tessera_extra_route($db, (int) ($body['transit_route_pk'] ?? $body['transit_pk'] ?? 0));
		if (!$route || !$other) {
			return tessera_network_fail('validation_error', 'Choose the route and the route it continues onto.', 422);
		}
		if ((int) $route['id'] === (int) $other['id']) {
			return tessera_network_fail('validation_error', 'Choose a different route to continue onto.', 422);
		}
		$routeName = tessera_extra_clip((string) ($route['fullname'] ?? ''), 100);
		$routePrev = tessera_extra_clip((string) ($other['fullname'] ?? ''), 100);
		if ($routeName === '' || $routePrev === '') {
			return tessera_network_fail('validation_error', 'Both routes need a name.', 422);
		}
		$code = (int) $route['route_id'];
		$dup = tessera_network_one(
			$db,
			'SELECT id FROM route_transit WHERE route_name = ? AND route_prev = ? LIMIT 1',
			'ss',
			[$routeName, $routePrev]
		);
		if ($dup) {
			return tessera_network_fail('conflict', 'That transit is already registered.', 409);
		}
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO route_transit (route_prev, route_id, route_name) VALUES (?,?,?)',
			'sis',
			[$routePrev, $code, $routeName]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save route transit', 500);
		}
		$newId = (int) $db->insert_id;
		$badge = '<span class="badge bg-danger">Transit</span>';
		$tenant = tessera_sql_tenant_eq();
		tessera_network_exec(
			$db,
			"UPDATE routes SET transit = ? WHERE id = ? AND {$tenant} LIMIT 1",
			'si',
			[$badge, (int) $route['id']]
		);
		$saved = tessera_network_one($db, 'SELECT id, route_name, route_prev, route_id FROM route_transit WHERE id = ? LIMIT 1', 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_extra_transit_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_transit_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a transit to delete.', 422);
		}
		$tenantR = tessera_sql_tenant_eq('r');
		$row = tessera_network_one(
			$db,
			"SELECT t.id, t.route_id FROM route_transit t
			 WHERE t.id = ? AND EXISTS (SELECT 1 FROM routes r WHERE r.route_id = t.route_id AND {$tenantR})
			 LIMIT 1",
			'i',
			[$id]
		);
		if (!$row) {
			return tessera_network_fail('not_found', 'Route transit not found.', 404);
		}
		$code = (int) ($row['route_id'] ?? 0);
		$exec = tessera_network_exec($db, 'DELETE FROM route_transit WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete route transit', 500);
		}
		$left = tessera_network_one($db, 'SELECT id FROM route_transit WHERE route_id = ? LIMIT 1', 'i', [$code]);
		if (!$left) {
			$tenant = tessera_sql_tenant_eq();
			tessera_network_exec($db, "UPDATE routes SET transit = '' WHERE route_id = ? AND {$tenant}", 'i', [$code]);
		}
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_extra_template_item(array $row): array
	{
		$fullname = trim((string) ($row['fullname'] ?? ''));
		$routeName = trim((string) ($row['route_name'] ?? ''));
		$day = (string) ($row['day'] ?? '');
		$bus = (string) ($row['bus'] ?? '');
		$labelName = $fullname !== '' ? $fullname : $routeName;
		return [
			'id' => (int) ($row['id'] ?? 0),
			'day' => $day,
			'bus' => $bus,
			'route_name' => $routeName,
			'fullname' => $fullname,
			'route_id' => (int) ($row['route_id'] ?? 0),
			'label' => trim($labelName . ' · ' . $day . ' · ' . $bus, ' ·'),
		];
	}

	/** @param array<string,mixed> $row */
	function tessera_extra_time_item(array $row): array
	{
		$departure = (string) ($row['deputure'] ?? '');
		return [
			'id' => (int) ($row['id'] ?? 0),
			'point_id' => (int) ($row['point_id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'status' => (string) ($row['status'] ?? ''),
			'route_type' => (string) ($row['route_type'] ?? ''),
			'departure' => $departure,
			'deputure' => $departure,
			'arrival' => (string) ($row['arrival'] ?? ''),
			'reporting_time' => (string) ($row['reporting_time'] ?? ''),
		];
	}

	/** @return list<array<string,mixed>>|null */
	function tessera_extra_time_rows(mysqli $db, int $dayId): ?array
	{
		return tessera_network_rows(
			$db,
			'SELECT id, route_id, point_id, name, status, route_type, deputure, arrival, reporting_time
			 FROM traveling_time WHERE route_day_id = ? ORDER BY id ASC',
			'i',
			[$dayId]
		);
	}

	/**
	 * Create missing traveling_time rows for this template from its route points
	 * (and route_time defaults). Edits stay on the chosen route_day.
	 *
	 * @param array<string,mixed> $day
	 */
	function tessera_extra_ensure_traveling_points(mysqli $db, array $day): void
	{
		$dayId = (int) ($day['id'] ?? 0);
		$ref = (int) ($day['route_id'] ?? 0);
		if ($dayId < 1) {
			return;
		}
		$tenant = tessera_sql_tenant_eq();
		$route = tessera_network_one(
			$db,
			"SELECT id, route_id, route_type, fullname FROM routes WHERE id = ? AND {$tenant} LIMIT 1",
			'i',
			[$ref]
		);
		if (!$route) {
			$route = tessera_network_one(
				$db,
				"SELECT id, route_id, route_type, fullname FROM routes WHERE route_id = ? AND {$tenant} LIMIT 1",
				'i',
				[$ref]
			);
		}
		if (!$route) {
			return;
		}
		$code = (string) (int) $route['route_id'];
		$points = tessera_network_rows(
			$db,
			'SELECT id, name, point, time, arrival FROM route_points WHERE route_id = ? ORDER BY id ASC',
			's',
			[$code]
		);
		if ($points === null) {
			return;
		}
		$routeType = trim((string) ($route['route_type'] ?? ''));
		if ($routeType === '') {
			$routeType = 'Main Route';
		}
		foreach ($points as $point) {
			$pointId = (int) ($point['id'] ?? 0);
			if ($pointId < 1) {
				continue;
			}
			$existing = tessera_network_one(
				$db,
				'SELECT id FROM traveling_time WHERE route_day_id = ? AND point_id = ? LIMIT 1',
				'ii',
				[$dayId, $pointId]
			);
			if ($existing) {
				continue;
			}
			$rt = tessera_network_one(
				$db,
				'SELECT deputure, arrival, reporting_time FROM route_time WHERE point_id = ? ORDER BY id DESC LIMIT 1',
				'i',
				[$pointId]
			);
			$depRaw = (string) ($rt['deputure'] ?? $point['time'] ?? '');
			$arrRaw = (string) ($rt['arrival'] ?? $point['arrival'] ?? '');
			$repRaw = (string) ($rt['reporting_time'] ?? '');
			$dep = tessera_extra_clock($depRaw) ?? '';
			$arr = tessera_extra_clock($arrRaw) ?? '';
			$rep = tessera_extra_clock($repRaw) ?? '';
			$kind = strtolower(trim((string) ($point['point'] ?? '')));
			$status = ($kind === 'end' || $kind === 'arrival') ? 'arrival' : 'Departure';
			$name = tessera_extra_clip((string) ($point['name'] ?? ''), 100);
			tessera_network_exec(
				$db,
				'INSERT INTO traveling_time (route_id, route_day_id, deputure, arrival, reporting_time, point_id, status, route_type, main_id, name)
				 VALUES (?,?,?,?,?,?,?,?,?,?)',
				'iisssissss',
				[$ref, $dayId, $dep, $arr, $rep, $pointId, $status, $routeType, '0', $name]
			);
		}
	}

	/** @param array<string,mixed> $query */
	function tessera_extra_traveling_list(mysqli $db, array $query): array
	{
		$tenant = tessera_sql_tenant_eq();
		$templates = tessera_network_rows(
			$db,
			"SELECT id, day, bus, route_name, fullname, route_id
			 FROM route_day WHERE {$tenant}
			 ORDER BY position ASC, fullname ASC, id DESC LIMIT 400"
		);
		if ($templates === null) {
			return tessera_network_fail('server_error', 'Could not load trip templates', 500);
		}
		$dayId = (int) ($query['route_day_id'] ?? 0);
		$dayMeta = null;
		$items = [];
		if ($dayId > 0) {
			$day = tessera_network_one(
				$db,
				"SELECT id, day, bus, route_name, fullname, route_id FROM route_day WHERE id = ? AND {$tenant} LIMIT 1",
				'i',
				[$dayId]
			);
			if (!$day) {
				return tessera_network_fail('not_found', 'Trip template not found.', 404);
			}
			tessera_extra_ensure_traveling_points($db, $day);
			$rows = tessera_extra_time_rows($db, $dayId);
			if ($rows === null) {
				return tessera_network_fail('server_error', 'Could not load traveling times', 500);
			}
			$dayMeta = tessera_extra_template_item($day);
			$items = array_map('tessera_extra_time_item', $rows);
		}
		return tessera_network_ok([
			'templates' => array_map('tessera_extra_template_item', $templates),
			'route_day_id' => $dayId,
			'route_day' => $dayMeta,
			'items' => $items,
		]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_traveling_save(mysqli $db, array $body): array
	{
		$dayId = (int) ($body['route_day_id'] ?? 0);
		$tenant = tessera_sql_tenant_eq();
		$day = tessera_network_one(
			$db,
			"SELECT id, route_id FROM route_day WHERE id = ? AND {$tenant} LIMIT 1",
			'i',
			[$dayId]
		);
		if (!$day) {
			return tessera_network_fail('not_found', 'Trip template not found.', 404);
		}
		$points = $body['points'] ?? null;
		if (!is_array($points) || $points === []) {
			$singleId = (int) ($body['id'] ?? 0);
			if ($singleId > 0) {
				$points = [$body];
			}
		}
		if (!is_array($points) || $points === []) {
			return tessera_network_fail('validation_error', 'Choose a trip and at least one stop.', 422);
		}
		if (count($points) > 200) {
			return tessera_network_fail('validation_error', 'Too many stops in one save.', 422);
		}
		foreach ($points as $point) {
			if (!is_array($point)) {
				return tessera_network_fail('validation_error', 'Stop times are incomplete.', 422);
			}
			$id = (int) ($point['id'] ?? 0);
			if ($id < 1) {
				return tessera_network_fail('validation_error', 'Open the trip before saving times.', 422);
			}
			$depIn = array_key_exists('departure', $point) ? (string) $point['departure'] : (string) ($point['deputure'] ?? '');
			$arrIn = (string) ($point['arrival'] ?? '');
			$repIn = (string) ($point['reporting_time'] ?? '');
			$dep = tessera_extra_clock($depIn);
			$arr = tessera_extra_clock($arrIn);
			$rep = tessera_extra_clock($repIn);
			if ($dep === null || $arr === null || $rep === null) {
				return tessera_network_fail('validation_error', 'Use 24-hour times like 07:15.', 422);
			}
			$row = tessera_network_one(
				$db,
				'SELECT id FROM traveling_time WHERE id = ? AND route_day_id = ? LIMIT 1',
				'ii',
				[$id, $dayId]
			);
			if (!$row) {
				return tessera_network_fail('not_found', 'Stop time not found on this trip.', 404);
			}
			$exec = tessera_network_exec(
				$db,
				'UPDATE traveling_time SET deputure = ?, arrival = ?, reporting_time = ? WHERE id = ? AND route_day_id = ? LIMIT 1',
				'sssii',
				[$dep, $arr, $rep, $id, $dayId]
			);
			if (!$exec['ok']) {
				return tessera_network_fail('server_error', 'Could not save traveling times', 500);
			}
		}
		$rows = tessera_extra_time_rows($db, $dayId) ?? [];
		return tessera_network_ok([
			'route_day_id' => $dayId,
			'items' => array_map('tessera_extra_time_item', $rows),
		]);
	}

	/** @param array<string,mixed> $query */
	function tessera_extra_assign_list(mysqli $db, array $query): array
	{
		$username = tessera_extra_clip((string) ($query['username'] ?? ''), 78);
		$users = tessera_network_rows(
			$db,
			"SELECT username FROM users WHERE status = 'active' AND username <> '' ORDER BY username ASC LIMIT 500"
		);
		if ($users === null) {
			return tessera_network_fail('server_error', 'Could not load staff', 500);
		}
		if ($username !== '') {
			$known = tessera_network_one(
				$db,
				"SELECT username FROM users WHERE username = ? AND status = 'active' LIMIT 1",
				's',
				[$username]
			);
			if (!$known) {
				return tessera_network_fail('not_found', 'Choose an active staff member.', 404);
			}
		}
		$tenant = tessera_sql_tenant_eq();
		$routes = tessera_network_rows(
			$db,
			"SELECT id, route_id, fullname, route_name, start_route, end_route
			 FROM routes WHERE {$tenant} ORDER BY fullname ASC LIMIT 500"
		);
		if ($routes === null) {
			return tessera_network_fail('server_error', 'Could not load routes', 500);
		}
		$assigned = [];
		if ($username !== '') {
			$links = tessera_network_rows($db, 'SELECT route_id FROM assigned_routes WHERE username = ?', 's', [$username]) ?? [];
			foreach ($links as $link) {
				$assigned[(int) ($link['route_id'] ?? 0)] = true;
			}
		}
		$userItems = [];
		foreach ($users as $user) {
			$userItems[] = ['username' => (string) $user['username']];
		}
		$items = [];
		foreach ($routes as $route) {
			$code = (int) ($route['route_id'] ?? 0);
			$items[] = [
				'id' => (int) ($route['id'] ?? 0),
				'route_id' => $code,
				'fullname' => (string) ($route['fullname'] ?? ''),
				'route_name' => (string) ($route['route_name'] ?? ''),
				'start_route' => (string) ($route['start_route'] ?? ''),
				'end_route' => (string) ($route['end_route'] ?? ''),
				'assigned' => $username !== '' && !empty($assigned[$code]),
			];
		}
		return tessera_network_ok([
			'username' => $username,
			'users' => $userItems,
			'routes' => $items,
		]);
	}

	/** @param array<string,mixed> $body */
	function tessera_extra_assign_save(mysqli $db, array $body): array
	{
		$username = tessera_extra_clip((string) ($body['username'] ?? ''), 78);
		$route = tessera_extra_route($db, (int) ($body['route_pk'] ?? $body['id'] ?? 0));
		if ($username === '' || !$route || !array_key_exists('assigned', $body)) {
			return tessera_network_fail('validation_error', 'Choose a staff member and a route.', 422);
		}
		$known = tessera_network_one(
			$db,
			"SELECT username FROM users WHERE username = ? AND status = 'active' LIMIT 1",
			's',
			[$username]
		);
		if (!$known) {
			return tessera_network_fail('not_found', 'Choose an active staff member.', 404);
		}
		$code = (int) $route['route_id'];
		$on = tessera_extra_flag($body['assigned']);
		$existing = tessera_network_one(
			$db,
			'SELECT id FROM assigned_routes WHERE username = ? AND route_id = ? LIMIT 1',
			'si',
			[$username, $code]
		);
		if ($on) {
			if (!$existing) {
				$any = tessera_network_one($db, 'SELECT id FROM assigned_routes WHERE username = ? LIMIT 1', 's', [$username]);
				$selected = $any ? 'false' : 'true';
				$active = $any ? '' : 'active';
				$tab = $any ? '' : 'show active';
				$exec = tessera_network_exec(
					$db,
					'INSERT INTO assigned_routes (route_id, start_route, end_route, username, fare_price, selected, active, active_tab, detail, route_name)
					 VALUES (?,?,?,?,?,?,?,?,?,?)',
					'isssisssss',
					[
						$code,
						tessera_extra_clip((string) ($route['start_route'] ?? ''), 200),
						tessera_extra_clip((string) ($route['end_route'] ?? ''), 20),
						$username,
						(int) ($route['fare_price'] ?? 0),
						$selected,
						$active,
						$tab,
						(string) ($route['detail'] ?? ''),
						tessera_extra_clip((string) ($route['route_name'] ?? ''), 100),
					]
				);
				if (!$exec['ok']) {
					return tessera_network_fail('server_error', 'Could not assign the route', 500);
				}
			}
		} elseif ($existing) {
			$exec = tessera_network_exec(
				$db,
				'DELETE FROM assigned_routes WHERE username = ? AND route_id = ?',
				'si',
				[$username, $code]
			);
			if (!$exec['ok']) {
				return tessera_network_fail('server_error', 'Could not remove the route', 500);
			}
		}
		return tessera_network_ok([
			'assigned' => $on,
			'username' => $username,
			'route_pk' => (int) $route['id'],
			'route_id' => $code,
		]);
	}
}
