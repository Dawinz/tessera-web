<?php
/**
 * Accounts setup catalogs: job cards, routes, currency, particulars, vehicle categories, assignment.
 * Particulars, garage items, and the currency family share tables but stay separate screens.
 */
declare(strict_types=1);

if (!function_exists('tessera_accounts_setup_clip')) {
	function tessera_accounts_setup_clip(string $value, int $max): string
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

	function tessera_accounts_setup_text(array $input, string $key, int $max): string
	{
		return tessera_accounts_setup_clip((string) ($input[$key] ?? ''), $max);
	}

	function tessera_accounts_setup_id(array $input): int
	{
		return max(0, (int) ($input['id'] ?? 0));
	}

	function tessera_accounts_setup_q(array $input): string
	{
		return tessera_accounts_setup_clip((string) ($input['q'] ?? ''), 80);
	}

	function tessera_accounts_setup_tenant(): int
	{
		if (!function_exists('tessera_current_tenant_id')) {
			require_once dirname(__DIR__) . '/template/includes/tessera_tenant.php';
		}
		return (int) tessera_current_tenant_id();
	}

	function tessera_accounts_setup_fail(string $message, int $status = 422): array
	{
		return ['ok' => false, 'error' => $message, 'status' => $status];
	}

	/** @param array<string,mixed> $data */
	function tessera_accounts_setup_ok(array $data): array
	{
		$data['ok'] = true;
		return $data;
	}

	function tessera_accounts_setup_finish(array $result): void
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
	function tessera_accounts_setup_read_body(): array
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

	function tessera_accounts_setup_flag($value): bool
	{
		if (is_bool($value)) {
			return $value;
		}
		if (is_int($value)) {
			return $value === 1;
		}
		return in_array(strtolower(trim((string) $value)), ['1', 'true', 'on', 'yes'], true);
	}

	function tessera_accounts_setup_int($value, int $max = 2000000000): int
	{
		$clean = preg_replace('/[^0-9.]+/', '', (string) $value);
		if ($clean === null || $clean === '' || $clean === '.') {
			return 0;
		}
		$n = (int) round((float) $clean);
		if ($n < 0) {
			return 0;
		}
		return $n > $max ? $max : $n;
	}

	function tessera_accounts_setup_rate_string($value): string
	{
		$clean = preg_replace('/[^0-9.]+/', '', (string) $value);
		if ($clean === null || $clean === '' || $clean === '.') {
			return '';
		}
		$n = (float) $clean;
		if ($n <= 0) {
			return '';
		}
		if (abs($n - round($n)) < 0.0000001) {
			return (string) (int) round($n);
		}
		$out = rtrim(rtrim(number_format($n, 6, '.', ''), '0'), '.');
		return $out === '' ? '' : $out;
	}

	function tessera_accounts_setup_float($value): float
	{
		$raw = tessera_accounts_setup_rate_string($value);
		return $raw === '' ? 0.0 : (float) $raw;
	}

	/** @return list<string> */
	function tessera_accounts_setup_csv(string $csv): array
	{
		$out = [];
		foreach (explode(',', $csv) as $part) {
			$part = trim($part);
			if ($part !== '' && !in_array($part, $out, true)) {
				$out[] = $part;
			}
		}
		return $out;
	}

	function tessera_accounts_setup_csv_add(string $csv, string $name): string
	{
		$parts = tessera_accounts_setup_csv($csv);
		if ($name !== '' && !in_array($name, $parts, true)) {
			$parts[] = $name;
		}
		return implode(',', $parts);
	}

	function tessera_accounts_setup_csv_remove(string $csv, string $name): string
	{
		$parts = [];
		foreach (tessera_accounts_setup_csv($csv) as $part) {
			if ($part !== $name) {
				$parts[] = $part;
			}
		}
		return implode(',', $parts);
	}

	function tessera_accounts_setup_hit(string $q, array $parts): bool
	{
		if ($q === '') {
			return true;
		}
		return str_contains(strtolower(implode(' ', $parts)), strtolower($q));
	}

	/** @param list<int|string|float> $params */
	function tessera_accounts_setup_rows(mysqli $db, string $sql, array $params = []): array
	{
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			return [];
		}
		$ok = $params === [] ? $stmt->execute() : $stmt->execute(array_values($params));
		if (!$ok) {
			$stmt->close();
			return [];
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

	/** @param list<int|string|float> $params */
	function tessera_accounts_setup_first(mysqli $db, string $sql, array $params = []): ?array
	{
		$rows = tessera_accounts_setup_rows($db, $sql, $params);
		return $rows[0] ?? null;
	}

	/**
	 * @param list<int|string|float> $params
	 * @return array{ok:bool,id?:int,error?:string,status?:int}
	 */
	function tessera_accounts_setup_write(mysqli $db, string $sql, array $params = []): array
	{
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			return tessera_accounts_setup_fail('Could not save this record.', 500);
		}
		$ok = $params === [] ? $stmt->execute() : $stmt->execute(array_values($params));
		if (!$ok) {
			$stmt->close();
			return tessera_accounts_setup_fail('Could not save this record.', 500);
		}
		$id = (int) $stmt->insert_id;
		$stmt->close();
		return ['ok' => true, 'id' => $id];
	}

	function tessera_accounts_setup_cargo(): ?mysqli
	{
		if (!function_exists('sr_mysqli')) {
			require_once dirname(__DIR__) . '/template/includes/sr_env.php';
		}
		$db = sr_mysqli('cargo');
		return $db instanceof mysqli ? $db : null;
	}

	function tessera_accounts_setup_one_of(string $value, array $allowed): string
	{
		foreach ($allowed as $item) {
			if (strcasecmp($value, (string) $item) === 0) {
				return (string) $item;
			}
		}
		return '';
	}

	function tessera_accounts_setup_currency_kind(string $status): string
	{
		return stripos($status, 'Primary') !== false ? 'Primary' : 'Secondary';
	}

	function tessera_accounts_setup_currency_badge(string $kind): string
	{
		if ($kind === 'Primary') {
			return '<span class="badge badge-primary">Primary</span>';
		}
		return '<span class="badge badge-secondary">Secondary</span>';
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_currency_item(array $row): array
	{
		$rate = tessera_accounts_setup_rate_string((string) ($row['value_no'] ?? ''));
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'symbol' => (string) ($row['symbol'] ?? ''),
			'rate' => $rate === '' ? 0 : (float) $rate,
			'detail' => (string) ($row['detail'] ?? ''),
			'status' => tessera_accounts_setup_currency_kind((string) ($row['status'] ?? '')),
		];
	}

	function tessera_accounts_setup_currency_row(mysqli $db, string $name): ?array
	{
		if ($name === '') {
			return null;
		}
		return tessera_accounts_setup_first($db, 'SELECT * FROM currency WHERE name = ? LIMIT 1', [$name]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_route_item(array $row): array
	{
		$status = (string) ($row['status'] ?? '');
		$kind = strcasecmp($status, 'Main') === 0 ? 'Main' : 'Sub route';
		return [
			'id' => (int) ($row['id'] ?? 0),
			'start' => (string) ($row['start_point'] ?? ''),
			'end' => (string) ($row['end_point'] ?? ''),
			'fullname' => (string) ($row['fullname'] ?? ''),
			'code' => (string) ($row['name'] ?? ''),
			'currency' => (string) ($row['currency'] ?? ''),
			'kind' => $kind,
			'main_route' => (string) ($row['main_route'] ?? ''),
			'detail' => (string) ($row['detail'] ?? ''),
			'availability' => (string) ($row['availability'] ?? ''),
			'rate' => (int) ($row['currency_value'] ?? 0),
		];
	}

	function tessera_accounts_setup_route_by_id(mysqli $db, int $id): ?array
	{
		$tenant = tessera_accounts_setup_tenant();
		return tessera_accounts_setup_first($db, 'SELECT * FROM route WHERE id = ? AND tenant_id = ? LIMIT 1', [$id, $tenant]);
	}

	function tessera_accounts_setup_route_by_name(mysqli $db, string $fullname): ?array
	{
		$tenant = tessera_accounts_setup_tenant();
		return tessera_accounts_setup_first($db, 'SELECT * FROM route WHERE fullname = ? AND tenant_id = ? LIMIT 1', [$fullname, $tenant]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_category_item(array $row): array
	{
		$front = (string) ($row['front_name'] ?? '');
		$name = (string) ($row['name'] ?? '');
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => $name,
			'account' => (string) ($row['category'] ?? ''),
			'group' => (string) ($row['cat_group'] ?? ''),
			'front_name' => $front,
			'particulars' => (string) ($row['particulars_detail'] ?? ''),
			'label' => $front !== '' ? $front : $name,
		];
	}

	function tessera_accounts_setup_category_by_id(mysqli $db, int $id): ?array
	{
		if ($id < 1) {
			return null;
		}
		return tessera_accounts_setup_first($db, 'SELECT * FROM particular_category WHERE id = ? LIMIT 1', [$id]);
	}

	function tessera_accounts_setup_front_name(string $name, string $group): string
	{
		return tessera_accounts_setup_clip($name . '(' . $group . ')', 50);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_bootstrap(mysqli $db, string $username, array $query): array
	{
		unset($username, $query);
		$tenant = tessera_accounts_setup_tenant();
		$routes = [];
		$main = [];
		$have = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM route WHERE tenant_id = ? ORDER BY fullname ASC', [$tenant]) as $row) {
			$item = tessera_accounts_setup_route_item($row);
			$routes[] = $item;
			$have[$item['fullname']] = true;
			if ($item['kind'] === 'Main') {
				$main[] = $item;
			}
		}
		$currencies = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM currency ORDER BY name ASC') as $row) {
			$currencies[] = tessera_accounts_setup_currency_item($row);
		}
		$categories = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM particular_category ORDER BY name ASC') as $row) {
			$categories[] = tessera_accounts_setup_category_item($row);
		}
		$particulars = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT id, name, category, account FROM particulars ORDER BY category ASC, name ASC') as $row) {
			$particulars[] = [
				'id' => (int) $row['id'],
				'name' => (string) $row['name'],
				'category' => (string) $row['category'],
				'account' => (string) $row['account'],
				'label' => trim((string) $row['category'] . ' · ' . (string) $row['name']),
			];
		}
		$vehicleCategories = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT id, name FROM vehicle_category ORDER BY name ASC') as $row) {
			$vehicleCategories[] = ['id' => (int) $row['id'], 'name' => (string) $row['name']];
		}
		$users = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT username, firstname, surname, role FROM users ORDER BY username ASC LIMIT 500') as $row) {
			$username = trim((string) ($row['username'] ?? ''));
			if ($username === '') {
				continue;
			}
			$label = trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['surname'] ?? ''));
			$users[] = [
				'username' => $username,
				'label' => $label !== '' ? $label : $username,
				'role' => (string) ($row['role'] ?? ''),
			];
		}
		$cargoRoutes = [];
		$cargo = tessera_accounts_setup_cargo();
		if ($cargo instanceof mysqli) {
			foreach (tessera_accounts_setup_rows($cargo, 'SELECT route_full_name, start_route, end_route FROM routes WHERE tenant_id = ? ORDER BY route_full_name ASC', [$tenant]) as $row) {
				$fullname = trim((string) ($row['route_full_name'] ?? ''));
				if ($fullname === '' || isset($have[$fullname])) {
					continue;
				}
				$cargoRoutes[] = [
					'fullname' => $fullname,
					'start' => (string) ($row['start_route'] ?? ''),
					'end' => (string) ($row['end_route'] ?? ''),
					'label' => $fullname,
				];
			}
		}
		$months = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT DISTINCT month FROM job_card WHERE tenant_id = ? AND month <> "" ORDER BY month ASC', [$tenant]) as $row) {
			$months[] = (string) $row['month'];
		}
		$cards = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT id, date, route_name FROM job_card WHERE tenant_id = ? ORDER BY date DESC, id DESC LIMIT 200', [$tenant]) as $row) {
			$cards[] = [
				'id' => (int) $row['id'],
				'label' => (string) $row['date'] . ' · ' . (string) $row['route_name'],
			];
		}

		return tessera_accounts_setup_ok([
			'routes' => $routes,
			'main_routes' => $main,
			'currencies' => $currencies,
			'categories' => $categories,
			'particulars' => $particulars,
			'vehicle_categories' => $vehicleCategories,
			'users' => $users,
			'cargo_routes' => $cargoRoutes,
			'months' => $months,
			'cards' => $cards,
			'affects' => [
				['id' => 'Paid', 'label' => 'Paid'],
				['id' => 'To be paid', 'label' => 'To be paid'],
			],
			'accounts' => [
				['id' => 'Expenses', 'label' => 'Expenses'],
				['id' => 'Income', 'label' => 'Income'],
			],
			'groups' => [
				['id' => 'Vehicle', 'label' => 'Vehicle'],
				['id' => 'Neutral', 'label' => 'Neutral'],
				['id' => 'Office', 'label' => 'Office'],
			],
		]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_currencies(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM currency ORDER BY name ASC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_currency_item($row);
			if (!tessera_accounts_setup_hit($q, [$item['name'], $item['symbol'], $item['detail'], $item['status']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_currency_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$name = tessera_accounts_setup_text($input, 'name', 60);
		$symbol = tessera_accounts_setup_text($input, 'symbol', 30);
		$detail = tessera_accounts_setup_text($input, 'detail', 2000);
		$rate = tessera_accounts_setup_rate_string($input['rate'] ?? '');
		if ($name === '' || $symbol === '' || $rate === '') {
			return tessera_accounts_setup_fail('Name, symbol, and rate are required.');
		}
		$dup = tessera_accounts_setup_first($db, 'SELECT id FROM currency WHERE name = ? AND id <> ? LIMIT 1', [$name, $id]);
		if ($dup) {
			return tessera_accounts_setup_fail('That currency already exists.', 409);
		}
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT * FROM currency WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Currency not found.', 404);
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE currency SET name = ?, value_no = ?, symbol = ?, detail = ? WHERE id = ?',
				[$name, $rate, $symbol, $detail, $id]
			);
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO currency (name, value_no, status, symbol, detail) VALUES (?,?,?,?,?)',
				[$name, $rate, tessera_accounts_setup_currency_badge('Secondary'), $symbol, $detail]
			);
			$id = (int) ($written['id'] ?? 0);
		}
		if (empty($written['ok'])) {
			return $written;
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM currency WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Could not save this record.', 500);
		}
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_currency_item($row)]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_currency_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM currency WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Currency not found.', 404);
		}
		if (tessera_accounts_setup_currency_kind((string) $row['status']) === 'Primary') {
			return tessera_accounts_setup_fail('The main currency stays in place.', 409);
		}
		$name = (string) $row['name'];
		$used = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM currency_value WHERE first_point = ? OR second_point = ? LIMIT 1',
			[$name, $name]
		);
		if (!$used) {
			$used = tessera_accounts_setup_first($db, 'SELECT id FROM route_currency WHERE Currency = ? LIMIT 1', [$name]);
		}
		if (!$used) {
			$used = tessera_accounts_setup_first($db, 'SELECT id FROM constants WHERE currency = ? LIMIT 1', [$name]);
		}
		if (!$used) {
			$used = tessera_accounts_setup_first($db, 'SELECT id FROM route WHERE currency = ? LIMIT 1', [$name]);
		}
		if ($used) {
			return tessera_accounts_setup_fail('This currency is still used.', 409);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM currency WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_exchange_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'first' => (string) ($row['first_point'] ?? ''),
			'second' => (string) ($row['second_point'] ?? ''),
			'value' => (float) ($row['value'] ?? 0),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_exchange_rates(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM currency_value ORDER BY first_point ASC, second_point ASC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_exchange_item($row);
			if (!tessera_accounts_setup_hit($q, [$item['first'], $item['second'], (string) $item['value']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_exchange_rate_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$first = tessera_accounts_setup_text($input, 'first', 100);
		$second = tessera_accounts_setup_text($input, 'second', 100);
		$value = tessera_accounts_setup_rate_string($input['value'] ?? '');
		if ($first === '' || $second === '' || $first === $second || $value === '') {
			return tessera_accounts_setup_fail('Choose two different currencies and a rate.');
		}
		if (!tessera_accounts_setup_currency_row($db, $first) || !tessera_accounts_setup_currency_row($db, $second)) {
			return tessera_accounts_setup_fail('Both currencies must already exist.');
		}
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM currency_value WHERE first_point = ? AND second_point = ? AND id <> ? LIMIT 1',
			[$first, $second, $id]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That exchange rate already exists.', 409);
		}
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT id FROM currency_value WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Exchange rate not found.', 404);
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE currency_value SET first_point = ?, second_point = ?, value = ? WHERE id = ?',
				[$first, $second, $value, $id]
			);
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO currency_value (first_point, second_point, value) VALUES (?,?,?)',
				[$first, $second, $value]
			);
			$id = (int) ($written['id'] ?? 0);
		}
		if (empty($written['ok'])) {
			return $written;
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM currency_value WHERE id = ? LIMIT 1', [$id]);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_exchange_item($row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_exchange_rate_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT id FROM currency_value WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Exchange rate not found.', 404);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM currency_value WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_route_currency_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'currency' => (string) ($row['Currency'] ?? $row['currency'] ?? ''),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'status' => (string) ($row['status'] ?? ''),
			'rate' => (int) ($row['value_currency'] ?? 0),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_route_currencies(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$route = tessera_accounts_setup_text($query, 'route', 200);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM route_currency ORDER BY route_name ASC, id ASC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_route_currency_item($row);
			if ($route !== '' && $item['route_name'] !== $route) {
				continue;
			}
			if (!tessera_accounts_setup_hit($q, [$item['currency'], $item['route_name'], $item['status']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_route_currency_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$routeName = tessera_accounts_setup_text($input, 'route_name', 200);
		$currency = tessera_accounts_setup_text($input, 'currency', 30);
		$status = tessera_accounts_setup_one_of(tessera_accounts_setup_text($input, 'status', 20), ['Main', 'Sub route']);
		if ($routeName === '' || $currency === '' || $status === '') {
			return tessera_accounts_setup_fail('Route, currency, and status are required.');
		}
		if (!tessera_accounts_setup_route_by_name($db, $routeName)) {
			return tessera_accounts_setup_fail('That route is not in Accounts yet.');
		}
		$currencyRow = tessera_accounts_setup_currency_row($db, $currency);
		if (!$currencyRow) {
			return tessera_accounts_setup_fail('That currency is not in Accounts yet.');
		}
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM route_currency WHERE route_name = ? AND Currency = ? AND status = ? AND id <> ? LIMIT 1',
			[$routeName, $currency, $status, $id]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That route currency already exists.', 409);
		}
		$rate = tessera_accounts_setup_int($currencyRow['value_no'] ?? 0);
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT id FROM route_currency WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Route currency not found.', 404);
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE route_currency SET Currency = ?, route_name = ?, value_currency = ?, status = ? WHERE id = ?',
				[$currency, $routeName, $rate, $status, $id]
			);
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO route_currency (Currency, route_name, value_currency, status) VALUES (?,?,?,?)',
				[$currency, $routeName, $rate, $status]
			);
			$id = (int) ($written['id'] ?? 0);
		}
		if (empty($written['ok'])) {
			return $written;
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM route_currency WHERE id = ? LIMIT 1', [$id]);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_route_currency_item($row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_route_currency_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT id FROM route_currency WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Route currency not found.', 404);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM route_currency WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_routes(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$tenant = tessera_accounts_setup_tenant();
		$q = tessera_accounts_setup_q($query);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM route WHERE tenant_id = ? ORDER BY fullname ASC', [$tenant]) as $row) {
			$item = tessera_accounts_setup_route_item($row);
			if (!tessera_accounts_setup_hit($q, [$item['fullname'], $item['currency'], $item['kind'], $item['detail'], $item['main_route']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_route_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$currencyName = tessera_accounts_setup_text($input, 'currency', 60);
		$detail = tessera_accounts_setup_text($input, 'detail', 2000);
		$currencyRow = tessera_accounts_setup_currency_row($db, $currencyName);
		if (!$currencyRow) {
			return tessera_accounts_setup_fail('Choose a currency that already exists.');
		}
		$rate = tessera_accounts_setup_int($currencyRow['value_no'] ?? 0);
		if ($id > 0) {
			$current = tessera_accounts_setup_route_by_id($db, $id);
			if (!$current) {
				return tessera_accounts_setup_fail('Route not found.', 404);
			}
			$availability = tessera_accounts_setup_one_of(
				tessera_accounts_setup_text($input, 'availability', 20),
				['active', 'inactive']
			);
			if ($availability === '') {
				$availability = (string) ($current['availability'] ?? 'active');
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE route SET currency = ?, currency_value = ?, detail = ?, availability = ? WHERE id = ? AND tenant_id = ?',
				[$currencyName, $rate, $detail, $availability, $id, tessera_accounts_setup_tenant()]
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$row = tessera_accounts_setup_route_by_id($db, $id);
			return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_route_item($row ?: [])]);
		}

		$fullname = tessera_accounts_setup_text($input, 'cargo_fullname', 100);
		$kind = tessera_accounts_setup_one_of(tessera_accounts_setup_text($input, 'kind', 20), ['Main', 'Sub route']);
		$mainRoute = tessera_accounts_setup_text($input, 'main_route', 200);
		if ($fullname === '' || $kind === '') {
			return tessera_accounts_setup_fail('Choose a cargo route and whether it is a main or sub route.');
		}
		if ($kind === 'Sub route') {
			$parent = tessera_accounts_setup_route_by_name($db, $mainRoute);
			if (!$parent || strcasecmp((string) $parent['status'], 'Main') !== 0) {
				return tessera_accounts_setup_fail('A sub route needs a main route.');
			}
			if ($mainRoute === $fullname) {
				return tessera_accounts_setup_fail('A route cannot be a sub route of itself.');
			}
		}
		if (tessera_accounts_setup_route_by_name($db, $fullname)) {
			return tessera_accounts_setup_fail('That route is already in Accounts.', 409);
		}
		$cargoDb = tessera_accounts_setup_cargo();
		if (!$cargoDb) {
			return tessera_accounts_setup_fail('Cargo routes are unavailable.', 503);
		}
		$cargo = tessera_accounts_setup_first(
			$cargoDb,
			'SELECT route_name, start_route, end_route, route_full_name FROM routes WHERE route_full_name = ? AND tenant_id = ? LIMIT 1',
			[$fullname, tessera_accounts_setup_tenant()]
		);
		if (!$cargo) {
			return tessera_accounts_setup_fail('Register this route in Cargo first.');
		}
		$start = tessera_accounts_setup_clip((string) ($cargo['start_route'] ?? ''), 100);
		$end = tessera_accounts_setup_clip((string) ($cargo['end_route'] ?? ''), 500);
		if ($start === '' || $end === '') {
			return tessera_accounts_setup_fail('That cargo route is missing its stops.');
		}
		$code = trim((string) ($cargo['route_name'] ?? ''));
		if ($code === '') {
			$code = base64_encode($fullname);
		}
		$badgeMain = '<span class="badge badge-primary">Main Route</span>';
		$badgeSub = '<span class="badge badge-secondary">Sub Route</span>';
		$button = '<i class="typcn typcn-media-stop  "></i> De active';
		$status = $kind === 'Main' ? 'Main' : 'Sub route';
		$badge = $kind === 'Main' ? $badgeMain : $badgeSub;
		$parentName = $kind === 'Main' ? '' : $mainRoute;
		$tenant = tessera_accounts_setup_tenant();
		$written = tessera_accounts_setup_write(
			$db,
			'INSERT INTO route (start_point, end_point, fullname, name, detail, status, badge, availability, button, button_view, currency, currency_value, main_route, tenant_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
			[$start, $end, $fullname, $code, $detail, $status, $badge, 'active', $button, 'danger', $currencyName, $rate, $parentName, $tenant]
		);
		if (empty($written['ok'])) {
			return $written;
		}
		$newId = (int) ($written['id'] ?? 0);
		$reverseId = 0;
		if ($kind === 'Main') {
			$reverseName = tessera_accounts_setup_clip($end . ' to ' . $start, 100);
			if (!tessera_accounts_setup_route_by_name($db, $reverseName)) {
				$reverse = tessera_accounts_setup_write(
					$db,
					'INSERT INTO route (start_point, end_point, fullname, name, detail, status, badge, availability, button, button_view, currency, currency_value, main_route, tenant_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
					[$end, $start, $reverseName, $code, $detail, 'Sub route', $badgeSub, 'active', $button, 'danger', $currencyName, $rate, $fullname, $tenant]
				);
				if (empty($reverse['ok'])) {
					tessera_accounts_setup_write($db, 'DELETE FROM route WHERE id = ? AND tenant_id = ?', [$newId, $tenant]);
					return $reverse;
				}
				$reverseId = (int) ($reverse['id'] ?? 0);
			}
		}
		$row = tessera_accounts_setup_route_by_id($db, $newId);
		return tessera_accounts_setup_ok([
			'item' => tessera_accounts_setup_route_item($row ?: []),
			'reverse_id' => $reverseId,
		]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_route_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_route_by_id($db, $id);
		if (!$row) {
			return tessera_accounts_setup_fail('Route not found.', 404);
		}
		$tenant = tessera_accounts_setup_tenant();
		$used = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM vehicle_account WHERE route_name = ? AND tenant_id = ? LIMIT 1',
			[(string) $row['fullname'], $tenant]
		);
		if ($used) {
			return tessera_accounts_setup_fail('This route already has ledger lines.', 409);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM route WHERE id = ? AND tenant_id = ?', [$id, $tenant]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_sub_route_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'main_route' => (string) ($row['main_route'] ?? ''),
			'fullname' => (string) ($row['fullname'] ?? ''),
			'start' => (string) ($row['start_point'] ?? ''),
			'end' => (string) ($row['end_point'] ?? ''),
			'currency' => (string) ($row['currency'] ?? ''),
			'detail' => (string) ($row['detail'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_sub_routes(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM sub_route ORDER BY main_route ASC, fullname ASC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_sub_route_item($row);
			if (!tessera_accounts_setup_hit($q, [$item['main_route'], $item['fullname'], $item['currency'], $item['detail']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_sub_route_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$currencyName = tessera_accounts_setup_text($input, 'currency', 60);
		$detail = tessera_accounts_setup_text($input, 'detail', 2000);
		$currencyRow = tessera_accounts_setup_currency_row($db, $currencyName);
		if (!$currencyRow) {
			return tessera_accounts_setup_fail('Choose a currency that already exists.');
		}
		$rate = tessera_accounts_setup_int($currencyRow['value_no'] ?? 0);
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT id FROM sub_route WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Sub route not found.', 404);
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE sub_route SET currency = ?, currency_value = ?, detail = ? WHERE id = ?',
				[$currencyName, $rate, $detail, $id]
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$row = tessera_accounts_setup_first($db, 'SELECT * FROM sub_route WHERE id = ? LIMIT 1', [$id]);
			return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_sub_route_item($row ?: [])]);
		}
		$main = tessera_accounts_setup_text($input, 'main_route', 300);
		$fullname = tessera_accounts_setup_text($input, 'fullname', 100);
		if ($main === '' || $fullname === '' || $main === $fullname) {
			return tessera_accounts_setup_fail('Choose a main route and a different route to attach.');
		}
		$parent = tessera_accounts_setup_route_by_name($db, $main);
		$child = tessera_accounts_setup_route_by_name($db, $fullname);
		if (!$parent || strcasecmp((string) $parent['status'], 'Main') !== 0 || !$child) {
			return tessera_accounts_setup_fail('Both routes must already exist, and the parent must be a main route.');
		}
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM sub_route WHERE fullname = ? AND main_route = ? LIMIT 1',
			[$fullname, $main]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That sub route is already attached.', 409);
		}
		$written = tessera_accounts_setup_write(
			$db,
			'INSERT INTO sub_route (main_route, start_point, end_point, fullname, name, currency, status, currency_value, detail) VALUES (?,?,?,?,?,?,?,?,?)',
			[
				$main,
				(string) $child['start_point'],
				(string) $child['end_point'],
				$fullname,
				(string) $child['name'],
				$currencyName,
				'',
				$rate,
				$detail,
			]
		);
		if (empty($written['ok'])) {
			return $written;
		}
		$newId = (int) ($written['id'] ?? 0);
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM sub_route WHERE id = ? LIMIT 1', [$newId]);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_sub_route_item($row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_sub_route_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT id FROM sub_route WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Sub route not found.', 404);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM sub_route WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_constant_item(mysqli $db, array $row): array
	{
		$categoryName = (string) ($row['name_category'] ?? '');
		$category = tessera_accounts_setup_first($db, 'SELECT id FROM particular_category WHERE name = ? LIMIT 1', [$categoryName]);
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'price' => (int) ($row['price_value'] ?? 0),
			'currency' => (string) ($row['currency'] ?? ''),
			'route_category' => (string) ($row['route_category'] ?? ''),
			'category_id' => (int) ($category['id'] ?? 0),
			'category' => $categoryName,
			'affect' => (string) ($row['affect'] ?? ''),
			'vehicle' => (string) ($row['vehicle'] ?? ''),
			'account' => (string) ($row['status'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_constants(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$route = tessera_accounts_setup_text($query, 'route', 200);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM constants ORDER BY route_name ASC, name ASC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_constant_item($db, $row);
			if ($route !== '' && $item['route_name'] !== $route) {
				continue;
			}
			if (!tessera_accounts_setup_hit($q, [$item['name'], $item['route_name'], $item['category'], $item['vehicle'], $item['currency']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_constant_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$name = tessera_accounts_setup_text($input, 'name', 100);
		$routeName = tessera_accounts_setup_text($input, 'route_name', 200);
		$currency = tessera_accounts_setup_text($input, 'currency', 20);
		$routeCategory = tessera_accounts_setup_one_of(tessera_accounts_setup_text($input, 'route_category', 20), ['Main', 'Sub']);
		$affect = tessera_accounts_setup_one_of(tessera_accounts_setup_text($input, 'affect', 20), ['Paid', 'To be paid']);
		$vehicle = tessera_accounts_setup_text($input, 'vehicle', 100);
		$price = tessera_accounts_setup_int($input['price'] ?? 0);
		$category = tessera_accounts_setup_category_by_id($db, (int) ($input['category_id'] ?? 0));
		if ($name === '' || $routeName === '' || $currency === '' || $routeCategory === '' || $affect === '' || $vehicle === '' || !$category) {
			return tessera_accounts_setup_fail('Name, route, category, currency, vehicle, and affect are required.');
		}
		if (!tessera_accounts_setup_route_by_name($db, $routeName) || !tessera_accounts_setup_currency_row($db, $currency)) {
			return tessera_accounts_setup_fail('Route and currency must already exist.');
		}
		$categoryName = tessera_accounts_setup_clip((string) $category['name'], 200);
		$account = tessera_accounts_setup_clip((string) $category['category'], 20);
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM constants WHERE route_name = ? AND name = ? AND route_category = ? AND name_category = ? AND vehicle = ? AND id <> ? LIMIT 1',
			[$routeName, $name, $routeCategory, $categoryName, $vehicle, $id]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That constant already exists.', 409);
		}
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT id FROM constants WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Constant not found.', 404);
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE constants SET name = ?, route_name = ?, price_value = ?, currency = ?, route_category = ?, name_category = ?, affect = ?, status = ?, vehicle = ? WHERE id = ?',
				[$name, $routeName, $price, $currency, $routeCategory, $categoryName, $affect, $account, $vehicle, $id]
			);
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO constants (name, route_name, price_value, currency, route_category, name_category, affect, status, vehicle) VALUES (?,?,?,?,?,?,?,?,?)',
				[$name, $routeName, $price, $currency, $routeCategory, $categoryName, $affect, $account, $vehicle]
			);
			$id = (int) ($written['id'] ?? 0);
		}
		if (empty($written['ok'])) {
			return $written;
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM constants WHERE id = ? LIMIT 1', [$id]);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_constant_item($db, $row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_constant_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT id FROM constants WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Constant not found.', 404);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM constants WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_particular_categories(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM particular_category ORDER BY name ASC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_category_item($row);
			if (!tessera_accounts_setup_hit($q, [$item['name'], $item['account'], $item['group'], $item['particulars'], $item['front_name']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_particular_category_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$name = tessera_accounts_setup_text($input, 'name', 100);
		$account = tessera_accounts_setup_one_of(tessera_accounts_setup_text($input, 'account', 30), ['Income', 'Expenses']);
		$group = tessera_accounts_setup_one_of(tessera_accounts_setup_text($input, 'group', 30), ['Vehicle', 'Neutral', 'Office']);
		if ($name === '' || $account === '' || $group === '') {
			return tessera_accounts_setup_fail('Name, account, and group are required.');
		}
		$front = tessera_accounts_setup_front_name($name, $group);
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM particular_category WHERE ((name = ? AND category = ?) OR front_name = ?) AND id <> ? LIMIT 1',
			[$name, $account, $front, $id]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That category already exists.', 409);
		}
		if ($id > 0) {
			$current = tessera_accounts_setup_category_by_id($db, $id);
			if (!$current) {
				return tessera_accounts_setup_fail('Category not found.', 404);
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE particular_category SET name = ?, category = ?, cat_group = ?, front_name = ? WHERE id = ?',
				[$name, $account, $group, $front, $id]
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$oldName = (string) $current['name'];
			$oldAccount = (string) $current['category'];
			tessera_accounts_setup_write(
				$db,
				'UPDATE particulars SET category = ?, account = ?, group_name = ?, cat_group = ? WHERE category = ? AND account = ?',
				[$name, $account, $name, $front, $oldName, $oldAccount]
			);
			if ($oldName !== $name) {
				tessera_accounts_setup_write($db, 'UPDATE constants SET name_category = ? WHERE name_category = ?', [$name, $oldName]);
			}
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO particular_category (name, particulars_detail, category, cat_group, front_name) VALUES (?,?,?,?,?)',
				[$name, '', $account, $group, $front]
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$id = (int) ($written['id'] ?? 0);
		}
		$row = tessera_accounts_setup_category_by_id($db, $id);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_category_item($row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_particular_category_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_category_by_id($db, $id);
		if (!$row) {
			return tessera_accounts_setup_fail('Category not found.', 404);
		}
		$used = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM particulars WHERE category = ? AND account = ? LIMIT 1',
			[(string) $row['name'], (string) $row['category']]
		);
		if ($used) {
			return tessera_accounts_setup_fail('Remove the particulars in this category first.', 409);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM particular_category WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_particular_item(mysqli $db, array $row): array
	{
		$category = tessera_accounts_setup_first(
			$db,
			'SELECT id, front_name FROM particular_category WHERE name = ? AND category = ? LIMIT 1',
			[(string) ($row['category'] ?? ''), (string) ($row['account'] ?? '')]
		);
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'category' => (string) ($row['category'] ?? ''),
			'category_id' => (int) ($category['id'] ?? 0),
			'account' => (string) ($row['account'] ?? ''),
			'affect' => (string) ($row['affect'] ?? ''),
			'sub_particulars' => (string) ($row['sub_particulars'] ?? ''),
			'front_name' => (string) ($category['front_name'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_particulars(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$categoryId = (int) ($query['category_id'] ?? 0);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM particulars ORDER BY category ASC, name ASC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_particular_item($db, $row);
			if ($categoryId > 0 && $item['category_id'] !== $categoryId) {
				continue;
			}
			if (!tessera_accounts_setup_hit($q, [$item['name'], $item['category'], $item['affect'], $item['sub_particulars']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_particular_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$name = tessera_accounts_setup_text($input, 'name', 100);
		$affect = tessera_accounts_setup_one_of(tessera_accounts_setup_text($input, 'affect', 20), ['Paid', 'To be paid']);
		$category = tessera_accounts_setup_category_by_id($db, (int) ($input['category_id'] ?? 0));
		if ($name === '' || $affect === '' || !$category) {
			return tessera_accounts_setup_fail('Category, name, and affect are required.');
		}
		$categoryName = (string) $category['name'];
		$account = (string) $category['category'];
		$front = tessera_accounts_setup_clip((string) $category['front_name'], 50);
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM particulars WHERE category = ? AND account = ? AND name = ? AND id <> ? LIMIT 1',
			[$categoryName, $account, $name, $id]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That particular already exists in this category.', 409);
		}
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT * FROM particulars WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Particular not found.', 404);
			}
			$oldName = (string) $current['name'];
			$oldCategory = (string) $current['category'];
			$oldAccount = (string) $current['account'];
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE particulars SET name = ?, category = ?, account = ?, cat_group = ?, group_name = ?, affect = ? WHERE id = ?',
				[$name, $categoryName, $account, $front, $categoryName, $affect, $id]
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$oldCat = tessera_accounts_setup_first(
				$db,
				'SELECT * FROM particular_category WHERE name = ? AND category = ? LIMIT 1',
				[$oldCategory, $oldAccount]
			);
			if ($oldCat && ((int) $oldCat['id'] !== (int) $category['id'] || $oldName !== $name)) {
				$detail = tessera_accounts_setup_csv_remove((string) $oldCat['particulars_detail'], $oldName);
				tessera_accounts_setup_write($db, 'UPDATE particular_category SET particulars_detail = ? WHERE id = ?', [$detail, (int) $oldCat['id']]);
			}
			$fresh = tessera_accounts_setup_category_by_id($db, (int) $category['id']);
			$detail = tessera_accounts_setup_csv_add((string) ($fresh['particulars_detail'] ?? ''), $name);
			tessera_accounts_setup_write($db, 'UPDATE particular_category SET particulars_detail = ? WHERE id = ?', [$detail, (int) $category['id']]);
			if ($oldName !== $name) {
				$clipped = tessera_accounts_setup_clip($name, 50);
				$oldClip = tessera_accounts_setup_clip($oldName, 50);
				tessera_accounts_setup_write($db, 'UPDATE particular_sub SET particular_name = ? WHERE particular_name = ?', [$clipped, $oldClip]);
				tessera_accounts_setup_write(
					$db,
					'UPDATE vehicle_account SET name = ?, category = ? WHERE name = ? AND category = ? AND tenant_id = ?',
					[$name, $categoryName, $oldName, $oldCategory, tessera_accounts_setup_tenant()]
				);
			}
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO particulars (name, category, account, cat_group, group_name, affect, sub_particulars) VALUES (?,?,?,?,?,?,?)',
				[$name, $categoryName, $account, $front, $categoryName, $affect, '']
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$id = (int) ($written['id'] ?? 0);
			$detail = tessera_accounts_setup_csv_add((string) $category['particulars_detail'], $name);
			tessera_accounts_setup_write($db, 'UPDATE particular_category SET particulars_detail = ? WHERE id = ?', [$detail, (int) $category['id']]);
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM particulars WHERE id = ? LIMIT 1', [$id]);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_particular_item($db, $row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_particular_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM particulars WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Particular not found.', 404);
		}
		$name = (string) $row['name'];
		$used = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM vehicle_account WHERE name = ? AND category = ? AND tenant_id = ? LIMIT 1',
			[$name, (string) $row['category'], tessera_accounts_setup_tenant()]
		);
		if ($used) {
			return tessera_accounts_setup_fail('This particular is already on the ledger.', 409);
		}
		$garage = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM particular_sub WHERE particular_name = ? LIMIT 1',
			[tessera_accounts_setup_clip($name, 50)]
		);
		if ($garage) {
			return tessera_accounts_setup_fail('Remove garage items for this particular first.', 409);
		}
		$category = tessera_accounts_setup_first(
			$db,
			'SELECT * FROM particular_category WHERE name = ? AND category = ? LIMIT 1',
			[(string) $row['category'], (string) $row['account']]
		);
		if ($category) {
			$detail = tessera_accounts_setup_csv_remove((string) $category['particulars_detail'], $name);
			tessera_accounts_setup_write($db, 'UPDATE particular_category SET particulars_detail = ? WHERE id = ?', [$detail, (int) $category['id']]);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM particulars WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_particular_lines(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$name = tessera_accounts_setup_text($query, 'particular', 100);
		$start = tessera_accounts_setup_text($query, 'start', 20);
		$end = tessera_accounts_setup_text($query, 'end', 20);
		$currency = tessera_accounts_setup_text($query, 'currency', 30);
		if ($name === '') {
			return tessera_accounts_setup_ok(['items' => [], 'particular' => '']);
		}
		$sql = 'SELECT id, bus, trip_date, name, category, price, currency, route_name, detail, affect FROM vehicle_account WHERE tenant_id = ? AND name = ?';
		$params = [tessera_accounts_setup_tenant(), $name];
		if ($start !== '') {
			$sql .= ' AND trip_date >= ?';
			$params[] = $start;
		}
		if ($end !== '') {
			$sql .= ' AND trip_date <= ?';
			$params[] = $end;
		}
		if ($currency !== '') {
			$sql .= ' AND currency = ?';
			$params[] = $currency;
		}
		$sql .= ' ORDER BY trip_date DESC, id DESC LIMIT 200';
		$items = [];
		foreach (tessera_accounts_setup_rows($db, $sql, $params) as $row) {
			$items[] = [
				'id' => (int) $row['id'],
				'bus' => (string) ($row['bus'] ?? ''),
				'trip_date' => (string) ($row['trip_date'] ?? ''),
				'name' => (string) ($row['name'] ?? ''),
				'category' => (string) ($row['category'] ?? ''),
				'price' => (float) ($row['price'] ?? 0),
				'currency' => (string) ($row['currency'] ?? ''),
				'route_name' => (string) ($row['route_name'] ?? ''),
				'detail' => (string) ($row['detail'] ?? ''),
				'affect' => (string) ($row['affect'] ?? ''),
			];
		}
		return tessera_accounts_setup_ok(['items' => $items, 'particular' => $name]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_garage_particulars(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM particular_sub ORDER BY particular_name ASC, name ASC LIMIT 500') as $row) {
			$parent = (string) ($row['particular_name'] ?? '');
			$match = tessera_accounts_setup_first($db, 'SELECT id FROM particulars WHERE name = ? LIMIT 1', [$parent]);
			$item = [
				'id' => (int) $row['id'],
				'particular_id' => (int) ($match['id'] ?? 0),
				'particular_name' => $parent,
				'name' => (string) ($row['name'] ?? ''),
			];
			if (!tessera_accounts_setup_hit($q, [$item['particular_name'], $item['name']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_garage_particular_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$name = tessera_accounts_setup_text($input, 'name', 50);
		$parent = tessera_accounts_setup_first($db, 'SELECT * FROM particulars WHERE id = ? LIMIT 1', [(int) ($input['particular_id'] ?? 0)]);
		if ($name === '' || !$parent) {
			return tessera_accounts_setup_fail('Choose a particular and name the garage item.');
		}
		$parentName = tessera_accounts_setup_clip((string) $parent['name'], 50);
		if ($parentName !== (string) $parent['name']) {
			return tessera_accounts_setup_fail('That particular name is too long to use as a garage parent.');
		}
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM particular_sub WHERE particular_name = ? AND name = ? AND id <> ? LIMIT 1',
			[$parentName, $name, $id]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That garage item already exists.', 409);
		}
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT * FROM particular_sub WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Garage item not found.', 404);
			}
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE particular_sub SET particular_name = ?, name = ? WHERE id = ?',
				[$parentName, $name, $id]
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$oldParent = (string) $current['particular_name'];
			$oldName = (string) $current['name'];
			if ($oldParent !== $parentName || $oldName !== $name) {
				$oldRows = tessera_accounts_setup_rows($db, 'SELECT id, sub_particulars FROM particulars WHERE name = ?', [$oldParent]);
				foreach ($oldRows as $oldRow) {
					$csv = tessera_accounts_setup_csv_remove((string) $oldRow['sub_particulars'], $oldName);
					tessera_accounts_setup_write($db, 'UPDATE particulars SET sub_particulars = ? WHERE id = ?', [$csv, (int) $oldRow['id']]);
				}
			}
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO particular_sub (particular_name, name) VALUES (?,?)',
				[$parentName, $name]
			);
			if (empty($written['ok'])) {
				return $written;
			}
			$id = (int) ($written['id'] ?? 0);
		}
		$rows = tessera_accounts_setup_rows($db, 'SELECT id, sub_particulars FROM particulars WHERE name = ?', [$parentName]);
		foreach ($rows as $row) {
			$csv = tessera_accounts_setup_csv_add((string) $row['sub_particulars'], $name);
			tessera_accounts_setup_write($db, 'UPDATE particulars SET sub_particulars = ? WHERE id = ?', [$csv, (int) $row['id']]);
		}
		return tessera_accounts_setup_ok([
			'item' => [
				'id' => $id,
				'particular_id' => (int) $parent['id'],
				'particular_name' => $parentName,
				'name' => $name,
			],
		]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_garage_particular_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM particular_sub WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Garage item not found.', 404);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM particular_sub WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		$parents = tessera_accounts_setup_rows($db, 'SELECT id, sub_particulars FROM particulars WHERE name = ?', [(string) $row['particular_name']]);
		foreach ($parents as $parent) {
			$csv = tessera_accounts_setup_csv_remove((string) $parent['sub_particulars'], (string) $row['name']);
			tessera_accounts_setup_write($db, 'UPDATE particulars SET sub_particulars = ? WHERE id = ?', [$csv, (int) $parent['id']]);
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_vehicle_category_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'detail' => (string) ($row['detail'] ?? ''),
			'vehicles' => (string) ($row['partiuclar'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_vehicle_categories(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM vehicle_category ORDER BY name ASC LIMIT 200') as $row) {
			$item = tessera_accounts_setup_vehicle_category_item($row);
			if (!tessera_accounts_setup_hit($q, [$item['name'], $item['detail'], $item['vehicles']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_vehicle_category_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$name = tessera_accounts_setup_text($input, 'name', 40);
		$detail = tessera_accounts_setup_text($input, 'detail', 4000);
		if ($name === '') {
			return tessera_accounts_setup_fail('Category name is required.');
		}
		$dup = tessera_accounts_setup_first($db, 'SELECT id FROM vehicle_category WHERE name = ? AND id <> ? LIMIT 1', [$name, $id]);
		if ($dup) {
			return tessera_accounts_setup_fail('That vehicle category already exists.', 409);
		}
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT id FROM vehicle_category WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Vehicle category not found.', 404);
			}
			$written = tessera_accounts_setup_write($db, 'UPDATE vehicle_category SET name = ?, detail = ? WHERE id = ?', [$name, $detail, $id]);
			if (!empty($written['ok'])) {
				tessera_accounts_setup_write($db, 'UPDATE vehicle_particular_details SET category_name = ? WHERE category_id = ?', [$name, $id]);
			}
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO vehicle_category (name, detail, partiuclar) VALUES (?,?,?)',
				[$name, $detail, '']
			);
			$id = (int) ($written['id'] ?? 0);
		}
		if (empty($written['ok'])) {
			return $written;
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM vehicle_category WHERE id = ? LIMIT 1', [$id]);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_vehicle_category_item($row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_vehicle_category_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT id FROM vehicle_category WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Vehicle category not found.', 404);
		}
		$used = tessera_accounts_setup_first($db, 'SELECT id FROM vehicle_particular_details WHERE category_id = ? LIMIT 1', [$id]);
		if ($used) {
			return tessera_accounts_setup_fail('Remove the vehicles in this category first.', 409);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM vehicle_category WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_vehicle_category_particulars(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$categoryId = (int) ($query['category_id'] ?? 0);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM vehicle_particular_details ORDER BY category_name ASC, name ASC LIMIT 500') as $row) {
			$item = [
				'id' => (int) $row['id'],
				'name' => (string) ($row['name'] ?? ''),
				'category_id' => (int) ($row['category_id'] ?? 0),
				'category_name' => (string) ($row['category_name'] ?? ''),
			];
			if ($categoryId > 0 && $item['category_id'] !== $categoryId) {
				continue;
			}
			if (!tessera_accounts_setup_hit($q, [$item['name'], $item['category_name']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	function tessera_accounts_setup_sync_vehicle_list(mysqli $db, int $categoryId): void
	{
		$names = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT name FROM vehicle_particular_details WHERE category_id = ? ORDER BY name ASC', [$categoryId]) as $row) {
			$name = trim((string) ($row['name'] ?? ''));
			if ($name !== '') {
				$names[] = $name;
			}
		}
		tessera_accounts_setup_write($db, 'UPDATE vehicle_category SET partiuclar = ? WHERE id = ?', [implode(',', $names), $categoryId]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_vehicle_category_particular_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$name = tessera_accounts_setup_text($input, 'name', 200);
		$category = tessera_accounts_setup_first($db, 'SELECT * FROM vehicle_category WHERE id = ? LIMIT 1', [(int) ($input['category_id'] ?? 0)]);
		if ($name === '' || !$category) {
			return tessera_accounts_setup_fail('Choose a vehicle category and a vehicle.');
		}
		$categoryId = (int) $category['id'];
		$categoryName = tessera_accounts_setup_clip((string) $category['name'], 100);
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM vehicle_particular_details WHERE category_id = ? AND name = ? AND id <> ? LIMIT 1',
			[$categoryId, $name, $id]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('That vehicle is already in this category.', 409);
		}
		$previousCategory = 0;
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT * FROM vehicle_particular_details WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Vehicle not found.', 404);
			}
			$previousCategory = (int) $current['category_id'];
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE vehicle_particular_details SET name = ?, category_id = ?, category_name = ? WHERE id = ?',
				[$name, $categoryId, $categoryName, $id]
			);
		} else {
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO vehicle_particular_details (name, category_id, category_name) VALUES (?,?,?)',
				[$name, $categoryId, $categoryName]
			);
			$id = (int) ($written['id'] ?? 0);
		}
		if (empty($written['ok'])) {
			return $written;
		}
		tessera_accounts_setup_sync_vehicle_list($db, $categoryId);
		if ($previousCategory > 0 && $previousCategory !== $categoryId) {
			tessera_accounts_setup_sync_vehicle_list($db, $previousCategory);
		}
		return tessera_accounts_setup_ok([
			'item' => [
				'id' => $id,
				'name' => $name,
				'category_id' => $categoryId,
				'category_name' => $categoryName,
			],
		]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_vehicle_category_particular_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM vehicle_particular_details WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Vehicle not found.', 404);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM vehicle_particular_details WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		tessera_accounts_setup_sync_vehicle_list($db, (int) $row['category_id']);
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	function tessera_accounts_setup_job_date(string $value): ?DateTimeImmutable
	{
		$tz = new DateTimeZone('Africa/Dar_es_Salaam');
		$dt = DateTimeImmutable::createFromFormat('!Y-m-d', $value, $tz);
		if (!$dt || $dt->format('Y-m-d') !== $value) {
			return null;
		}
		return $dt;
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_job_item(mysqli $db, array $row): array
	{
		$count = tessera_accounts_setup_first(
			$db,
			'SELECT COUNT(*) AS c FROM job_card_entries WHERE date = ? AND route_name = ?',
			[(string) ($row['date'] ?? ''), (string) ($row['route_name'] ?? '')]
		);
		return [
			'id' => (int) ($row['id'] ?? 0),
			'date' => (string) ($row['date'] ?? ''),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'month' => (string) ($row['month'] ?? ''),
			'year' => (int) ($row['year'] ?? 0),
			'username' => (string) ($row['username'] ?? ''),
			'amount' => (int) ($row['amount'] ?? 0),
			'vehicles' => (string) ($row['name'] ?? ''),
			'entries' => (int) ($count['c'] ?? 0),
		];
	}

	/** @param array<string,mixed> $row */
	function tessera_accounts_setup_entry_item(array $row): array
	{
		$qty = (int) ($row['qty'] ?? 0);
		$price = (int) ($row['price'] ?? 0);
		return [
			'id' => (int) ($row['id'] ?? 0),
			'date' => (string) ($row['date'] ?? ''),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'bus' => (string) ($row['bus'] ?? ''),
			'item' => (string) ($row['item'] ?? ''),
			'qty' => $qty,
			'price' => $price,
			'line_total' => $qty * $price,
			'detail' => (string) ($row['detail'] ?? ''),
			'month' => (string) ($row['month'] ?? ''),
			'year' => (int) ($row['year'] ?? 0),
			'username' => (string) ($row['username'] ?? ''),
		];
	}

	function tessera_accounts_setup_job_sync(mysqli $db, string $date, string $route): void
	{
		$rows = tessera_accounts_setup_rows(
			$db,
			'SELECT bus, qty, price FROM job_card_entries WHERE date = ? AND route_name = ?',
			[$date, $route]
		);
		$amount = 0;
		$buses = [];
		foreach ($rows as $row) {
			$amount += (int) $row['qty'] * (int) $row['price'];
			$bus = trim((string) $row['bus']);
			if ($bus !== '' && !in_array($bus, $buses, true)) {
				$buses[] = $bus;
			}
		}
		$tenant = tessera_accounts_setup_tenant();
		tessera_accounts_setup_write(
			$db,
			'UPDATE job_card SET amount = ?, name = ? WHERE date = ? AND route_name = ? AND tenant_id = ?',
			[$amount, tessera_accounts_setup_clip(implode(',', $buses), 100), $date, $route, $tenant]
		);
		tessera_accounts_setup_write($db, 'DELETE FROM job_card_vehicle WHERE date = ? AND route_name = ?', [$date, $route]);
		$dt = tessera_accounts_setup_job_date($date);
		$month = $dt ? $dt->format('F') : '';
		$year = $dt ? $dt->format('Y') : '';
		foreach ($buses as $bus) {
			tessera_accounts_setup_write(
				$db,
				'INSERT INTO job_card_vehicle (date, vehicle, month, year, route_name) VALUES (?,?,?,?,?)',
				[$date, tessera_accounts_setup_clip($bus, 100), $month, $year, tessera_accounts_setup_clip($route, 100)]
			);
		}
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_job_cards(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$tenant = tessera_accounts_setup_tenant();
		$q = tessera_accounts_setup_q($query);
		$route = tessera_accounts_setup_text($query, 'route', 200);
		$month = tessera_accounts_setup_text($query, 'month', 30);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM job_card WHERE tenant_id = ? ORDER BY date DESC, id DESC LIMIT 400', [$tenant]) as $row) {
			$item = tessera_accounts_setup_job_item($db, $row);
			if ($route !== '' && $item['route_name'] !== $route) {
				continue;
			}
			if ($month !== '' && $item['month'] !== $month) {
				continue;
			}
			if (!tessera_accounts_setup_hit($q, [$item['date'], $item['route_name'], $item['vehicles'], $item['username']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_job_card_save(mysqli $db, string $username, array $input): array
	{
		$dateRaw = tessera_accounts_setup_text($input, 'date', 20);
		$routeName = tessera_accounts_setup_text($input, 'route_name', 200);
		$dt = tessera_accounts_setup_job_date($dateRaw);
		if (!$dt || $routeName === '') {
			return tessera_accounts_setup_fail('Date and route are required.');
		}
		if (!tessera_accounts_setup_route_by_name($db, $routeName)) {
			return tessera_accounts_setup_fail('That route is not in Accounts yet.');
		}
		$tenant = tessera_accounts_setup_tenant();
		$date = $dt->format('Y-m-d');
		$dup = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM job_card WHERE date = ? AND route_name = ? AND tenant_id = ? LIMIT 1',
			[$date, $routeName, $tenant]
		);
		if ($dup) {
			return tessera_accounts_setup_fail('A job card already exists for that date and route.', 409);
		}
		$month = $dt->format('F');
		$year = $dt->format('Y');
		$first = $dt->format('Y-m-01');
		$bucket = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM job_card_detail WHERE first_date = ? AND year = ? LIMIT 1',
			[$first, $year]
		);
		if (!$bucket) {
			$made = tessera_accounts_setup_write(
				$db,
				'INSERT INTO job_card_detail (first_date, month, year) VALUES (?,?,?)',
				[$first, $month, $year]
			);
			if (empty($made['ok'])) {
				return $made;
			}
		}
		$written = tessera_accounts_setup_write(
			$db,
			'INSERT INTO job_card (name, date, amount, item, username, status, bootstrap, route_name, user_checked, checked_count, analysed, month, year, tenant_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
			['', $date, 0, '', tessera_accounts_setup_clip($username, 100), '', 'bg-success', $routeName, '', 0, '', $month, $year, $tenant]
		);
		if (empty($written['ok'])) {
			return $written;
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM job_card WHERE id = ? LIMIT 1', [(int) ($written['id'] ?? 0)]);
		return tessera_accounts_setup_ok(['item' => tessera_accounts_setup_job_item($db, $row ?: [])]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_job_card_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$tenant = tessera_accounts_setup_tenant();
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM job_card WHERE id = ? AND tenant_id = ? LIMIT 1', [$id, $tenant]);
		if (!$row) {
			return tessera_accounts_setup_fail('Job card not found.', 404);
		}
		$used = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM job_card_entries WHERE date = ? AND route_name = ? LIMIT 1',
			[(string) $row['date'], (string) $row['route_name']]
		);
		if ($used) {
			return tessera_accounts_setup_fail('Remove the job card lines first.', 409);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM job_card WHERE id = ? AND tenant_id = ?', [$id, $tenant]);
		if (empty($written['ok'])) {
			return $written;
		}
		tessera_accounts_setup_write(
			$db,
			'DELETE FROM job_card_vehicle WHERE date = ? AND route_name = ?',
			[(string) $row['date'], (string) $row['route_name']]
		);
		$left = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM job_card WHERE month = ? AND year = ? LIMIT 1',
			[(string) $row['month'], (string) $row['year']]
		);
		if (!$left) {
			$dt = tessera_accounts_setup_job_date((string) $row['date']);
			if ($dt) {
				tessera_accounts_setup_write(
					$db,
					'DELETE FROM job_card_detail WHERE first_date = ? AND year = ?',
					[$dt->format('Y-m-01'), $dt->format('Y')]
				);
			}
		}
		return tessera_accounts_setup_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_job_card(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$tenant = tessera_accounts_setup_tenant();
		$id = (int) ($query['id'] ?? 0);
		$cards = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT id, date, route_name FROM job_card WHERE tenant_id = ? ORDER BY date DESC, id DESC LIMIT 200', [$tenant]) as $row) {
			$cards[] = [
				'id' => (int) $row['id'],
				'label' => (string) $row['date'] . ' · ' . (string) $row['route_name'],
			];
		}
		if ($id < 1) {
			return tessera_accounts_setup_ok(['card' => null, 'entries' => [], 'cards' => $cards]);
		}
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM job_card WHERE id = ? AND tenant_id = ? LIMIT 1', [$id, $tenant]);
		if (!$row) {
			return tessera_accounts_setup_fail('Job card not found.', 404);
		}
		$entries = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM job_card_entries WHERE date = ? AND route_name = ? ORDER BY id ASC', [(string) $row['date'], (string) $row['route_name']]) as $entry) {
			$entries[] = tessera_accounts_setup_entry_item($entry);
		}
		return tessera_accounts_setup_ok([
			'card' => tessera_accounts_setup_job_item($db, $row),
			'entries' => $entries,
			'cards' => $cards,
		]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_job_card_entries(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$q = tessera_accounts_setup_q($query);
		$route = tessera_accounts_setup_text($query, 'route', 200);
		$items = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT * FROM job_card_entries ORDER BY date DESC, id DESC LIMIT 500') as $row) {
			$item = tessera_accounts_setup_entry_item($row);
			if ($route !== '' && $item['route_name'] !== $route) {
				continue;
			}
			if (!tessera_accounts_setup_hit($q, [$item['date'], $item['route_name'], $item['bus'], $item['item'], $item['detail']])) {
				continue;
			}
			$items[] = $item;
		}
		return tessera_accounts_setup_ok(['items' => $items]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_job_card_entry_save(mysqli $db, string $username, array $input): array
	{
		$id = tessera_accounts_setup_id($input);
		$bus = tessera_accounts_setup_text($input, 'bus', 20);
		$itemName = tessera_accounts_setup_text($input, 'item', 300);
		$detail = tessera_accounts_setup_text($input, 'detail', 4000);
		$qty = tessera_accounts_setup_int($input['qty'] ?? 0, 1000000);
		$price = tessera_accounts_setup_int($input['price'] ?? 0);
		if ($bus === '' || $itemName === '' || $qty < 1 || $price < 1) {
			return tessera_accounts_setup_fail('Vehicle, item, quantity, and price are required.');
		}
		$tenant = tessera_accounts_setup_tenant();
		if ($id > 0) {
			$current = tessera_accounts_setup_first($db, 'SELECT * FROM job_card_entries WHERE id = ? LIMIT 1', [$id]);
			if (!$current) {
				return tessera_accounts_setup_fail('Job card line not found.', 404);
			}
			$date = (string) $current['date'];
			$routeName = (string) $current['route_name'];
			$written = tessera_accounts_setup_write(
				$db,
				'UPDATE job_card_entries SET bus = ?, item = ?, qty = ?, price = ?, detail = ? WHERE id = ?',
				[$bus, $itemName, $qty, $price, $detail, $id]
			);
		} else {
			$cardId = (int) ($input['job_card_id'] ?? 0);
			if ($cardId > 0) {
				$card = tessera_accounts_setup_first($db, 'SELECT * FROM job_card WHERE id = ? AND tenant_id = ? LIMIT 1', [$cardId, $tenant]);
			} else {
				$dateRaw = tessera_accounts_setup_text($input, 'date', 20);
				$routeName = tessera_accounts_setup_text($input, 'route_name', 200);
				$dt = tessera_accounts_setup_job_date($dateRaw);
				$card = null;
				if ($dt && $routeName !== '') {
					$card = tessera_accounts_setup_first(
						$db,
						'SELECT * FROM job_card WHERE date = ? AND route_name = ? AND tenant_id = ? LIMIT 1',
						[$dt->format('Y-m-d'), $routeName, $tenant]
					);
				}
			}
			if (!$card) {
				return tessera_accounts_setup_fail('Create the job card for this date and route first.');
			}
			$date = (string) $card['date'];
			$routeName = (string) $card['route_name'];
			$dt = tessera_accounts_setup_job_date($date);
			$now = new DateTimeImmutable('now', new DateTimeZone('Africa/Dar_es_Salaam'));
			$written = tessera_accounts_setup_write(
				$db,
				'INSERT INTO job_card_entries (date, bus, item, qty, price, date_insert, time_insert, status, route_name, username, detail, month, year) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)',
				[
					$date,
					$bus,
					$itemName,
					$qty,
					$price,
					$now->format('Y-m-d'),
					$now->format('h:i A'),
					'',
					$routeName,
					tessera_accounts_setup_clip($username, 300),
					$detail,
					$dt ? $dt->format('F') : (string) $card['month'],
					$dt ? $dt->format('Y') : (string) $card['year'],
				]
			);
			$id = (int) ($written['id'] ?? 0);
		}
		if (empty($written['ok'])) {
			return $written;
		}
		tessera_accounts_setup_job_sync($db, $date, $routeName);
		$entry = tessera_accounts_setup_first($db, 'SELECT * FROM job_card_entries WHERE id = ? LIMIT 1', [$id]);
		$card = tessera_accounts_setup_first(
			$db,
			'SELECT * FROM job_card WHERE date = ? AND route_name = ? AND tenant_id = ? LIMIT 1',
			[$date, $routeName, $tenant]
		);
		return tessera_accounts_setup_ok([
			'item' => tessera_accounts_setup_entry_item($entry ?: []),
			'card' => tessera_accounts_setup_job_item($db, $card ?: []),
		]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_job_card_entry_delete(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$id = tessera_accounts_setup_id($input);
		$row = tessera_accounts_setup_first($db, 'SELECT * FROM job_card_entries WHERE id = ? LIMIT 1', [$id]);
		if (!$row) {
			return tessera_accounts_setup_fail('Job card line not found.', 404);
		}
		$written = tessera_accounts_setup_write($db, 'DELETE FROM job_card_entries WHERE id = ?', [$id]);
		if (empty($written['ok'])) {
			return $written;
		}
		$date = (string) $row['date'];
		$routeName = (string) $row['route_name'];
		tessera_accounts_setup_job_sync($db, $date, $routeName);
		$card = tessera_accounts_setup_first(
			$db,
			'SELECT * FROM job_card WHERE date = ? AND route_name = ? AND tenant_id = ? LIMIT 1',
			[$date, $routeName, tessera_accounts_setup_tenant()]
		);
		return tessera_accounts_setup_ok([
			'deleted' => true,
			'id' => $id,
			'card' => $card ? tessera_accounts_setup_job_item($db, $card) : null,
		]);
	}

	/** @param array<string,mixed> $query */
	function tessera_accounts_setup_assign_list(mysqli $db, string $username, array $query): array
	{
		unset($username);
		$selected = tessera_accounts_setup_text($query, 'username', 200);
		$tenant = tessera_accounts_setup_tenant();
		$users = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT username, firstname, surname FROM users ORDER BY username ASC LIMIT 500') as $row) {
			$name = trim((string) ($row['username'] ?? ''));
			if ($name === '') {
				continue;
			}
			$label = trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['surname'] ?? ''));
			$users[] = ['username' => $name, 'label' => $label !== '' ? $label : $name];
		}
		$assigned = [];
		if ($selected !== '') {
			foreach (tessera_accounts_setup_rows($db, 'SELECT route_name FROM assign_route WHERE username = ?', [$selected]) as $row) {
				$assigned[(string) $row['route_name']] = true;
			}
		}
		$routes = [];
		foreach (tessera_accounts_setup_rows($db, 'SELECT id, fullname, status, start_point, end_point FROM route WHERE tenant_id = ? ORDER BY fullname ASC', [$tenant]) as $row) {
			$fullname = (string) $row['fullname'];
			$routes[] = [
				'id' => (int) $row['id'],
				'fullname' => $fullname,
				'kind' => strcasecmp((string) $row['status'], 'Main') === 0 ? 'Main' : 'Sub route',
				'start' => (string) $row['start_point'],
				'end' => (string) $row['end_point'],
				'assigned' => isset($assigned[$fullname]),
			];
		}
		return tessera_accounts_setup_ok(['users' => $users, 'routes' => $routes, 'username' => $selected]);
	}

	/** @param array<string,mixed> $input */
	function tessera_accounts_setup_assign_save(mysqli $db, string $username, array $input): array
	{
		unset($username);
		$person = tessera_accounts_setup_text($input, 'username', 200);
		$routeId = (int) ($input['route_id'] ?? 0);
		$assigned = tessera_accounts_setup_flag($input['assigned'] ?? false);
		if ($person === '' || $routeId < 1) {
			return tessera_accounts_setup_fail('Choose a person and a route.');
		}
		$user = tessera_accounts_setup_first($db, 'SELECT username FROM users WHERE username = ? LIMIT 1', [$person]);
		$route = tessera_accounts_setup_route_by_id($db, $routeId);
		if (!$user || !$route) {
			return tessera_accounts_setup_fail('Person or route was not found.', 404);
		}
		$fullname = (string) $route['fullname'];
		$existing = tessera_accounts_setup_first(
			$db,
			'SELECT id FROM assign_route WHERE username = ? AND route_name = ? LIMIT 1',
			[$person, $fullname]
		);
		if ($assigned) {
			if (!$existing) {
				$main = trim((string) $route['main_route']);
				if ($main === '') {
					$main = $fullname;
				}
				$status = strcasecmp((string) $route['status'], 'Main') === 0 ? 'main route' : '';
				$written = tessera_accounts_setup_write(
					$db,
					'INSERT INTO assign_route (route, route_name, username, point, main_route, end_point, status) VALUES (?,?,?,?,?,?,?)',
					[
						tessera_accounts_setup_clip((string) $route['name'], 200),
						tessera_accounts_setup_clip($fullname, 200),
						$person,
						tessera_accounts_setup_clip((string) $route['start_point'], 100),
						tessera_accounts_setup_clip($main, 300),
						tessera_accounts_setup_clip((string) $route['end_point'], 100),
						$status,
					]
				);
				if (empty($written['ok'])) {
					return $written;
				}
			}
		} elseif ($existing) {
			$written = tessera_accounts_setup_write(
				$db,
				'DELETE FROM assign_route WHERE username = ? AND route_name = ?',
				[$person, $fullname]
			);
			if (empty($written['ok'])) {
				return $written;
			}
		}
		return tessera_accounts_setup_ok([
			'username' => $person,
			'route_id' => $routeId,
			'assigned' => $assigned,
		]);
	}
}
