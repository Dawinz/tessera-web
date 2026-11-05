<?php
/**
 * Tickets network + trip templates (locations, routes, points, route_day).
 * Preserves classic route_id quirks:
 * - routes.route_id is the integer code (MAX+1); routes.id is the primary key
 * - route_day.route_id stores routes.id
 * - route_points.route_id and sub_route.main_route_no store routes.route_id (the code)
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_tenant.php';

if (!function_exists('tessera_network_fail')) {
	/**
	 * @return array{ok:false,code:string,message:string,status:int}
	 */
	function tessera_network_fail(string $code, string $message, int $status = 400): array
	{
		return ['ok' => false, 'code' => $code, 'message' => $message, 'status' => $status];
	}

	/**
	 * @param array<string,mixed> $data
	 * @return array{ok:true,data:array<string,mixed>}
	 */
	function tessera_network_ok(array $data): array
	{
		return ['ok' => true, 'data' => $data];
	}

	function tessera_network_emit(array $result): void
	{
		if (!empty($result['ok'])) {
			tessera_console_ok($result['data'] ?? []);
		}
		tessera_console_error(
			(string) ($result['code'] ?? 'error'),
			(string) ($result['message'] ?? 'Request failed'),
			(int) ($result['status'] ?? 400)
		);
	}

	function tessera_network_guard(string $method): mysqli
	{
		tessera_console_require_method($method);
		$username = tessera_console_require_session();
		global $db;
		require_once dirname(__DIR__) . '/connect.php';
		if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
			tessera_console_error('db_unavailable', 'Database unavailable', 503);
		}
		tessera_console_require_capability($db, $username, 'tickets');
		if ((int) tessera_current_tenant_id() < 1) {
			tessera_console_error('forbidden', 'No tenant context', 403);
		}
		$GLOBALS['tessera_network_username'] = $username;
		return $db;
	}

	/** @return array<string,mixed> */
	function tessera_network_body(): array
	{
		$raw = file_get_contents('php://input');
		$payload = json_decode($raw ?: '[]', true);
		if (!is_array($payload)) {
			tessera_console_error('validation_error', 'Invalid JSON body', 422);
		}
		return $payload;
	}

	function tessera_tickets_can_manage_template(mysqli $db, string $username): bool
	{
		$legacyRole = '';
		$stmt = $db->prepare('SELECT role FROM users WHERE username = ? LIMIT 1');
		if ($stmt) {
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$res = $stmt->get_result();
			if ($res && ($row = $res->fetch_assoc())) {
				$legacyRole = (string) ($row['role'] ?? '');
			}
			$stmt->close();
		}
		if (in_array($legacyRole, ['Super Administrator', 'Administrator'], true)) {
			return true;
		}
		if (!function_exists('tessera_user_role_code')) {
			$rbac = __DIR__ . '/tessera_rbac.php';
			if (is_file($rbac)) {
				require_once $rbac;
			}
		}
		if (function_exists('tessera_user_role_code')) {
			return in_array(tessera_user_role_code($db, $username), ['owner', 'manager'], true);
		}
		return false;
	}

	/**
	 * @param list<mixed> $params
	 * @return list<array<string,mixed>>|null
	 */
	function tessera_network_rows(mysqli $db, string $sql, string $types = '', array $params = []): ?array
	{
		$marks = substr_count($sql, '?');
		if ($marks !== strlen($types) || $marks !== count($params)) {
			return null;
		}
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			return null;
		}
		if ($types !== '') {
			$stmt->bind_param($types, ...$params);
		}
		if (!$stmt->execute()) {
			$stmt->close();
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

	/** @param list<mixed> $params */
	function tessera_network_one(mysqli $db, string $sql, string $types = '', array $params = []): ?array
	{
		$rows = tessera_network_rows($db, $sql, $types, $params);
		if ($rows === null) {
			return null;
		}
		return $rows[0] ?? null;
	}

	/**
	 * @param list<mixed> $params
	 * @return array{ok:bool,affected:int}
	 */
	function tessera_network_exec(mysqli $db, string $sql, string $types, array $params): array
	{
		$marks = substr_count($sql, '?');
		if ($marks !== strlen($types) || $marks !== count($params)) {
			$GLOBALS['tessera_network_sql_error'] = 'bind count ' . $marks . '/' . strlen($types) . '/' . count($params);
			return ['ok' => false, 'affected' => 0];
		}
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			$GLOBALS['tessera_network_sql_error'] = $db->error;
			return ['ok' => false, 'affected' => 0];
		}
		if ($types !== '') {
			$stmt->bind_param($types, ...$params);
		}
		$ok = $stmt->execute();
		if (!$ok) {
			$GLOBALS['tessera_network_sql_error'] = $stmt->error;
		}
		$affected = $ok ? (int) $stmt->affected_rows : 0;
		$stmt->close();
		return ['ok' => (bool) $ok, 'affected' => $affected];
	}

	function tessera_network_decode_detail(?string $raw): string
	{
		$raw = (string) $raw;
		if ($raw === '') {
			return '';
		}
		$decoded = base64_decode($raw, true);
		return $decoded === false ? $raw : $decoded;
	}

	function tessera_network_encode_detail(string $detail, bool $caretQuotes): string
	{
		if (strlen($detail) > 4000) {
			$detail = substr($detail, 0, 4000);
		}
		if ($caretQuotes) {
			$detail = str_replace("'", '^', $detail);
		}
		return base64_encode($detail);
	}

	function tessera_network_digits($value): int
	{
		$digits = preg_replace('/\D+/', '', (string) $value);
		if ($digits === null || $digits === '') {
			return 0;
		}
		return (int) $digits;
	}

	function tessera_network_fare($value): ?int
	{
		$clean = preg_replace('/[^0-9.]+/', '', (string) $value);
		if ($clean === null || $clean === '' || $clean === '.') {
			return null;
		}
		$n = (int) round((float) $clean);
		if ($n < 0 || $n > 2000000000) {
			return null;
		}
		return $n;
	}

	/** @return array{day:string,position:int}|null */
	function tessera_network_weekday(string $day): ?array
	{
		$map = [
			'monday' => ['Monday', 1],
			'tuesday' => ['Tuesday', 2],
			'wednesday' => ['Wednesday', 3],
			'thursday' => ['Thursday', 4],
			'friday' => ['Friday', 5],
			'saturday' => ['Saturday', 6],
			'sunday' => ['Sunday', 7],
		];
		$key = strtolower(trim($day));
		if (!isset($map[$key])) {
			return null;
		}
		return ['day' => $map[$key][0], 'position' => $map[$key][1]];
	}

	function tessera_network_clock(string $raw, string $fallback): string
	{
		$raw = trim($raw);
		$ts = strtotime($raw !== '' ? $raw : $fallback);
		if ($ts === false) {
			$ts = strtotime($fallback);
		}
		return date('h:i A', $ts ?: time());
	}

	/**
	 * @param list<string> $columns
	 * @return array{sql:string,types:string,params:list<string>}
	 */
	function tessera_network_like_clause(string $q, array $columns): array
	{
		$q = trim($q);
		$safe = [];
		foreach ($columns as $col) {
			if (preg_match('/^[a-z_][a-z0-9_]*(\.[a-z_][a-z0-9_]*)?$/i', $col)) {
				$safe[] = $col;
			}
		}
		if ($q === '' || $safe === []) {
			return ['sql' => '', 'types' => '', 'params' => []];
		}
		$like = '%' . addcslashes($q, "%_\\") . '%';
		$parts = [];
		$params = [];
		foreach ($safe as $col) {
			$parts[] = $col . " LIKE ? ESCAPE '\\\\'";
			$params[] = $like;
		}
		return [
			'sql' => ' AND (' . implode(' OR ', $parts) . ')',
			'types' => str_repeat('s', count($params)),
			'params' => $params,
		];
	}

	/** @param array<string,mixed> $row */
	function tessera_network_route_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'route_id' => (int) ($row['route_id'] ?? 0),
			'start_route' => (string) ($row['start_route'] ?? ''),
			'end_route' => (string) ($row['end_route'] ?? ''),
			'fullname' => (string) ($row['fullname'] ?? ''),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'route_type' => (string) ($row['route_type'] ?? ''),
			'currency' => (string) ($row['currency'] ?? ''),
			'currency_symbol' => (string) ($row['currency_symbol'] ?? ''),
			'fare_price' => (int) ($row['fare_price'] ?? 0),
			'fare' => (int) ($row['fare_price'] ?? 0),
			'active_date' => (int) ($row['active_date'] ?? 0),
			'detail' => tessera_network_decode_detail((string) ($row['detail'] ?? '')),
			'contact_no1' => (string) ($row['contact_no1'] ?? ''),
			'contact_no2' => (string) ($row['contact_no2'] ?? ''),
			'badge_label' => (string) ($row['route_type'] ?? ''),
		];
	}

	/** @return array{country:string,initial:string,rate:float}|null */
	function tessera_network_currency(mysqli $db, string $country): ?array
	{
		$country = trim($country);
		if ($country === '') {
			return null;
		}
		$row = tessera_network_one($db, 'SELECT country, initial, rate FROM currency WHERE country = ? LIMIT 1', 's', [$country]);
		if (!$row) {
			return null;
		}
		return [
			'country' => (string) $row['country'],
			'initial' => (string) ($row['initial'] ?? ''),
			'rate' => (float) ($row['rate'] ?? 0),
		];
	}

	function tessera_network_next_route_code(mysqli $db): int
	{
		$row = tessera_network_one($db, 'SELECT MAX(route_id) AS m FROM routes');
		if (!$row || $row['m'] === null) {
			return 0;
		}
		return ((int) $row['m']) + 1;
	}

	function tessera_network_touch_single_assignment(mysqli $db): void
	{
		$row = tessera_network_one($db, 'SELECT COUNT(DISTINCT route_id) AS c FROM assigned_routes');
		if ($row && (int) ($row['c'] ?? 0) === 1) {
			$db->query("UPDATE assigned_routes SET active='active', active_tab='show active', selected='true'");
		}
	}

	function tessera_network_sync_traveling_time(mysqli $db, int $routePk): void
	{
		if ($routePk < 1) {
			return;
		}
		$key = (string) $routePk;
		$times = tessera_network_rows(
			$db,
			'SELECT route_id, route_type, main_id, point_id, arrival, deputure, reporting_time, status
			 FROM route_time WHERE route_id = ? OR main_id = ?',
			'ss',
			[$key, $key]
		);
		if ($times === null) {
			return;
		}
		foreach ($times as $rt) {
			$routeId = (string) ($rt['route_id'] ?? '');
			$mainId = (string) ($rt['main_id'] ?? '');
			$pointId = (string) ($rt['point_id'] ?? '');
			$arrival = (string) ($rt['arrival'] ?? '');
			$departure = (string) ($rt['deputure'] ?? '');
			$reporting = (string) ($rt['reporting_time'] ?? '');
			$status = (string) ($rt['status'] ?? '');
			$routeType = (string) ($rt['route_type'] ?? '');
			$days = tessera_network_rows($db, 'SELECT id FROM route_day WHERE route_id = ?', 's', [$routeId]);
			if ($days === null) {
				continue;
			}
			if ($days === []) {
				$days = tessera_network_rows($db, 'SELECT id FROM route_day WHERE route_id = ?', 's', [$mainId]) ?? [];
			}
			$point = tessera_network_one($db, 'SELECT name FROM route_points WHERE id = ? LIMIT 1', 's', [$pointId]);
			$name = (string) ($point['name'] ?? '');
			foreach ($days as $day) {
				$dayId = (int) ($day['id'] ?? 0);
				if ($dayId < 1) {
					continue;
				}
				$existing = tessera_network_one(
					$db,
					'SELECT id FROM traveling_time WHERE route_id = ? AND route_day_id = ? AND status = ? AND point_id = ? LIMIT 1',
					'siss',
					[$routeId, $dayId, $status, $pointId]
				);
				if ($existing) {
					tessera_network_exec(
						$db,
						'UPDATE traveling_time SET deputure = ?, arrival = ?, reporting_time = ?
						 WHERE route_id = ? AND route_day_id = ? AND status = ? AND point_id = ?',
						'ssssiss',
						[$departure, $arrival, $reporting, $routeId, $dayId, $status, $pointId]
					);
				} else {
					tessera_network_exec(
						$db,
						'INSERT INTO traveling_time (route_id, route_day_id, deputure, arrival, reporting_time, point_id, status, route_type, main_id, name)
						 VALUES (?,?,?,?,?,?,?,?,?,?)',
						'sissssssss',
						[$routeId, $dayId, $departure, $arrival, $reporting, $pointId, $status, $routeType, $mainId, $name]
					);
				}
			}
		}
	}

	/** @param array<string,mixed> $query */
	function tessera_network_bootstrap(mysqli $db, array $query = []): array
	{
		$tenant = tessera_sql_tenant_eq();
		$tenantR = tessera_sql_tenant_eq('r');
		$locations = tessera_network_rows($db, 'SELECT id, name FROM locations_names ORDER BY name ASC LIMIT 500') ?? [];
		$currencies = tessera_network_rows($db, 'SELECT country, initial, rate FROM currency ORDER BY country ASC LIMIT 100') ?? [];
		$main = tessera_network_rows(
			$db,
			"SELECT id, route_id, fullname, route_name FROM routes WHERE {$tenant} AND route_type = 'Main Route' ORDER BY fullname ASC LIMIT 500"
		) ?? [];
		$routes = tessera_network_rows(
			$db,
			"SELECT id, route_id, fullname, route_name, route_type FROM routes WHERE {$tenant} ORDER BY fullname ASC LIMIT 500"
		) ?? [];
		$vehicles = tessera_network_rows(
			$db,
			"SELECT name FROM vehicle WHERE {$tenant} AND name <> '' ORDER BY name ASC LIMIT 500"
		) ?? [];
		$subs = tessera_network_rows(
			$db,
			"SELECT s.id, s.name, s.sub_route_id, s.start_route, s.end_route
			 FROM sub_route s
			 WHERE EXISTS (SELECT 1 FROM routes r WHERE r.route_id = s.main_route_no AND {$tenantR})
			 ORDER BY s.name ASC LIMIT 500"
		) ?? [];
		$weekdays = [
			['name' => 'Monday', 'position' => 1],
			['name' => 'Tuesday', 'position' => 2],
			['name' => 'Wednesday', 'position' => 3],
			['name' => 'Thursday', 'position' => 4],
			['name' => 'Friday', 'position' => 5],
			['name' => 'Saturday', 'position' => 6],
			['name' => 'Sunday', 'position' => 7],
		];
		$routeItems = [];
		foreach ($routes as $row) {
			$routeItems[] = [
				'id' => (int) $row['id'],
				'route_id' => (int) $row['route_id'],
				'fullname' => (string) $row['fullname'],
				'route_name' => (string) $row['route_name'],
				'route_type' => (string) $row['route_type'],
				'label' => trim((string) $row['fullname'] . ' · ' . (string) $row['route_type']),
			];
		}
		$subItems = [];
		foreach ($subs as $row) {
			$subItems[] = [
				'id' => (int) $row['id'],
				'name' => (string) $row['name'],
				'sub_route_id' => (int) $row['sub_route_id'],
				'start_route' => (string) $row['start_route'],
				'end_route' => (string) $row['end_route'],
				'label' => trim((string) $row['name'] . ' · ' . (string) $row['start_route'] . ' to ' . (string) $row['end_route']),
			];
		}
		return tessera_network_ok([
			'locations' => array_map(static function (array $row): array {
				return ['id' => (int) $row['id'], 'name' => (string) $row['name']];
			}, $locations),
			'currencies' => array_map(static function (array $row): array {
				return [
					'country' => (string) $row['country'],
					'initial' => (string) ($row['initial'] ?? ''),
					'rate' => (float) ($row['rate'] ?? 0),
				];
			}, $currencies),
			'main_routes' => array_map(static function (array $row): array {
				return [
					'id' => (int) $row['id'],
					'route_id' => (int) $row['route_id'],
					'fullname' => (string) $row['fullname'],
					'route_name' => (string) $row['route_name'],
				];
			}, $main),
			'routes' => $routeItems,
			'vehicles' => array_map(static function (array $row): array {
				return ['name' => (string) $row['name']];
			}, $vehicles),
			'sub_routes' => $subItems,
			'weekdays' => $weekdays,
			'points' => [
				['id' => 'starting', 'label' => 'Starting'],
				['id' => 'end', 'label' => 'End'],
			],
		]);
	}

	/** @param array<string,mixed> $query */
	function tessera_network_locations_list(mysqli $db, array $query): array
	{
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['name']);
		$rows = tessera_network_rows(
			$db,
			'SELECT id, name FROM locations_names WHERE 1=1' . $like['sql'] . ' ORDER BY name ASC LIMIT 500',
			$like['types'],
			$like['params']
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load locations', 500);
		}
		$items = array_map(static function (array $row): array {
			return ['id' => (int) $row['id'], 'name' => (string) $row['name']];
		}, $rows);
		return tessera_network_ok(['items' => $items, 'total' => count($items)]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_location_create(mysqli $db, array $body): array
	{
		$name = trim((string) ($body['name'] ?? $body['l_name'] ?? ''));
		if ($name === '' || strlen($name) > 100) {
			return tessera_network_fail('validation_error', 'Enter a location name (up to 100 characters).', 422);
		}
		$existing = tessera_network_one($db, 'SELECT id FROM locations_names WHERE name = ? LIMIT 1', 's', [$name]);
		if ($existing === null && $db->errno) {
			return tessera_network_fail('server_error', 'Could not check locations', 500);
		}
		if ($existing) {
			return tessera_network_fail('conflict', 'That location already exists.', 409);
		}
		$exec = tessera_network_exec($db, 'INSERT INTO locations_names (name) VALUES (?)', 's', [$name]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save location', 500);
		}
		return tessera_network_ok(['id' => (int) $db->insert_id, 'name' => $name]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_location_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a location to delete.', 422);
		}
		$exec = tessera_network_exec($db, 'DELETE FROM locations_names WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete location', 500);
		}
		if ($exec['affected'] < 1) {
			return tessera_network_fail('not_found', 'Location not found.', 404);
		}
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_network_routes_list(mysqli $db, array $query): array
	{
		$tenant = tessera_sql_tenant_eq();
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['start_route', 'end_route', 'route_name', 'fullname']);
		$typeSql = '';
		$types = $like['types'];
		$params = $like['params'];
		$type = trim((string) ($query['route_type'] ?? ''));
		if ($type === 'Main Route' || $type === 'Sub Route') {
			$typeSql = ' AND route_type = ?';
			$types .= 's';
			$params[] = $type;
		}
		$rows = tessera_network_rows(
			$db,
			"SELECT id, route_id, start_route, end_route, fullname, route_name, route_type, currency, currency_symbol,
			        fare_price, active_date, detail, contact_no1, contact_no2
			 FROM routes WHERE {$tenant}" . $typeSql . $like['sql'] . ' ORDER BY id ASC LIMIT 500',
			$types,
			$params
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load routes', 500);
		}
		$items = array_map('tessera_network_route_item', $rows);
		return tessera_network_ok(['items' => $items, 'total' => count($items)]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_route_save(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		$start = trim((string) ($body['start_route'] ?? $body['starting_point'] ?? ''));
		$end = trim((string) ($body['end_route'] ?? $body['end_point'] ?? ''));
		$currencyName = trim((string) ($body['currency'] ?? ''));
		$routeName = trim((string) ($body['route_name'] ?? $body['initial'] ?? ''));
		$detailRaw = (string) ($body['detail'] ?? $body['details'] ?? '');
		$active = tessera_network_digits($body['active_date'] ?? $body['days'] ?? 0);
		$contact1 = trim((string) ($body['contact_no1'] ?? ''));
		$contact2 = trim((string) ($body['contact_no2'] ?? ''));
		$fare = tessera_network_fare($body['fare'] ?? $body['price'] ?? $body['fair'] ?? '');
		if ($start === '' || $end === '' || $routeName === '') {
			return tessera_network_fail('validation_error', 'Start, end, and route name are required.', 422);
		}
		if ($start === $end) {
			return tessera_network_fail('validation_error', 'Start and end must be different.', 422);
		}
		if (strlen($routeName) > 100 || strlen($start) > 80 || strlen($end) > 80) {
			return tessera_network_fail('validation_error', 'Start, end, or route name is too long.', 422);
		}
		if ($fare === null) {
			return tessera_network_fail('validation_error', 'Enter a fare.', 422);
		}
		$currency = tessera_network_currency($db, $currencyName);
		if ($currency === null) {
			return tessera_network_fail('validation_error', 'Choose a currency from the list.', 422);
		}
		$fullname = $start . ' to ' . $end;
		$tenant = tessera_sql_tenant_eq();
		$tenantId = (int) tessera_current_tenant_id();

		if ($id > 0) {
			$existing = tessera_network_one(
				$db,
				"SELECT id, route_id, currency, route_type FROM routes WHERE id = ? AND {$tenant} LIMIT 1",
				'i',
				[$id]
			);
			if (!$existing) {
				return tessera_network_fail('not_found', 'Route not found.', 404);
			}
			$detail = tessera_network_encode_detail($detailRaw, false);
			$code = (int) $existing['route_id'];
			$sameCurrency = (string) ($existing['currency'] ?? '') === $currency['country'];
			if ($sameCurrency) {
				$exec = tessera_network_exec(
					$db,
					"UPDATE routes SET start_route=?, end_route=?, fare_price=?, detail=?, currency=?, route_name=?, active_date=?, contact_no1=?, contact_no2=?, fullname=?
					 WHERE id=? AND {$tenant}",
					'ssisssisssi',
					[$start, $end, $fare, $detail, $currency['country'], $routeName, $active, $contact1, $contact2, $fullname, $id]
				);
				if (!$exec['ok']) {
					return tessera_network_fail('server_error', 'Could not update route', 500);
				}
				tessera_network_exec($db, 'UPDATE assigned_routes SET route_name=? WHERE route_id=?', 'si', [$routeName, $code]);
			} else {
				$intl = (int) round($currency['rate'] * $fare);
				$exec = tessera_network_exec(
					$db,
					"UPDATE routes SET start_route=?, end_route=?, fare_price=?, fare_price_international=?, detail=?, currency=?, currency_symbol=?, route_name=?, active_date=?, contact_no1=?, contact_no2=?, fullname=?
					 WHERE id=? AND {$tenant}",
					'ssiissssisssi',
					[$start, $end, $fare, $intl, $detail, $currency['country'], $currency['initial'], $routeName, $active, $contact1, $contact2, $fullname, $id]
				);
				if (!$exec['ok']) {
					return tessera_network_fail('server_error', 'Could not update route', 500);
				}
				tessera_network_exec(
					$db,
					'UPDATE assigned_routes SET fare_price=?, fare_price_international=?, currency=?, currency_symbol=?, route_name=? WHERE route_id=?',
					'iisssi',
					[$fare, $intl, $currency['country'], $currency['initial'], $routeName, $code]
				);
			}
			$saved = tessera_network_one($db, "SELECT * FROM routes WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$id]);
			return tessera_network_ok(['item' => $saved ? tessera_network_route_item($saved) : ['id' => $id]]);
		}

		$routeNameStored = str_replace("'", '^', $routeName);
		$dup = tessera_network_one(
			$db,
			"SELECT id FROM routes WHERE start_route=? AND end_route=? AND route_type='Main Route' AND {$tenant} LIMIT 1",
			'ss',
			[$start, $end]
		);
		if ($dup) {
			return tessera_network_fail('conflict', 'That route already exists.', 409);
		}
		$code = tessera_network_next_route_code($db);
		$detail = tessera_network_encode_detail($detailRaw, true);
		$badge = '<span class="badge bg-primary">Main Route</span>';
		$routeType = 'Main Route';
		$empty = '';
		$zero = 0;
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO routes (
				start_route, end_route, fare_price, fare_price_international, sub_route_id,
				route_id, detail, route_name, currency, currency_symbol,
				active_date, arrival, departure, contact_no1, contact_no2,
				fullname, transit, terms, route_type, badge,
				main_id, route_id_fullname, vip_price, id_type, tenant_id
			) VALUES (?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?)',
			'ssiisissssisssssssssssiii',
			[
				$start, $end, $fare, $zero, $empty,
				$code, $detail, $routeNameStored, $currency['country'], $currency['initial'],
				$active, $empty, $empty, $contact1, $contact2,
				$fullname, $empty, $empty, $routeType, $badge,
				'0', $empty, $zero, $zero, $tenantId,
			]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save route', 500);
		}
		$newId = (int) $db->insert_id;
		tessera_network_touch_single_assignment($db);
		$saved = tessera_network_one($db, "SELECT * FROM routes WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_network_route_item($saved) : ['id' => $newId, 'route_id' => $code, 'fullname' => $fullname]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_route_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a route to delete.', 422);
		}
		$tenant = tessera_sql_tenant_eq();
		$tenantB = tessera_sql_tenant_eq('b');
		$row = tessera_network_one($db, "SELECT id, route_id FROM routes WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$id]);
		if (!$row) {
			return tessera_network_fail('not_found', 'Route not found.', 404);
		}
		$code = (string) (int) $row['route_id'];
		$booking = tessera_network_one(
			$db,
			"SELECT b.id FROM booking b WHERE {$tenantB} AND b.route_id = ? LIMIT 1",
			's',
			[$code]
		);
		if ($booking) {
			return tessera_network_fail('conflict', 'This route has bookings and cannot be deleted.', 409);
		}
		$exec = tessera_network_exec($db, "DELETE FROM routes WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete route', 500);
		}
		tessera_network_exec($db, 'DELETE FROM assigned_routes WHERE route_id = ?', 'i', [(int) $row['route_id']]);
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_network_sub_route_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'start_route' => (string) ($row['start_route'] ?? ''),
			'end_route' => (string) ($row['end_route'] ?? ''),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'currency' => (string) ($row['currency'] ?? ''),
			'currency_symbol' => (string) ($row['currency_symbol'] ?? ''),
			'fare_price' => (int) ($row['fare_price'] ?? 0),
			'a_time' => (string) ($row['a_time'] ?? ''),
			'd_time' => (string) ($row['d_time'] ?? ''),
			'active_day' => (int) ($row['active_day'] ?? 0),
			'detail' => tessera_network_decode_detail((string) ($row['detail'] ?? '')),
			'main_route_no' => (int) ($row['main_route_no'] ?? 0),
			'sub_route_id' => (int) ($row['sub_route_id'] ?? 0),
			'status' => (string) ($row['status'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_network_sub_routes_list(mysqli $db, array $query): array
	{
		$tenantR = tessera_sql_tenant_eq('r');
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['s.start_route', 's.end_route', 's.name', 's.route_name']);
		$rows = tessera_network_rows(
			$db,
			"SELECT s.id, s.name, s.start_route, s.end_route, s.route_name, s.currency, s.currency_symbol, s.fare_price,
			        s.a_time, s.d_time, s.active_day, s.detail, s.main_route_no, s.sub_route_id, s.status
			 FROM sub_route s
			 WHERE EXISTS (SELECT 1 FROM routes r WHERE r.route_id = s.main_route_no AND {$tenantR})"
			. $like['sql'] . ' ORDER BY s.id DESC LIMIT 500',
			$like['types'],
			$like['params']
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load sub-routes', 500);
		}
		$items = array_map('tessera_network_sub_route_item', $rows);
		return tessera_network_ok(['items' => $items, 'total' => count($items)]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_sub_route_create(mysqli $db, array $body): array
	{
		$start = trim((string) ($body['start_route'] ?? $body['starting_point'] ?? ''));
		$end = trim((string) ($body['end_route'] ?? $body['end_point'] ?? ''));
		$name = trim((string) ($body['name'] ?? $body['initial'] ?? ''));
		$currencyName = trim((string) ($body['currency'] ?? ''));
		$mainId = (int) ($body['main_route_id'] ?? 0);
		$mainLabel = trim((string) ($body['main_route'] ?? ''));
		$detailRaw = (string) ($body['detail'] ?? $body['details'] ?? '');
		$fare = tessera_network_fare($body['fare'] ?? $body['fair'] ?? $body['price'] ?? '');
		if ($start === '' || $end === '' || $name === '') {
			return tessera_network_fail('validation_error', 'Start, end, and name are required.', 422);
		}
		if ($fare === null) {
			return tessera_network_fail('validation_error', 'Enter a fare.', 422);
		}
		$currency = tessera_network_currency($db, $currencyName);
		if ($currency === null) {
			return tessera_network_fail('validation_error', 'Choose a currency from the list.', 422);
		}
		$tenant = tessera_sql_tenant_eq();
		if ($mainId > 0) {
			$main = tessera_network_one(
				$db,
				"SELECT id, route_id, start_route, end_route, route_name, fullname FROM routes WHERE id = ? AND {$tenant} LIMIT 1",
				'i',
				[$mainId]
			);
		} else {
			$main = tessera_network_one(
				$db,
				"SELECT id, route_id, start_route, end_route, route_name, fullname FROM routes
				 WHERE (route_name = ? OR fullname = ?) AND {$tenant} LIMIT 1",
				'ss',
				[$mainLabel, $mainLabel]
			);
		}
		if (!$main) {
			return tessera_network_fail('not_found', 'Main route not found.', 404);
		}
		$mainCode = (int) $main['route_id'];
		$dup = tessera_network_one(
			$db,
			'SELECT id FROM sub_route WHERE start_route = ? AND end_route = ? AND main_route_no = ? LIMIT 1',
			'ssi',
			[$start, $end, $mainCode]
		);
		if ($dup) {
			return tessera_network_fail('conflict', 'That sub-route already exists.', 409);
		}
		$last = tessera_network_one($db, 'SELECT sub_route_id FROM sub_route ORDER BY id DESC LIMIT 1');
		$code = $last ? ((int) $last['sub_route_id']) + 1 : 0;
		$detail = tessera_network_encode_detail($detailRaw, false);
		$arrival = tessera_network_clock((string) ($body['a_time'] ?? ''), '08:00');
		$departure = tessera_network_clock((string) ($body['d_time'] ?? $body['d_ptime'] ?? ''), '06:00');
		$active = tessera_network_digits($body['active_day'] ?? $body['a_aft'] ?? 0);
		$intl = (int) round($currency['rate'] * $fare);
		$mainLabelStored = trim((string) ($main['route_name'] ?? '')) !== '' ? (string) $main['route_name'] : (string) $main['fullname'];
		if ($mainLabel !== '') {
			$mainLabelStored = $mainLabel;
		}
		$status = 'active';
		$vip = 0;
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO sub_route (
				start_route, end_route, fare_price, fare_price_international, sub_route_id, detail, name,
				main_route_no, a_time, d_time, active_day, route_name, currency, currency_symbol, status, vip_price
			) VALUES (?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?)',
			'ssiiissississssi',
			[
				$start, $end, $fare, $intl, $code, $detail, $name,
				$mainCode, $arrival, $departure, $active, $mainLabelStored, $currency['country'], $currency['initial'], $status, $vip,
			]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save sub-route', 500);
		}
		$newId = (int) $db->insert_id;
		$mainStart = (string) $main['start_route'];
		$mainEnd = (string) $main['end_route'];
		$listed = tessera_network_one(
			$db,
			'SELECT id FROM sub_route_list WHERE start_route = ? AND end_route = ? AND location = ? LIMIT 1',
			'sss',
			[$mainStart, $mainEnd, $end]
		);
		if (!$listed) {
			tessera_network_exec(
				$db,
				'INSERT INTO sub_route_list (start_route, end_route, location) VALUES (?,?,?)',
				'sss',
				[$mainStart, $mainEnd, $end]
			);
		}
		$saved = tessera_network_one($db, 'SELECT * FROM sub_route WHERE id = ? LIMIT 1', 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_network_sub_route_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_sub_route_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a sub-route to delete.', 422);
		}
		$tenantR = tessera_sql_tenant_eq('r');
		$row = tessera_network_one(
			$db,
			"SELECT s.id FROM sub_route s
			 WHERE s.id = ? AND EXISTS (SELECT 1 FROM routes r WHERE r.route_id = s.main_route_no AND {$tenantR})
			 LIMIT 1",
			'i',
			[$id]
		);
		if (!$row) {
			return tessera_network_fail('not_found', 'Sub-route not found.', 404);
		}
		$exec = tessera_network_exec($db, 'DELETE FROM sub_route WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete sub-route', 500);
		}
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_network_point_item(array $row): array
	{
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'point' => (string) ($row['point'] ?? ''),
			'time' => (string) ($row['time'] ?? ''),
			'detail' => (string) ($row['detail'] ?? ''),
			'route_name' => (string) ($row['route_name'] ?? ''),
			'route_id' => (string) ($row['route_id'] ?? ''),
			'start_location' => (string) ($row['start_location'] ?? ''),
			'end_location' => (string) ($row['end_location'] ?? ''),
			'sub_route_id' => isset($row['sub_route_id']) ? (int) $row['sub_route_id'] : null,
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_network_route_points_list(mysqli $db, array $query): array
	{
		$tenantR = tessera_sql_tenant_eq('r');
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['p.name', 'p.route_name', 'p.point']);
		$rows = tessera_network_rows(
			$db,
			"SELECT p.id, p.name, p.point, p.time, p.detail, p.route_name, p.route_id, p.start_location, p.end_location
			 FROM route_points p
			 WHERE EXISTS (SELECT 1 FROM routes r WHERE r.route_id = p.route_id AND {$tenantR})"
			. $like['sql'] . ' ORDER BY p.id DESC LIMIT 500',
			$like['types'],
			$like['params']
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load route points', 500);
		}
		$items = array_map('tessera_network_point_item', $rows);
		return tessera_network_ok(['items' => $items, 'total' => count($items)]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_route_point_create(mysqli $db, array $body): array
	{
		$routePk = (int) ($body['route_pk'] ?? $body['route_id'] ?? 0);
		$point = trim((string) ($body['point'] ?? $body['point_start'] ?? ''));
		$place = trim((string) ($body['name'] ?? $body['place_name'] ?? ''));
		if ($routePk < 1 || $place === '' || !in_array($point, ['starting', 'end'], true)) {
			return tessera_network_fail('validation_error', 'Choose a route, a point, and a place name.', 422);
		}
		if (strlen($place) > 300) {
			return tessera_network_fail('validation_error', 'Place name is too long.', 422);
		}
		$tenant = tessera_sql_tenant_eq();
		$route = tessera_network_one(
			$db,
			"SELECT id, route_id, route_name, start_route, end_route FROM routes WHERE id = ? AND {$tenant} LIMIT 1",
			'i',
			[$routePk]
		);
		if (!$route) {
			return tessera_network_fail('not_found', 'Route not found.', 404);
		}
		$code = (string) (int) $route['route_id'];
		$dup = tessera_network_one(
			$db,
			'SELECT id FROM route_points WHERE name = ? AND point = ? AND route_id = ? LIMIT 1',
			'sss',
			[$place, $point, $code]
		);
		if ($dup) {
			return tessera_network_fail('conflict', 'That route point is already registered.', 409);
		}
		$routeName = (string) ($route['route_name'] ?? '');
		$start = (string) ($route['start_route'] ?? '');
		$end = (string) ($route['end_route'] ?? '');
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO route_points (name, route_id, point, detail, route_name, start_location, end_location) VALUES (?,?,?,?,?,?,?)',
			'sssssss',
			[$place, $code, $point, $place, $routeName, $start, $end]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save route point', 500);
		}
		$newId = (int) $db->insert_id;
		$saved = tessera_network_one($db, 'SELECT * FROM route_points WHERE id = ? LIMIT 1', 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_network_point_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_route_point_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a route point to delete.', 422);
		}
		$tenantR = tessera_sql_tenant_eq('r');
		$row = tessera_network_one(
			$db,
			"SELECT p.id FROM route_points p
			 WHERE p.id = ? AND EXISTS (SELECT 1 FROM routes r WHERE r.route_id = p.route_id AND {$tenantR})
			 LIMIT 1",
			'i',
			[$id]
		);
		if (!$row) {
			return tessera_network_fail('not_found', 'Route point not found.', 404);
		}
		$exec = tessera_network_exec($db, 'DELETE FROM route_points WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete route point', 500);
		}
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $query */
	function tessera_network_sub_route_points_list(mysqli $db, array $query): array
	{
		$tenantR = tessera_sql_tenant_eq('r');
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['p.name', 'p.route_name', 'p.point']);
		$rows = tessera_network_rows(
			$db,
			"SELECT p.id, p.name, p.point, p.time, p.detail, p.route_name, p.route_id, p.start_location, p.end_location, p.sub_route_id
			 FROM sub_route_points p
			 WHERE EXISTS (SELECT 1 FROM routes r WHERE r.route_id = p.route_id AND {$tenantR})"
			. $like['sql'] . ' ORDER BY p.id DESC LIMIT 500',
			$like['types'],
			$like['params']
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load sub-route points', 500);
		}
		$items = array_map('tessera_network_point_item', $rows);
		return tessera_network_ok(['items' => $items, 'total' => count($items)]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_sub_route_point_create(mysqli $db, array $body): array
	{
		$subId = (int) ($body['sub_route_id'] ?? 0);
		$subName = trim((string) ($body['route_name'] ?? ''));
		$point = trim((string) ($body['point'] ?? $body['point_start'] ?? ''));
		$place = trim((string) ($body['name'] ?? $body['place_name'] ?? ''));
		$timeRaw = trim((string) ($body['a_time'] ?? $body['time'] ?? ''));
		if ($place === '' || $timeRaw === '' || !in_array($point, ['starting', 'end'], true)) {
			return tessera_network_fail('validation_error', 'Choose a sub-route, a point, a place, and a time.', 422);
		}
		$tenantR = tessera_sql_tenant_eq('r');
		if ($subId > 0) {
			$sub = tessera_network_one(
				$db,
				"SELECT s.* FROM sub_route s
				 WHERE s.id = ? AND EXISTS (SELECT 1 FROM routes r WHERE r.route_id = s.main_route_no AND {$tenantR})
				 LIMIT 1",
				'i',
				[$subId]
			);
		} else {
			$sub = tessera_network_one(
				$db,
				"SELECT s.* FROM sub_route s
				 WHERE s.name = ? AND EXISTS (SELECT 1 FROM routes r WHERE r.route_id = s.main_route_no AND {$tenantR})
				 LIMIT 1",
				's',
				[$subName]
			);
		}
		if (!$sub) {
			return tessera_network_fail('not_found', 'Sub-route not found.', 404);
		}
		$mainCode = (int) $sub['main_route_no'];
		$routeName = (string) $sub['name'];
		/* Classic insert_route_points_sub.php checks point = sub-route name (not the starting/end value). */
		$dup = tessera_network_one(
			$db,
			'SELECT id FROM sub_route_points WHERE point = ? AND route_id = ? LIMIT 1',
			'si',
			[$routeName, $mainCode]
		);
		if ($dup) {
			return tessera_network_fail('conflict', 'That sub-route point is already registered.', 409);
		}
		$clock = tessera_network_clock($timeRaw, '08:00');
		$detail = $place . ' - ' . $clock;
		$subCode = (int) $sub['sub_route_id'];
		$start = (string) ($sub['start_route'] ?? '');
		$end = (string) ($sub['end_route'] ?? '');
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO sub_route_points (route_id, time, detail, point, route_name, sub_route_id, name, start_location, end_location)
			 VALUES (?,?,?,?,?,?,?,?,?)',
			'issssisss',
			[$mainCode, $clock, $detail, $point, $routeName, $subCode, $place, $start, $end]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save sub-route point', 500);
		}
		$newId = (int) $db->insert_id;
		$saved = tessera_network_one($db, 'SELECT * FROM sub_route_points WHERE id = ? LIMIT 1', 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_network_point_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_sub_route_point_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a sub-route point to delete.', 422);
		}
		$tenantR = tessera_sql_tenant_eq('r');
		$row = tessera_network_one(
			$db,
			"SELECT p.id FROM sub_route_points p
			 WHERE p.id = ? AND EXISTS (SELECT 1 FROM routes r WHERE r.route_id = p.route_id AND {$tenantR})
			 LIMIT 1",
			'i',
			[$id]
		);
		if (!$row) {
			return tessera_network_fail('not_found', 'Sub-route point not found.', 404);
		}
		$exec = tessera_network_exec($db, 'DELETE FROM sub_route_points WHERE id = ? LIMIT 1', 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete sub-route point', 500);
		}
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}

	/** @param array<string,mixed> $row */
	function tessera_network_trip_item(array $row): array
	{
		$status = (string) ($row['status'] ?? '');
		return [
			'id' => (int) ($row['id'] ?? 0),
			'route_id' => (int) ($row['route_id'] ?? 0),
			'day' => (string) ($row['day'] ?? ''),
			'position' => (int) ($row['position'] ?? 0),
			'bus' => (string) ($row['bus'] ?? ''),
			'status' => $status,
			'status_label' => $status === 'active' ? 'Deactivate' : 'Activate',
			'route_name' => (string) ($row['route_name'] ?? ''),
			'fullname' => (string) ($row['fullname'] ?? ''),
			'detail' => tessera_network_decode_detail((string) ($row['detail'] ?? '')),
			'departure' => (string) ($row['departure'] ?? ''),
			'arrival' => (string) ($row['arrival'] ?? ''),
		];
	}

	/** @param array<string,mixed> $query */
	function tessera_network_trip_templates_list(mysqli $db, array $query): array
	{
		$tenant = tessera_sql_tenant_eq();
		$like = tessera_network_like_clause((string) ($query['q'] ?? ''), ['bus', 'day', 'route_name', 'fullname', 'status']);
		$types = $like['types'];
		$params = $like['params'];
		$extra = '';
		$day = tessera_network_weekday((string) ($query['day'] ?? ''));
		if ($day) {
			$extra .= ' AND day = ?';
			$types .= 's';
			$params[] = $day['day'];
		}
		$bus = trim((string) ($query['bus'] ?? ''));
		if ($bus !== '' && strlen($bus) <= 80) {
			$extra .= ' AND bus = ?';
			$types .= 's';
			$params[] = $bus;
		}
		$status = trim((string) ($query['status'] ?? ''));
		if ($status === 'active' || $status === 'Not Active') {
			$extra .= ' AND status = ?';
			$types .= 's';
			$params[] = $status;
		}
		$rows = tessera_network_rows(
			$db,
			"SELECT id, route_id, day, position, bus, status, route_name, fullname, detail, departure, arrival
			 FROM route_day WHERE {$tenant}" . $extra . $like['sql'] . ' ORDER BY position ASC, id DESC LIMIT 500',
			$types,
			$params
		);
		if ($rows === null) {
			return tessera_network_fail('server_error', 'Could not load trip templates', 500);
		}
		$items = array_map('tessera_network_trip_item', $rows);
		return tessera_network_ok(['items' => $items, 'total' => count($items)]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_trip_template_create(mysqli $db, array $body): array
	{
		$weekday = tessera_network_weekday((string) ($body['day'] ?? $body['day_of_week'] ?? ''));
		$routePk = (int) ($body['route_id'] ?? $body['route_pk'] ?? 0);
		$bus = trim((string) ($body['bus'] ?? $body['vehicle_name'] ?? ''));
		$detailRaw = (string) ($body['detail'] ?? '');
		if ($weekday === null || $routePk < 1 || $bus === '') {
			return tessera_network_fail('validation_error', 'Choose a weekday, a main route, and a bus.', 422);
		}
		if ($weekday['position'] < 1 || $weekday['position'] > 7) {
			return tessera_network_fail('validation_error', 'Weekday must be Monday through Sunday.', 422);
		}
		$tenant = tessera_sql_tenant_eq();
		$tenantId = (int) tessera_current_tenant_id();
		$route = tessera_network_one(
			$db,
			"SELECT id, fullname, route_name, route_id FROM routes WHERE id = ? AND {$tenant} LIMIT 1",
			'i',
			[$routePk]
		);
		if (!$route) {
			return tessera_network_fail('not_found', 'Route not found.', 404);
		}
		$dup = tessera_network_one(
			$db,
			"SELECT id FROM route_day WHERE bus = ? AND day = ? AND route_id = ? AND {$tenant} LIMIT 1",
			'ssi',
			[$bus, $weekday['day'], $routePk]
		);
		if ($dup) {
			return tessera_network_fail('conflict', 'A template for this bus, weekday, and route already exists.', 409);
		}
		$detail = tessera_network_encode_detail($detailRaw, false);
		$routeName = (string) ($route['route_name'] ?? '');
		$fullname = (string) ($route['fullname'] ?? '');
		$empty = '';
		$zero = 0;
		$status = 'active';
		$buttonName = 'De activate';
		$buttonIcon = 'uil uil-exclamation-octagon me-2';
		$buttonClass = 'btn btn-soft-danger';
		$position = $weekday['position'];
		$exec = tessera_network_exec(
			$db,
			'INSERT INTO route_day (
				route_id, day, 24day, 12hours, mints, bus, arrival, departure, detail, status,
				reporting_time, route_name, route_active_time, button_name, button_icon, button_class, position, fullname, tenant_id
			) VALUES (?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?)',
			'isssisssssssssssisi',
			[
				$routePk, $weekday['day'], $empty, $empty, $zero, $bus, $empty, $empty, $detail, $status,
				$empty, $routeName, $empty, $buttonName, $buttonIcon, $buttonClass, $position, $fullname, $tenantId,
			]
		);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not save trip template', 500);
		}
		$newId = (int) $db->insert_id;
		tessera_network_sync_traveling_time($db, $routePk);
		$saved = tessera_network_one($db, "SELECT * FROM route_day WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$newId]);
		return tessera_network_ok(['item' => $saved ? tessera_network_trip_item($saved) : ['id' => $newId]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_trip_template_status(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a trip template.', 422);
		}
		$tenant = tessera_sql_tenant_eq();
		$row = tessera_network_one($db, "SELECT id, status FROM route_day WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$id]);
		if (!$row) {
			return tessera_network_fail('not_found', 'Trip template not found.', 404);
		}
		if ((string) $row['status'] === 'active') {
			$exec = tessera_network_exec(
				$db,
				"UPDATE route_day SET status='Not Active', button_name='Activate', button_icon='uil uil-check me-2', button_class='btn btn-soft-success'
				 WHERE id = ? AND {$tenant}",
				'i',
				[$id]
			);
		} else {
			$exec = tessera_network_exec(
				$db,
				"UPDATE route_day SET status='active', button_name='De activate', button_icon='uil uil-exclamation-octagon me-2', button_class='btn btn-soft-danger'
				 WHERE id = ? AND {$tenant}",
				'i',
				[$id]
			);
		}
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not change status', 500);
		}
		$saved = tessera_network_one($db, "SELECT * FROM route_day WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$id]);
		return tessera_network_ok(['item' => $saved ? tessera_network_trip_item($saved) : ['id' => $id]]);
	}

	/** @param array<string,mixed> $body */
	function tessera_network_trip_template_delete(mysqli $db, array $body): array
	{
		$id = (int) ($body['id'] ?? 0);
		if ($id < 1) {
			return tessera_network_fail('validation_error', 'Choose a trip template to delete.', 422);
		}
		$tenant = tessera_sql_tenant_eq();
		$row = tessera_network_one($db, "SELECT id FROM route_day WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$id]);
		if (!$row) {
			return tessera_network_fail('not_found', 'Trip template not found.', 404);
		}
		$exec = tessera_network_exec($db, "DELETE FROM route_day WHERE id = ? AND {$tenant} LIMIT 1", 'i', [$id]);
		if (!$exec['ok']) {
			return tessera_network_fail('server_error', 'Could not delete trip template', 500);
		}
		tessera_network_exec($db, 'DELETE FROM traveling_time WHERE route_day_id = ?', 'i', [$id]);
		return tessera_network_ok(['deleted' => true, 'id' => $id]);
	}
}
