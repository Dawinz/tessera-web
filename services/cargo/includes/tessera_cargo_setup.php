<?php
/**
 * Cargo setup: currency, routes + locations, route assignment, cargo fleet.
 * Staff accounts are created in Settings (same write-through as Tickets sign up).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_setup_clip')) {
	function tessera_cargo_setup_clip(string $value, int $max): string
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

	function tessera_cargo_setup_tenant(): int
	{
		if (function_exists('tessera_current_tenant_id')) {
			$id = (int) tessera_current_tenant_id();
			if ($id > 0) {
				return $id;
			}
		}
		return function_exists('tessera_default_tenant_id') ? (int) tessera_default_tenant_id() : 1;
	}

	function tessera_cargo_setup_fail(string $message, int $status = 422): array
	{
		return ['ok' => false, 'error' => $message, 'status' => $status];
	}

	/** @param array<string,mixed> $data */
	function tessera_cargo_setup_ok(array $data): array
	{
		$data['ok'] = true;
		return $data;
	}

	function tessera_cargo_setup_finish(array $result): void
	{
		if (empty($result['ok'])) {
			$status = (int) ($result['status'] ?? 422);
			if ($status === 404) {
				$code = 'not_found';
			} elseif ($status === 409) {
				$code = 'conflict';
			} elseif ($status >= 500) {
				$code = 'server_error';
			} else {
				$code = 'validation_error';
			}
			tessera_console_error($code, (string) ($result['error'] ?? 'Request failed'), $status);
		}
		unset($result['ok']);
		tessera_console_ok($result);
	}

	/** @return array<string,mixed> */
	function tessera_cargo_setup_read_body(): array
	{
		$raw = file_get_contents('php://input');
		if ($raw === false || trim($raw) === '') {
			return [];
		}
		$payload = json_decode($raw, true);
		if (!is_array($payload)) {
			tessera_console_error('validation_error', 'Invalid JSON body', 422);
		}
		return $payload;
	}

	function tessera_cargo_setup_rate($value): int
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

	function tessera_cargo_setup_flag($value): bool
	{
		if (is_bool($value)) {
			return $value;
		}
		if (is_int($value)) {
			return $value === 1;
		}
		return in_array(strtolower(trim((string) $value)), ['1', 'true', 'on', 'yes'], true);
	}

	function tessera_cargo_setup_decode(string $stored): string
	{
		$stored = trim($stored);
		if ($stored === '') {
			return '';
		}
		$decoded = base64_decode($stored, true);
		if ($decoded === false || $decoded === '') {
			return $stored;
		}
		if (!preg_match('//u', $decoded) || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $decoded)) {
			return $stored;
		}
		return $decoded;
	}

	function tessera_cargo_setup_country_name(string $stored): string
	{
		$stored = trim($stored);
		if (preg_match('/^\([^)]*\)\s*(.+)$/', $stored, $m)) {
			return trim($m[1]);
		}
		return $stored;
	}

	function tessera_cargo_setup_compose_country(string $initial, string $countryName, bool $main): string
	{
		$countryName = trim($countryName);
		$initial = trim($initial);
		if ($main) {
			return tessera_cargo_setup_clip($countryName, 200);
		}
		return tessera_cargo_setup_clip('(' . $initial . ')' . $countryName, 200);
	}

	/**
	 * @param list<mixed> $params
	 */
	function tessera_cargo_setup_stmt(mysqli $db, string $sql, string $types, array $params): ?mysqli_stmt
	{
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			return null;
		}
		if ($types !== '') {
			$refs = [$types];
			foreach ($params as $i => $_) {
				$refs[] = &$params[$i];
			}
			if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
				$stmt->close();
				return null;
			}
		}
		if (!$stmt->execute()) {
			$stmt->close();
			return null;
		}
		return $stmt;
	}

	/**
	 * @param list<mixed> $params
	 * @return list<array<string,mixed>>|null
	 */
	function tessera_cargo_setup_all(mysqli $db, string $sql, string $types = '', array $params = []): ?array
	{
		$stmt = tessera_cargo_setup_stmt($db, $sql, $types, $params);
		if (!$stmt) {
			return null;
		}
		$res = $stmt->get_result();
		$rows = [];
		if ($res) {
			while ($row = $res->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		$stmt->close();
		return $rows;
	}

	/**
	 * @param list<mixed> $params
	 * @return array<string,mixed>|null
	 */
	function tessera_cargo_setup_one(mysqli $db, string $sql, string $types = '', array $params = []): ?array
	{
		$rows = tessera_cargo_setup_all($db, $sql, $types, $params);
		if ($rows === null) {
			return null;
		}
		return $rows[0] ?? null;
	}

	/**
	 * @param list<mixed> $params
	 */
	function tessera_cargo_setup_exec(mysqli $db, string $sql, string $types = '', array $params = []): bool
	{
		$stmt = tessera_cargo_setup_stmt($db, $sql, $types, $params);
		if (!$stmt) {
			return false;
		}
		$stmt->close();
		return true;
	}

	function tessera_cargo_setup_like(string $q): string
	{
		$q = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], trim($q));
		return '%' . $q . '%';
	}

	/** @param array<string,mixed> $row */
	function tessera_cargo_setup_currency_item(array $row): array
	{
		$stored = (string) ($row['country'] ?? '');
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'status' => trim((string) ($row['status'] ?? '')),
			'rate' => (int) ($row['rate'] ?? 0),
			'initial' => (string) ($row['initial'] ?? ''),
			'country' => tessera_cargo_setup_country_name($stored),
			'country_stored' => $stored,
			'country_label' => $stored,
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_cargo_setup_currency_bootstrap(mysqli $db, array $query = []): array
	{
		$rows = tessera_cargo_setup_all($db, "SELECT name FROM country WHERE name <> '' ORDER BY name ASC LIMIT 300");
		if ($rows === null) {
			return tessera_cargo_setup_fail('Could not load countries.', 500);
		}
		$countries = [];
		foreach ($rows as $row) {
			$countries[] = ['name' => (string) $row['name']];
		}
		return tessera_cargo_setup_ok(['countries' => $countries]);
	}

	/** @param array<string,mixed> $query */
	function tessera_cargo_setup_currencies(mysqli $db, array $query): array
	{
		$q = trim((string) ($query['q'] ?? ''));
		$sql = 'SELECT id, name, status, rate, initial, country FROM currency';
		$types = '';
		$params = [];
		if ($q !== '') {
			$like = tessera_cargo_setup_like($q);
			$sql .= ' WHERE name LIKE ? OR country LIKE ? OR initial LIKE ? OR status LIKE ?';
			$types = 'ssss';
			$params = [$like, $like, $like, $like];
		}
		$sql .= ' ORDER BY id DESC LIMIT 300';
		$rows = tessera_cargo_setup_all($db, $sql, $types, $params);
		if ($rows === null) {
			return tessera_cargo_setup_fail('Could not load currencies.', 500);
		}
		$items = [];
		foreach ($rows as $row) {
			$items[] = tessera_cargo_setup_currency_item($row);
		}
		return tessera_cargo_setup_ok(['items' => $items, 'total' => count($items)]);
	}

	function tessera_cargo_setup_country_row(mysqli $db, string $name): ?array
	{
		$name = trim($name);
		if ($name === '') {
			return null;
		}
		return tessera_cargo_setup_one($db, 'SELECT name FROM country WHERE name = ? LIMIT 1', 's', [$name]);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_currency_save(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		$name = tessera_cargo_setup_clip((string) ($body['name'] ?? ''), 80);
		$initial = tessera_cargo_setup_clip((string) ($body['initial'] ?? ''), 30);
		$countryName = trim((string) ($body['country'] ?? ''));
		if ($name === '' || $initial === '' || $countryName === '') {
			return tessera_cargo_setup_fail('Enter a currency name, country, and initial.');
		}
		$countryRow = tessera_cargo_setup_country_row($db, $countryName);
		if (!$countryRow) {
			return tessera_cargo_setup_fail('Choose a country from the list.');
		}
		$countryName = (string) $countryRow['name'];
		$rate = tessera_cargo_setup_rate($body['rate'] ?? 1);

		if ($id > 0) {
			$existing = tessera_cargo_setup_one($db, 'SELECT id, status FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
			if (!$existing) {
				return tessera_cargo_setup_fail('Currency not found.', 404);
			}
			$storedStatus = (string) ($existing['status'] ?? '');
			$statusIn = trim((string) ($body['status'] ?? ''));
			if ($statusIn !== '' && strcasecmp($statusIn, trim($storedStatus)) !== 0) {
				return tessera_cargo_setup_fail('Currency status cannot be changed.');
			}
			$isMain = strcasecmp(trim($storedStatus), 'Main') === 0;
			$storedCountry = tessera_cargo_setup_compose_country($initial, $countryName, $isMain);
			$dup = tessera_cargo_setup_one($db, 'SELECT id FROM currency WHERE country = ? AND id <> ? LIMIT 1', 'si', [$storedCountry, $id]);
			if ($dup) {
				return tessera_cargo_setup_fail('That currency is already registered.', 409);
			}
			$ok = tessera_cargo_setup_exec(
				$db,
				'UPDATE currency SET name = ?, rate = ?, initial = ?, country = ? WHERE id = ? LIMIT 1',
				'sissi',
				[$name, $rate, $initial, $storedCountry, $id]
			);
			if (!$ok) {
				return tessera_cargo_setup_fail('Could not save currency.', 500);
			}
			$saved = tessera_cargo_setup_one($db, 'SELECT id, name, status, rate, initial, country FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
			return tessera_cargo_setup_ok(['item' => $saved ? tessera_cargo_setup_currency_item($saved) : ['id' => $id]]);
		}

		$statusIn = trim((string) ($body['status'] ?? ''));
		if ($statusIn === '') {
			$statusIn = 'Normal';
		}
		if (strcasecmp($statusIn, 'Main') === 0) {
			return tessera_cargo_setup_fail('The main currency is already registered.');
		}
		if (strcasecmp($statusIn, 'Normal') !== 0) {
			return tessera_cargo_setup_fail('Choose a currency status.');
		}
		$storedCountry = tessera_cargo_setup_compose_country($initial, $countryName, false);
		$dup = tessera_cargo_setup_one($db, 'SELECT id FROM currency WHERE country = ? LIMIT 1', 's', [$storedCountry]);
		if ($dup) {
			return tessera_cargo_setup_fail('That currency is already registered.', 409);
		}
		$ok = tessera_cargo_setup_exec(
			$db,
			'INSERT INTO currency (name, status, rate, initial, country) VALUES (?,?,?,?,?)',
			'ssiss',
			[$name, 'Normal', $rate, $initial, $storedCountry]
		);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not save currency.', 500);
		}
		$newId = (int) $db->insert_id;
		$saved = tessera_cargo_setup_one($db, 'SELECT id, name, status, rate, initial, country FROM currency WHERE id = ? LIMIT 1', 'i', [$newId]);
		return tessera_cargo_setup_ok(['item' => $saved ? tessera_cargo_setup_currency_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_currency_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_cargo_setup_fail('Choose a currency to delete.');
		}
		$row = tessera_cargo_setup_one($db, 'SELECT id, country, status FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$row) {
			return tessera_cargo_setup_fail('Currency not found.', 404);
		}
		if (strcasecmp(trim((string) ($row['status'] ?? '')), 'Main') === 0) {
			return tessera_cargo_setup_fail('The main currency stays on the list.', 409);
		}
		$used = tessera_cargo_setup_one($db, 'SELECT id FROM routes WHERE currency = ? LIMIT 1', 's', [(string) $row['country']]);
		if ($used) {
			return tessera_cargo_setup_fail('This currency is used by a route.', 409);
		}
		$ok = tessera_cargo_setup_exec($db, 'DELETE FROM currency WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not delete currency.', 500);
		}
		return tessera_cargo_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_cargo_setup_route_item(array $row, bool $locked): array
	{
		$display = tessera_cargo_setup_decode((string) ($row['route_name'] ?? ''));
		$full = (string) ($row['route_full_name'] ?? '');
		if ($display === '' || $display === (string) ($row['route_name'] ?? '')) {
			$maybe = tessera_cargo_setup_decode((string) ($row['route_name'] ?? ''));
			$display = $maybe !== '' ? $maybe : $full;
		}
		return [
			'id' => (int) ($row['id'] ?? 0),
			'route_id' => (int) ($row['route_id'] ?? 0),
			'start' => (string) ($row['start_route'] ?? ''),
			'end' => (string) ($row['end_route'] ?? ''),
			'fullname' => $full,
			'display_name' => $display,
			'initial' => (string) ($row['initial'] ?? ''),
			'currency' => (string) ($row['currency'] ?? ''),
			'currency_symbol' => (string) ($row['currency_symbol'] ?? ''),
			'contact_1' => (string) ($row['contact_1'] ?? ''),
			'contact_2' => (string) ($row['contact_2'] ?? ''),
			'address_1' => (string) ($row['address_1'] ?? ''),
			'address_2' => (string) ($row['address_2'] ?? ''),
			'terms_1' => tessera_cargo_setup_decode((string) ($row['term_1_new1'] ?? '')),
			'terms_2' => tessera_cargo_setup_decode((string) ($row['term_2_new'] ?? '')),
			'to_be_paid_currency' => (string) ($row['to_be_paid_currency'] ?? ''),
			'identity_locked' => $locked,
		];
	}

	function tessera_cargo_setup_route_locked(mysqli $db, string $fullname): bool
	{
		$tenant = tessera_cargo_setup_tenant();
		$manifest = tessera_cargo_setup_one(
			$db,
			'SELECT id FROM vehicle_name WHERE Route = ? AND tenant_id = ? LIMIT 1',
			'si',
			[$fullname, $tenant]
		);
		if ($manifest) {
			return true;
		}
		$parcel = tessera_cargo_setup_one(
			$db,
			'SELECT id FROM parcel_info WHERE account = ? AND tenant_id = ? LIMIT 1',
			'si',
			[$fullname, $tenant]
		);
		return $parcel !== null;
	}

	/** @param array<string,mixed> $query */
	function tessera_cargo_setup_route_bootstrap(mysqli $db, array $query = []): array
	{
		$locations = tessera_cargo_setup_all($db, 'SELECT id, name FROM locations_names ORDER BY name ASC LIMIT 500');
		$currencies = tessera_cargo_setup_all($db, 'SELECT id, name, initial, country, status FROM currency ORDER BY name ASC LIMIT 100');
		if ($locations === null || $currencies === null) {
			return tessera_cargo_setup_fail('Could not load route choices.', 500);
		}
		$locationItems = [];
		foreach ($locations as $row) {
			$locationItems[] = [
				'id' => (int) ($row['id'] ?? 0),
				'name' => (string) ($row['name'] ?? ''),
			];
		}
		$currencyItems = [];
		foreach ($currencies as $row) {
			$currencyItems[] = tessera_cargo_setup_currency_item($row);
		}
		return tessera_cargo_setup_ok([
			'locations' => $locationItems,
			'currencies' => $currencyItems,
		]);
	}

	/** @param array<string,mixed> $query */
	function tessera_cargo_setup_routes(mysqli $db, array $query): array
	{
		$tenant = tessera_cargo_setup_tenant();
		$q = trim((string) ($query['q'] ?? ''));
		$sql = 'SELECT id, start_route, end_route, route_id, route_name, currency, currency_symbol, route_full_name, initial,
			contact_1, contact_2, address_1, address_2, term_1_new1, term_2_new, to_be_paid_currency
			FROM routes WHERE tenant_id = ?';
		$types = 'i';
		$params = [$tenant];
		if ($q !== '') {
			$like = tessera_cargo_setup_like($q);
			$sql .= ' AND (start_route LIKE ? OR end_route LIKE ? OR route_full_name LIKE ? OR initial LIKE ? OR currency LIKE ?)';
			$types .= 'sssss';
			array_push($params, $like, $like, $like, $like, $like);
		}
		$sql .= ' ORDER BY id DESC LIMIT 300';
		$rows = tessera_cargo_setup_all($db, $sql, $types, $params);
		if ($rows === null) {
			return tessera_cargo_setup_fail('Could not load routes.', 500);
		}
		$items = [];
		foreach ($rows as $row) {
			$full = (string) ($row['route_full_name'] ?? '');
			$items[] = tessera_cargo_setup_route_item($row, tessera_cargo_setup_route_locked($db, $full));
		}
		return tessera_cargo_setup_ok(['items' => $items, 'total' => count($items)]);
	}

	function tessera_cargo_setup_next_location_id(mysqli $db): int
	{
		$row = tessera_cargo_setup_one($db, 'SELECT MAX(id) AS m FROM locations_names');
		if (!$row || $row['m'] === null) {
			return 1;
		}
		return ((int) $row['m']) + 1;
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_location_save(mysqli $db, array $body): array
	{
		$name = tessera_cargo_setup_clip((string) ($body['name'] ?? ''), 100);
		if ($name === '') {
			return tessera_cargo_setup_fail('Enter a location name.');
		}
		$dup = tessera_cargo_setup_one($db, 'SELECT id FROM locations_names WHERE name = ? LIMIT 1', 's', [$name]);
		if ($dup) {
			return tessera_cargo_setup_fail('That location is already on the list.', 409);
		}
		$nextId = tessera_cargo_setup_next_location_id($db);
		$ok = tessera_cargo_setup_exec($db, 'INSERT INTO locations_names (id, name) VALUES (?, ?)', 'is', [$nextId, $name]);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not save the location.', 500);
		}
		return tessera_cargo_setup_ok(['item' => ['id' => $nextId, 'name' => $name]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_location_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_cargo_setup_fail('Choose a location to delete.');
		}
		$row = tessera_cargo_setup_one($db, 'SELECT id, name FROM locations_names WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$row) {
			return tessera_cargo_setup_fail('Location not found.', 404);
		}
		$name = (string) $row['name'];
		$used = tessera_cargo_setup_one(
			$db,
			'SELECT id FROM routes WHERE start_route = ? OR end_route = ? LIMIT 1',
			'ss',
			[$name, $name]
		);
		if ($used) {
			return tessera_cargo_setup_fail('This location is used by a route.', 409);
		}
		$ok = tessera_cargo_setup_exec($db, 'DELETE FROM locations_names WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not delete the location.', 500);
		}
		return tessera_cargo_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @return array<string,mixed>|null */
	function tessera_cargo_setup_route_row(mysqli $db, int $id): ?array
	{
		if ($id < 1) {
			return null;
		}
		$tenant = tessera_cargo_setup_tenant();
		return tessera_cargo_setup_one(
			$db,
			'SELECT id, start_route, end_route, route_id, route_name, currency, currency_symbol, route_full_name, initial,
				contact_1, contact_2, address_1, address_2, term_1_new1, term_2_new, to_be_paid_currency, fare_price, detail
			 FROM routes WHERE id = ? AND tenant_id = ? LIMIT 1',
			'ii',
			[$id, $tenant]
		);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_route_save(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		$start = tessera_cargo_setup_clip((string) ($body['start'] ?? ''), 80);
		$end = tessera_cargo_setup_clip((string) ($body['end'] ?? ''), 80);
		$initial = tessera_cargo_setup_clip((string) ($body['initial'] ?? ''), 20);
		$display = tessera_cargo_setup_clip((string) ($body['display_name'] ?? ''), 60);
		$currencyStored = trim((string) ($body['currency'] ?? ''));
		$contact1 = tessera_cargo_setup_clip((string) ($body['contact_1'] ?? ''), 100);
		$contact2 = tessera_cargo_setup_clip((string) ($body['contact_2'] ?? ''), 100);
		$address1 = tessera_cargo_setup_clip((string) ($body['address_1'] ?? ''), 100);
		$address2 = tessera_cargo_setup_clip((string) ($body['address_2'] ?? ''), 100);
		$terms1 = base64_encode((string) ($body['terms_1'] ?? ''));
		$terms2 = base64_encode((string) ($body['terms_2'] ?? ''));
		$toBe = tessera_cargo_setup_clip((string) ($body['to_be_paid_currency'] ?? ''), 10);
		if ($start === '' || $end === '' || $start === $end || $initial === '' || $currencyStored === '') {
			return tessera_cargo_setup_fail('Choose a start, a different end, a receipt initial, and a currency.');
		}
		if (!tessera_cargo_setup_one($db, 'SELECT id FROM locations_names WHERE name = ? LIMIT 1', 's', [$start])
			|| !tessera_cargo_setup_one($db, 'SELECT id FROM locations_names WHERE name = ? LIMIT 1', 's', [$end])) {
			return tessera_cargo_setup_fail('Choose start and end from the location list.');
		}
		$currency = tessera_cargo_setup_one($db, 'SELECT rate, initial, country FROM currency WHERE country = ? LIMIT 1', 's', [$currencyStored]);
		if (!$currency) {
			return tessera_cargo_setup_fail('Choose a currency from the list.');
		}
		$symbol = (string) ($currency['initial'] ?? '');
		if ($display === '') {
			$display = tessera_cargo_setup_clip($start . ' to ' . $end, 60);
		}
		$routeName = base64_encode($display);
		$full = tessera_cargo_setup_clip($start . ' to ' . $end, 200);
		$tenant = tessera_cargo_setup_tenant();

		if ($id > 0) {
			$existing = tessera_cargo_setup_route_row($db, $id);
			if (!$existing) {
				return tessera_cargo_setup_fail('Route not found.', 404);
			}
			$locked = tessera_cargo_setup_route_locked($db, (string) ($existing['route_full_name'] ?? ''));
			if ($locked) {
				$sameIdentity = $start === (string) ($existing['start_route'] ?? '')
					&& $end === (string) ($existing['end_route'] ?? '')
					&& strcasecmp($initial, (string) ($existing['initial'] ?? '')) === 0;
				if (!$sameIdentity) {
					return tessera_cargo_setup_fail('This route is already used on manifests or parcels. You can change contacts, addresses, terms, and currency.');
				}
				$start = (string) ($existing['start_route'] ?? '');
				$end = (string) ($existing['end_route'] ?? '');
				$initial = (string) ($existing['initial'] ?? '');
				$full = (string) ($existing['route_full_name'] ?? '');
			} else {
				$dupInitial = tessera_cargo_setup_one(
					$db,
					'SELECT id FROM routes WHERE initial = ? AND tenant_id = ? AND id <> ? LIMIT 1',
					'sii',
					[$initial, $tenant, $id]
				);
				if ($dupInitial) {
					return tessera_cargo_setup_fail('That receipt initial is already used.', 409);
				}
				$dupPair = tessera_cargo_setup_one(
					$db,
					'SELECT id FROM routes WHERE start_route = ? AND end_route = ? AND tenant_id = ? AND id <> ? LIMIT 1',
					'ssii',
					[$start, $end, $tenant, $id]
				);
				if ($dupPair) {
					return tessera_cargo_setup_fail('That route is already registered.', 409);
				}
			}
			$ok = tessera_cargo_setup_exec(
				$db,
				'UPDATE routes SET start_route = ?, end_route = ?, route_name = ?, currency = ?, currency_symbol = ?, route_full_name = ?, initial = ?,
					contact_1 = ?, contact_2 = ?, address_1 = ?, address_2 = ?, term_1_new1 = ?, term_2_new = ?, to_be_paid_currency = ?
				 WHERE id = ? AND tenant_id = ? LIMIT 1',
				'ssssssssssssssii',
				[$start, $end, $routeName, $currencyStored, $symbol, $full, $initial, $contact1, $contact2, $address1, $address2, $terms1, $terms2, $toBe, $id, $tenant]
			);
			if (!$ok) {
				return tessera_cargo_setup_fail('Could not save the route.', 500);
			}
			$routeCode = (int) ($existing['route_id'] ?? 0);
			tessera_cargo_setup_exec(
				$db,
				'UPDATE assigned_routes SET start_route = ?, end_route = ?, route_name = ?, route_full_name = ?, currency = ?, currency_symbol = ? WHERE route_id = ?',
				'ssssssi',
				[$start, $end, $routeName, $full, $currencyStored, $symbol, $routeCode]
			);
			$saved = tessera_cargo_setup_route_row($db, $id);
			return tessera_cargo_setup_ok([
				'item' => $saved ? tessera_cargo_setup_route_item($saved, tessera_cargo_setup_route_locked($db, (string) ($saved['route_full_name'] ?? ''))) : ['id' => $id],
			]);
		}

		$dupInitial = tessera_cargo_setup_one(
			$db,
			'SELECT id FROM routes WHERE initial = ? AND tenant_id = ? LIMIT 1',
			'si',
			[$initial, $tenant]
		);
		if ($dupInitial) {
			return tessera_cargo_setup_fail('That receipt initial is already used. Change it.', 409);
		}
		$dupPair = tessera_cargo_setup_one(
			$db,
			'SELECT id FROM routes WHERE start_route = ? AND end_route = ? AND tenant_id = ? LIMIT 1',
			'ssi',
			[$start, $end, $tenant]
		);
		if ($dupPair) {
			return tessera_cargo_setup_fail('That route is already registered.', 409);
		}
		$max = tessera_cargo_setup_one($db, 'SELECT COALESCE(MAX(route_id), -1) AS m FROM routes WHERE tenant_id = ?', 'i', [$tenant]);
		$routeCode = $max ? ((int) $max['m']) + 1 : 1;
		$ok = tessera_cargo_setup_exec(
			$db,
			'INSERT INTO routes (
				start_route, end_route, route_id, route_name, currency, currency_symbol, route_full_name, initial,
				contact_1, contact_2, address_1, address_2, term_1_new1, term_2_new, to_be_paid_currency, tenant_id
			) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
			'ssissssssssssssi',
			[$start, $end, $routeCode, $routeName, $currencyStored, $symbol, $full, $initial, $contact1, $contact2, $address1, $address2, $terms1, $terms2, $toBe, $tenant]
		);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not save the route.', 500);
		}
		$newId = (int) $db->insert_id;
		$saved = tessera_cargo_setup_route_row($db, $newId);
		return tessera_cargo_setup_ok([
			'item' => $saved ? tessera_cargo_setup_route_item($saved, false) : ['id' => $newId],
		]);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_route_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		$row = tessera_cargo_setup_route_row($db, $id);
		if (!$row) {
			return tessera_cargo_setup_fail('Route not found.', 404);
		}
		$full = (string) ($row['route_full_name'] ?? '');
		$code = (int) ($row['route_id'] ?? 0);
		if (tessera_cargo_setup_route_locked($db, $full)) {
			return tessera_cargo_setup_fail('This route is used on manifests or parcels.', 409);
		}
		$booked = tessera_cargo_setup_one($db, 'SELECT id FROM booking WHERE route_id = ? LIMIT 1', 'i', [$code]);
		if ($booked) {
			return tessera_cargo_setup_fail('This route still has bookings.', 409);
		}
		$tenant = tessera_cargo_setup_tenant();
		$ok = tessera_cargo_setup_exec($db, 'DELETE FROM routes WHERE id = ? AND tenant_id = ? LIMIT 1', 'ii', [$id, $tenant]);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not delete the route.', 500);
		}
		tessera_cargo_setup_exec($db, 'DELETE FROM assigned_routes WHERE route_id = ?', 'i', [$code]);
		return tessera_cargo_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_cargo_setup_assign_list(mysqli $db, array $query): array
	{
		$username = tessera_cargo_setup_clip((string) ($query['username'] ?? ''), 78);
		$users = tessera_cargo_setup_all(
			$db,
			"SELECT username, fullname, firstname, surname FROM users WHERE status = 'active' AND username <> '' ORDER BY username ASC LIMIT 500"
		);
		if ($users === null) {
			return tessera_cargo_setup_fail('Could not load staff.', 500);
		}
		if ($username !== '') {
			$known = tessera_cargo_setup_one(
				$db,
				"SELECT username FROM users WHERE username = ? AND status = 'active' LIMIT 1",
				's',
				[$username]
			);
			if (!$known) {
				return tessera_cargo_setup_fail('Choose an active staff member.', 404);
			}
		}
		$tenant = tessera_cargo_setup_tenant();
		$routes = tessera_cargo_setup_all(
			$db,
			'SELECT id, route_id, start_route, end_route, route_full_name, route_name, initial
			 FROM routes WHERE tenant_id = ? ORDER BY route_full_name ASC LIMIT 500',
			'i',
			[$tenant]
		);
		if ($routes === null) {
			return tessera_cargo_setup_fail('Could not load routes.', 500);
		}
		$assigned = [];
		if ($username !== '') {
			$links = tessera_cargo_setup_all($db, 'SELECT route_id FROM assigned_routes WHERE username = ?', 's', [$username]) ?? [];
			foreach ($links as $link) {
				$assigned[(int) ($link['route_id'] ?? 0)] = true;
			}
		}
		$userItems = [];
		foreach ($users as $user) {
			$label = trim((string) ($user['fullname'] ?? ''));
			if ($label === '') {
				$label = trim((string) ($user['firstname'] ?? '') . ' ' . (string) ($user['surname'] ?? ''));
			}
			$userItems[] = [
				'username' => (string) ($user['username'] ?? ''),
				'label' => $label !== '' ? $label : (string) ($user['username'] ?? ''),
			];
		}
		$items = [];
		foreach ($routes as $route) {
			$code = (int) ($route['route_id'] ?? 0);
			$full = (string) ($route['route_full_name'] ?? '');
			$display = tessera_cargo_setup_decode((string) ($route['route_name'] ?? ''));
			$items[] = [
				'id' => (int) ($route['id'] ?? 0),
				'route_id' => $code,
				'fullname' => $full,
				'display_name' => $display !== '' ? $display : $full,
				'initial' => (string) ($route['initial'] ?? ''),
				'assigned' => $username !== '' && isset($assigned[$code]),
			];
		}
		return tessera_cargo_setup_ok([
			'username' => $username,
			'users' => $userItems,
			'routes' => $items,
		]);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_assign_save(mysqli $db, array $body): array
	{
		$username = tessera_cargo_setup_clip((string) ($body['username'] ?? ''), 78);
		$route = tessera_cargo_setup_route_row($db, (int) ($body['route_pk'] ?? $body['id'] ?? 0));
		if ($username === '' || !$route || !array_key_exists('assigned', $body)) {
			return tessera_cargo_setup_fail('Choose a staff member and a route.');
		}
		$known = tessera_cargo_setup_one(
			$db,
			"SELECT username FROM users WHERE username = ? AND status = 'active' LIMIT 1",
			's',
			[$username]
		);
		if (!$known) {
			return tessera_cargo_setup_fail('Choose an active staff member.', 404);
		}
		$code = (int) ($route['route_id'] ?? 0);
		$on = tessera_cargo_setup_flag($body['assigned']);
		$existing = tessera_cargo_setup_one(
			$db,
			'SELECT id FROM assigned_routes WHERE username = ? AND route_id = ? LIMIT 1',
			'si',
			[$username, $code]
		);
		if ($on) {
			if (!$existing) {
				$fare = (int) ($route['fare_price'] ?? 0);
				$ok = tessera_cargo_setup_exec(
					$db,
					'INSERT INTO assigned_routes (
						route_id, start_route, end_route, username, selected, active, active_tab,
						fare_price, detail, route_name, route_full_name, currency, currency_symbol, status
					) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
					'issssssissssss',
					[
						$code,
						(string) ($route['start_route'] ?? ''),
						(string) ($route['end_route'] ?? ''),
						$username,
						'true',
						'active',
						'show active',
						$fare,
						(string) ($route['detail'] ?? ''),
						(string) ($route['route_name'] ?? ''),
						(string) ($route['route_full_name'] ?? ''),
						(string) ($route['currency'] ?? ''),
						(string) ($route['currency_symbol'] ?? ''),
						'sender',
					]
				);
				if (!$ok) {
					return tessera_cargo_setup_fail('Could not assign the route.', 500);
				}
			}
			return tessera_cargo_setup_ok(['assigned' => true, 'route_id' => $code, 'username' => $username]);
		}
		if ($existing) {
			$ok = tessera_cargo_setup_exec(
				$db,
				'DELETE FROM assigned_routes WHERE username = ? AND route_id = ?',
				'si',
				[$username, $code]
			);
			if (!$ok) {
				return tessera_cargo_setup_fail('Could not remove the route.', 500);
			}
		}
		return tessera_cargo_setup_ok(['assigned' => false, 'route_id' => $code, 'username' => $username]);
	}

	/** @param array<string,mixed> $row */
	function tessera_cargo_setup_vehicle_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'driver' => (string) ($row['driver'] ?? ''),
			'conductor' => (string) ($row['conductor'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_cargo_setup_vehicles(mysqli $db, array $query): array
	{
		$tenant = tessera_cargo_setup_tenant();
		$q = trim((string) ($query['q'] ?? ''));
		$sql = 'SELECT id, name, driver, conductor FROM vehicles WHERE tenant_id = ?';
		$types = 'i';
		$params = [$tenant];
		if ($q !== '') {
			$like = tessera_cargo_setup_like($q);
			$sql .= ' AND (name LIKE ? OR driver LIKE ? OR conductor LIKE ?)';
			$types .= 'sss';
			array_push($params, $like, $like, $like);
		}
		$sql .= ' ORDER BY id DESC LIMIT 300';
		$rows = tessera_cargo_setup_all($db, $sql, $types, $params);
		if ($rows === null) {
			return tessera_cargo_setup_fail('Could not load vehicles.', 500);
		}
		$items = [];
		foreach ($rows as $row) {
			$items[] = tessera_cargo_setup_vehicle_item($row);
		}
		return tessera_cargo_setup_ok(['items' => $items, 'total' => count($items)]);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_vehicle_save(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		$name = tessera_cargo_setup_clip((string) ($body['name'] ?? ''), 100);
		$driver = tessera_cargo_setup_clip((string) ($body['driver'] ?? ''), 100);
		$conductor = tessera_cargo_setup_clip((string) ($body['conductor'] ?? ''), 100);
		if ($name === '' || $driver === '' || $conductor === '') {
			return tessera_cargo_setup_fail('Enter the vehicle name, driver, and conductor.');
		}
		$tenant = tessera_cargo_setup_tenant();
		if ($id > 0) {
			$existing = tessera_cargo_setup_one($db, 'SELECT id FROM vehicles WHERE id = ? AND tenant_id = ? LIMIT 1', 'ii', [$id, $tenant]);
			if (!$existing) {
				return tessera_cargo_setup_fail('Vehicle not found.', 404);
			}
			$dup = tessera_cargo_setup_one(
				$db,
				'SELECT id FROM vehicles WHERE name = ? AND tenant_id = ? AND id <> ? LIMIT 1',
				'sii',
				[$name, $tenant, $id]
			);
			if ($dup) {
				return tessera_cargo_setup_fail('That vehicle is already registered.', 409);
			}
			$ok = tessera_cargo_setup_exec(
				$db,
				'UPDATE vehicles SET name = ?, driver = ?, conductor = ? WHERE id = ? AND tenant_id = ? LIMIT 1',
				'sssii',
				[$name, $driver, $conductor, $id, $tenant]
			);
			if (!$ok) {
				return tessera_cargo_setup_fail('Could not save the vehicle.', 500);
			}
			return tessera_cargo_setup_ok(['item' => ['id' => $id, 'name' => $name, 'driver' => $driver, 'conductor' => $conductor]]);
		}
		$dup = tessera_cargo_setup_one($db, 'SELECT id FROM vehicles WHERE name = ? AND tenant_id = ? LIMIT 1', 'si', [$name, $tenant]);
		if ($dup) {
			return tessera_cargo_setup_fail('That vehicle is already registered.', 409);
		}
		$ok = tessera_cargo_setup_exec(
			$db,
			'INSERT INTO vehicles (name, driver, conductor, tenant_id) VALUES (?,?,?,?)',
			'sssi',
			[$name, $driver, $conductor, $tenant]
		);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not save the vehicle.', 500);
		}
		$newId = (int) $db->insert_id;
		return tessera_cargo_setup_ok(['item' => ['id' => $newId, 'name' => $name, 'driver' => $driver, 'conductor' => $conductor]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_cargo_setup_vehicle_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_cargo_setup_fail('Choose a vehicle to delete.');
		}
		$tenant = tessera_cargo_setup_tenant();
		$row = tessera_cargo_setup_one($db, 'SELECT id, name FROM vehicles WHERE id = ? AND tenant_id = ? LIMIT 1', 'ii', [$id, $tenant]);
		if (!$row) {
			return tessera_cargo_setup_fail('Vehicle not found.', 404);
		}
		$used = tessera_cargo_setup_one(
			$db,
			'SELECT id FROM vehicle_name WHERE Name = ? AND tenant_id = ? LIMIT 1',
			'si',
			[(string) $row['name'], $tenant]
		);
		if ($used) {
			return tessera_cargo_setup_fail('This vehicle has cargo manifests. Keep it on the list.', 409);
		}
		$ok = tessera_cargo_setup_exec($db, 'DELETE FROM vehicles WHERE id = ? AND tenant_id = ? LIMIT 1', 'ii', [$id, $tenant]);
		if (!$ok) {
			return tessera_cargo_setup_fail('Could not delete the vehicle.', 500);
		}
		return tessera_cargo_setup_ok(['deleted' => true, 'id' => $id]);
	}
}
