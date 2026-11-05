<?php
/**
 * Shared Cargo vehicle manifest helpers (C-P0-04).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_manifests_assigned_routes')) {
	/**
	 * @return list<array{name:string,status:string}>
	 */
	function tessera_cargo_manifests_assigned_routes(mysqli $db, string $username): array
	{
		$u = $db->real_escape_string($username);
		$out = [];
		$q = $db->query(
			"SELECT route_full_name, status FROM assigned_routes WHERE username='{$u}' ORDER BY preference DESC, id DESC"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$name = trim((string) ($row['route_full_name'] ?? ''));
				if ($name === '') {
					continue;
				}
				$out[] = [
					'name' => $name,
					'status' => (string) ($row['status'] ?? ''),
				];
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_cargo_manifests_filters')) {
	/**
	 * @return array{routes:list<string>,vehicles:list<array{name:string,driver:string,conductor:string}>}
	 */
	function tessera_cargo_manifests_filters(mysqli $db, string $username): array
	{
		$routes = [];
		foreach (tessera_cargo_manifests_assigned_routes($db, $username) as $r) {
			if (!in_array($r['name'], $routes, true)) {
				$routes[] = $r['name'];
			}
		}
		$vehicles = [];
		$vq = $db->query('SELECT name, driver, conductor FROM vehicles ORDER BY name ASC');
		if ($vq) {
			while ($row = $vq->fetch_assoc()) {
				$name = trim((string) ($row['name'] ?? ''));
				if ($name === '') {
					continue;
				}
				$vehicles[] = [
					'name' => $name,
					'driver' => (string) ($row['driver'] ?? ''),
					'conductor' => (string) ($row['conductor'] ?? ''),
				];
			}
		}
		return ['routes' => $routes, 'vehicles' => $vehicles];
	}
}

if (!function_exists('tessera_cargo_manifests_build_where')) {
	/**
	 * @param array{q?:string,route?:string,vehicle?:string,date_from?:string,date_to?:string} $filters
	 * @return array{where:string,order:string}
	 */
	function tessera_cargo_manifests_build_where(mysqli $db, array $filters): array
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$parts = [tessera_sql_tenant_eq()];

		$route = trim((string) ($filters['route'] ?? ''));
		if ($route !== '') {
			$parts[] = "Route='" . $db->real_escape_string($route) . "'";
		}
		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		if ($vehicle !== '') {
			$parts[] = "Name='" . $db->real_escape_string($vehicle) . "'";
		}

		$dateFrom = trim((string) ($filters['date_from'] ?? ''));
		$dateTo = trim((string) ($filters['date_to'] ?? ''));
		$order = 'date_sort DESC, id DESC';
		if ($dateFrom !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
			$parts[] = "date_sort >= '" . $db->real_escape_string($dateFrom) . "'";
			$order = 'date_sort ASC, id ASC';
		}
		if ($dateTo !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
			$parts[] = "date_sort <= '" . $db->real_escape_string($dateTo) . "'";
			$order = 'date_sort ASC, id ASC';
		}

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(Name LIKE '{$like}' OR Driver LIKE '{$like}' OR Conductor LIKE '{$like}' OR code LIKE '{$like}' OR Route LIKE '{$like}' OR date LIKE '{$like}')";
		}

		return ['where' => implode(' AND ', $parts), 'order' => $order];
	}
}

if (!function_exists('tessera_cargo_manifests_can_edit_route')) {
	function tessera_cargo_manifests_can_edit_route(mysqli $db, string $username, string $route): bool
	{
		foreach (tessera_cargo_manifests_assigned_routes($db, $username) as $r) {
			if ($r['name'] === $route && strcasecmp($r['status'], 'sender') === 0) {
				return true;
			}
		}
		/* Super / manager often have all routes without sender flag — allow if route assigned at all. */
		foreach (tessera_cargo_manifests_assigned_routes($db, $username) as $r) {
			if ($r['name'] === $route) {
				return true;
			}
		}
		$u = $db->real_escape_string($username);
		$roleQ = $db->query("SELECT role FROM users WHERE username='{$u}' LIMIT 1");
		if ($roleQ && ($row = $roleQ->fetch_assoc())) {
			$role = (string) ($row['role'] ?? '');
			if (stripos($role, 'super') !== false || stripos($role, 'admin') !== false) {
				return true;
			}
		}
		return false;
	}
}

if (!function_exists('tessera_cargo_manifests_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_cargo_manifests_row_to_item(mysqli $db, string $username, array $row): array
	{
		$id = (int) ($row['id'] ?? 0);
		$name = (string) ($row['Name'] ?? '');
		$date = (string) ($row['date'] ?? '');
		$route = (string) ($row['Route'] ?? '');
		$driver = (string) ($row['Driver'] ?? '');
		$conductor = (string) ($row['Conductor'] ?? '');
		$code = (string) ($row['code'] ?? '');
		$status = (string) ($row['status'] ?? '');
		$currency = (string) ($row['currency'] ?? '');
		$paid = (float) ($row['paid'] ?? 0);
		$toPay = (float) ($row['to_pay'] ?? 0);
		$locked = ((string) ($row['disable2'] ?? '')) === 'disabled';
		$canEdit = !$locked && tessera_cargo_manifests_can_edit_route($db, $username, $route);

		$dateLabel = $date;
		$ts = strtotime($date);
		if ($ts) {
			$dateLabel = date('d M Y', $ts);
		}

		$remarkRaw = (string) ($row['remark_test'] ?? '');
		$remarkPlain = '';
		if ($remarkRaw !== '') {
			$decoded = base64_decode($remarkRaw, true);
			if ($decoded !== false) {
				$remarkPlain = trim(html_entity_decode(strip_tags($decoded), ENT_QUOTES, 'UTF-8'));
			}
		}

		$q = [
			'date' => $date,
			'date2' => $date,
			'date3' => $name,
			'open' => $status,
			'route' => $route,
			'driver' => $driver,
			'Conductor' => $conductor,
			'code' => $code,
		];
		$viewQs = http_build_query($q);
		$loadQs = http_build_query([
			'bus' => $name,
			'date' => $date,
			'id' => $code,
			'open' => $status,
			'driver' => $driver,
			'conductor' => $conductor,
			'manifest_id' => $id,
			'route_name' => $route,
			'currency' => $currency,
		]);
		$checkoutQs = http_build_query($q);
		$chargesQs = http_build_query([
			'date' => $date,
			'name' => $name,
			'open' => $status,
			'driver' => $driver,
			'conductor' => $conductor,
		]);

		return [
			'id' => $id,
			'code' => $code,
			'vehicle' => $name,
			'route' => $route,
			'driver' => $driver,
			'conductor' => $conductor,
			'date' => $date,
			'date_label' => $dateLabel,
			'status' => $status,
			'currency' => $currency,
			'paid' => $paid,
			'to_pay' => $toPay,
			'total' => $paid + $toPay,
			'luggage_no' => (int) ($row['luggage_no'] ?? 0),
			'locked' => $locked,
			'can_edit' => $canEdit,
			'can_sms' => !$locked,
			'can_delete' => ((float) ($row['due'] ?? 0)) == 0.0,
			'remark' => $remarkPlain,
			'remark_html' => (string) ($row['remark_html'] ?? ''),
			'view_url' => '/cargo/vehicle_report_new.php?' . $viewQs,
			'load_url' => '/operator/app.php?module=cargo&page=check_luggages.php&' . $loadQs,
			'checkout_url' => '/cargo/vehicle_report_new_edit.php?' . $checkoutQs,
			'charges_url' => '/cargo/print_manifest.php?' . $chargesQs,
		];
	}
}

if (!function_exists('tessera_cargo_manifests_send_sms')) {
	/**
	 * Notify senders for parcels on this vehicle+date (classic send_bulk.php via tessera_cargo_notify).
	 * @return array{ok:bool,error?:string,notified?:int,item?:array<string,mixed>}
	 */
	function tessera_cargo_manifests_send_sms(mysqli $db, string $username, int $id): array
	{
		if ($id < 1) {
			return ['ok' => false, 'error' => 'Manifest id is required'];
		}
		$sel = $db->query(
			"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, due, remark_test, remark_html
			 FROM vehicle_name WHERE id={$id} LIMIT 1"
		);
		$row = $sel ? $sel->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Manifest not found'];
		}
		if ((string) ($row['disable2'] ?? '') === 'disabled') {
			return ['ok' => false, 'error' => 'SMS already sent for this manifest'];
		}

		$bus = (string) ($row['Name'] ?? '');
		$date = (string) ($row['date'] ?? '');
		$busEsc = $db->real_escape_string($bus);
		$dateEsc = $db->real_escape_string($date);
		$newDate = date('d-M-y', strtotime($date) ?: time());

		if (is_file(dirname(__DIR__) . '/includes/tessera_cargo_notify.php')) {
			require_once dirname(__DIR__) . '/includes/tessera_cargo_notify.php';
		}

		$notified = 0;
		$mq = $db->query("SELECT code FROM manifest WHERE trip_date='{$dateEsc}' AND bus='{$busEsc}'");
		if ($mq) {
			while ($m = $mq->fetch_assoc()) {
				$codeEsc = $db->real_escape_string((string) ($m['code'] ?? ''));
				$pq = $db->query(
					"SELECT s_no, code, s_name, account FROM parcel_info WHERE code='{$codeEsc}' LIMIT 1"
				);
				$parcel = $pq ? $pq->fetch_assoc() : null;
				if (!$parcel) {
					continue;
				}
				$phone = (string) ($parcel['s_no'] ?? '');
				$code = (string) ($parcel['code'] ?? '');
				$name = (string) ($parcel['s_name'] ?? '');
				if ($phone !== '' && function_exists('tessera_cargo_notify')) {
					tessera_cargo_notify(
						'cargo.packed',
						$phone,
						$code,
						"Habari {$name}, mzigo {$code} umepakiwa tarehe {$newDate}."
					);
					$notified++;
				}
			}
		}

		$button = base64_encode('sms');
		$db->query(
			"UPDATE vehicle_name SET img='{$button}', disable2='disabled' WHERE id={$id} LIMIT 1"
		);

		$sumToBe = 0.0;
		$sumPaid = 0.0;
		$sq = $db->query(
			"SELECT COALESCE(SUM(to_be),0) AS t, COALESCE(SUM(paid),0) AS p
			 FROM manifest WHERE trip_date='{$dateEsc}' AND bus='{$busEsc}'"
		);
		if ($sq && ($sr = $sq->fetch_assoc())) {
			$sumToBe = (float) ($sr['t'] ?? 0);
			$sumPaid = (float) ($sr['p'] ?? 0);
		}
		$total = $sumToBe + $sumPaid;
		$db->query(
			"UPDATE vehicle_name SET to_pay='{$sumToBe}', paid='{$sumPaid}', due='{$total}'
			 WHERE id={$id} LIMIT 1"
		);

		$gq = $db->query(
			"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, due, remark_test, remark_html
			 FROM vehicle_name WHERE id={$id} LIMIT 1"
		);
		$grow = $gq ? $gq->fetch_assoc() : $row;
		return [
			'ok' => true,
			'notified' => $notified,
			'item' => tessera_cargo_manifests_row_to_item($db, $username, $grow),
		];
	}
}

if (!function_exists('tessera_cargo_manifests_save_remark')) {
	/**
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_cargo_manifests_save_remark(
		mysqli $db,
		string $username,
		int $id,
		string $remarkText,
		string $remarkHtml = ''
	): array {
		if ($id < 1) {
			return ['ok' => false, 'error' => 'Manifest id is required'];
		}
		$text = trim(strip_tags($remarkText));
		if ($text === '' || strcasecmp($text, 'Add Remark') === 0) {
			return ['ok' => false, 'error' => 'Enter a remark'];
		}
		$html = trim($remarkHtml) !== '' ? $remarkHtml : ('<p>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p>');
		$textB64 = $db->real_escape_string(base64_encode($text));
		$htmlB64 = $db->real_escape_string(base64_encode($html));
		$userEsc = $db->real_escape_string($username);
		$ok = $db->query(
			"UPDATE vehicle_name
			 SET remark_test='{$textB64}', remark_html='{$htmlB64}', username_remark='{$userEsc}'
			 WHERE id={$id} LIMIT 1"
		);
		if (!$ok || $db->affected_rows < 1) {
			$chk = $db->query("SELECT id FROM vehicle_name WHERE id={$id} LIMIT 1");
			if (!$chk || $chk->num_rows < 1) {
				return ['ok' => false, 'error' => 'Manifest not found'];
			}
		}
		$gq = $db->query(
			"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, due, remark_test, remark_html
			 FROM vehicle_name WHERE id={$id} LIMIT 1"
		);
		$grow = $gq ? $gq->fetch_assoc() : null;
		if (!$grow) {
			return ['ok' => false, 'error' => 'Manifest not found'];
		}
		return ['ok' => true, 'item' => tessera_cargo_manifests_row_to_item($db, $username, $grow)];
	}
}

if (!function_exists('tessera_cargo_manifests_delete')) {
	/**
	 * Delete empty vehicle_name row (classic delete_vehicle_name.php — due must be 0).
	 * @return array{ok:bool,error?:string,id?:int}
	 */
	function tessera_cargo_manifests_delete(mysqli $db, int $id): array
	{
		if ($id < 1) {
			return ['ok' => false, 'error' => 'Manifest id is required'];
		}
		$sel = $db->query("SELECT id, due, Name, date FROM vehicle_name WHERE id={$id} LIMIT 1");
		$row = $sel ? $sel->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Manifest not found'];
		}
		if ((float) ($row['due'] ?? 0) != 0.0) {
			return ['ok' => false, 'error' => 'This manifest has loaded cargo and cannot be deleted'];
		}
		$busEsc = $db->real_escape_string((string) ($row['Name'] ?? ''));
		$dateEsc = $db->real_escape_string((string) ($row['date'] ?? ''));
		$onManifest = $db->query(
			"SELECT id FROM manifest WHERE bus='{$busEsc}' AND trip_date='{$dateEsc}' LIMIT 1"
		);
		if ($onManifest && $onManifest->num_rows > 0) {
			return ['ok' => false, 'error' => 'Remove loaded luggage before deleting this manifest'];
		}
		if (!$db->query("DELETE FROM vehicle_name WHERE id={$id} LIMIT 1")) {
			return ['ok' => false, 'error' => 'Could not delete manifest'];
		}
		return ['ok' => true, 'id' => $id];
	}
}

if (!function_exists('tessera_cargo_manifests_list')) {
	/**
	 * @param array{q?:string,route?:string,vehicle?:string,date_from?:string,date_to?:string} $filters
	 * @return array{items:list<array<string,mixed>>,total:int}
	 */
	function tessera_cargo_manifests_list(
		mysqli $db,
		string $username,
		array $filters,
		int $page,
		int $perPage
	): array {
		$built = tessera_cargo_manifests_build_where($db, $filters);
		$where = $built['where'];
		$order = $built['order'];

		$total = 0;
		$cq = $db->query("SELECT COUNT(*) AS c FROM vehicle_name WHERE {$where}");
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$total = (int) ($cr['c'] ?? 0);
		}

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$offset = ($page - 1) * $perPage;

		$items = [];
		$sql = "SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency,
			luggage_no, to_pay, paid, remark_test, remark_html
			FROM vehicle_name WHERE {$where} ORDER BY {$order} LIMIT {$offset}, {$perPage}";
		$q = $db->query($sql);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$items[] = tessera_cargo_manifests_row_to_item($db, $username, $row);
			}
		}
		return ['items' => $items, 'total' => $total];
	}
}

if (!function_exists('tessera_cargo_manifests_create')) {
	/**
	 * @param array{vehicle:string,driver:string,conductor:string,route:string,date:string} $payload
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_cargo_manifests_create(mysqli $db, string $username, array $payload): array
	{
		$vehicle = preg_replace('/\s+/', '_', trim((string) ($payload['vehicle'] ?? ''))) ?? '';
		$driver = trim((string) ($payload['driver'] ?? ''));
		$conductor = trim((string) ($payload['conductor'] ?? ''));
		$route = trim((string) ($payload['route'] ?? ''));
		$dateRaw = trim((string) ($payload['date'] ?? ''));
		if ($vehicle === '' || $route === '' || $dateRaw === '') {
			return ['ok' => false, 'error' => 'Vehicle, route, and date are required'];
		}
		$ts = strtotime($dateRaw);
		if ($ts === false) {
			return ['ok' => false, 'error' => 'Invalid date'];
		}
		$date = date('Y-m-d', $ts);
		if (!tessera_cargo_manifests_can_edit_route($db, $username, $route)) {
			return ['ok' => false, 'error' => 'You cannot register a manifest on this route'];
		}

		$routeEsc = $db->real_escape_string($route);
		$initial = '';
		$currency = 'TZS';
		$rq = $db->query("SELECT initial, currency_symbol FROM routes WHERE route_full_name='{$routeEsc}' LIMIT 1");
		if ($rq && ($rrow = $rq->fetch_assoc())) {
			$initial = (string) ($rrow['initial'] ?? '');
			$currency = (string) (($rrow['currency_symbol'] ?? '') !== '' ? $rrow['currency_symbol'] : 'TZS');
		}

		$code = 1;
		$cq = $db->query("SELECT code FROM manifest_code WHERE route_name='{$routeEsc}' LIMIT 1");
		if (!$cq || $cq->num_rows === 0) {
			$db->query("INSERT INTO manifest_code (code, route_name) VALUES ('1', '{$routeEsc}')");
		} else {
			$row = $cq->fetch_assoc();
			$code = ((int) ($row['code'] ?? 0)) + 1;
			$db->query("UPDATE manifest_code SET code='{$code}' WHERE route_name='{$routeEsc}'");
		}
		$manifestCode = sprintf('%04d', $code) . $initial;

		$vehicleEsc = $db->real_escape_string($vehicle);
		$dateEsc = $db->real_escape_string($date);
		$dup = $db->query("SELECT id FROM vehicle_name WHERE Name='{$vehicleEsc}' AND date='{$dateEsc}' LIMIT 1");
		if ($dup && $dup->num_rows > 0) {
			return ['ok' => false, 'error' => 'A manifest for this vehicle and date already exists'];
		}

		$drv = $db->real_escape_string($driver !== '' ? $driver : '-');
		$con = $db->real_escape_string($conductor !== '' ? $conductor : '-');
		$by = $db->real_escape_string($username);
		$codeEsc = $db->real_escape_string($manifestCode);
		$cur = $db->real_escape_string($currency);
		$img = $db->real_escape_string(base64_encode('wa'));

		$insert = $db->query(
			"INSERT INTO vehicle_name (
				Name, Driver, Conductor, Route, luggage_no, to_pay, paid, due, status, date, date_sort,
				passcode, creaded_by, locked_by, l_paid, l_to_be, l_amount, img, disable, disable2,
				updated, id2, changed, changed_user, change_time, code, remark_test, remark_html,
				username_remark, currency, routes, changes, manifest_routes, users
			) VALUES (
				'{$vehicleEsc}','{$drv}','{$con}','{$routeEsc}','0','0','0','0','open','{$dateEsc}','{$dateEsc}',
				'','{$by}','','0','0','0','{$img}','','',
				'','0','','','','{$codeEsc}','','',
				'','{$cur}','','','',''
			)"
		);
		if (!$insert) {
			return ['ok' => false, 'error' => 'Insert failed: ' . $db->error];
		}
		$db->query("UPDATE vehicles SET driver='{$drv}', conductor='{$con}' WHERE name='{$vehicleEsc}'");

		$id = (int) $db->insert_id;
		$gq = null;
		if ($id > 0) {
			$gq = $db->query(
				"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, remark_test, remark_html
				 FROM vehicle_name WHERE id={$id} LIMIT 1"
			);
		}
		if (!$gq || $gq->num_rows === 0) {
			$gq = $db->query(
				"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, remark_test, remark_html
				 FROM vehicle_name WHERE Name='{$vehicleEsc}' AND date='{$dateEsc}' AND code='{$codeEsc}'
				 ORDER BY id DESC LIMIT 1"
			);
		}
		$grow = $gq ? $gq->fetch_assoc() : null;
		if (!$grow) {
			return ['ok' => true, 'item' => ['id' => $id, 'code' => $manifestCode]];
		}
		return ['ok' => true, 'item' => tessera_cargo_manifests_row_to_item($db, $username, $grow)];
	}
}

if (!function_exists('tessera_cargo_manifests_update')) {
	/**
	 * @param array{vehicle?:string,driver?:string,conductor?:string,route?:string,date?:string} $payload
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_cargo_manifests_update(mysqli $db, string $username, int $id, array $payload): array
	{
		if ($id <= 0) {
			return ['ok' => false, 'error' => 'Invalid manifest id'];
		}
		$gq = $db->query(
			"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, remark_test, remark_html
			 FROM vehicle_name WHERE id={$id} LIMIT 1"
		);
		$existing = $gq ? $gq->fetch_assoc() : null;
		if (!$existing) {
			return ['ok' => false, 'error' => 'Manifest not found'];
		}
		if (((string) ($existing['disable2'] ?? '')) === 'disabled') {
			return ['ok' => false, 'error' => 'Manifest is closed'];
		}
		$oldRoute = (string) ($existing['Route'] ?? '');
		if (!tessera_cargo_manifests_can_edit_route($db, $username, $oldRoute)) {
			return ['ok' => false, 'error' => 'You cannot edit this manifest'];
		}

		$vehicle = preg_replace('/\s+/', '_', trim((string) ($payload['vehicle'] ?? $existing['Name']))) ?? (string) $existing['Name'];
		$driver = trim((string) ($payload['driver'] ?? $existing['Driver']));
		$conductor = trim((string) ($payload['conductor'] ?? $existing['Conductor']));
		$route = trim((string) ($payload['route'] ?? $existing['Route']));
		$dateRaw = trim((string) ($payload['date'] ?? $existing['date']));
		$ts = strtotime($dateRaw);
		if ($ts === false) {
			return ['ok' => false, 'error' => 'Invalid date'];
		}
		$date = date('Y-m-d', $ts);
		if ($route !== $oldRoute && !tessera_cargo_manifests_can_edit_route($db, $username, $route)) {
			return ['ok' => false, 'error' => 'You cannot move this manifest to that route'];
		}

		$nameEsc = $db->real_escape_string($vehicle);
		$drv = $db->real_escape_string($driver !== '' ? $driver : '-');
		$con = $db->real_escape_string($conductor !== '' ? $conductor : '-');
		$rt = $db->real_escape_string($route);
		$dt = $db->real_escape_string($date);
		$by = $db->real_escape_string($username);
		$now = $db->real_escape_string(date('Y-m-d') . ' @ ' . date('h:i A'));
		$oldName = $db->real_escape_string((string) $existing['Name']);
		$oldDate = $db->real_escape_string((string) $existing['date']);

		$changed = '';
		if ($vehicle !== (string) $existing['Name']) {
			$changed .= "Vehicle name Changed from {$existing['Name']} to {$vehicle}<br>";
		}
		if ($date !== (string) $existing['date']) {
			$changed .= "Trip date Changed from {$existing['date']} to {$date}<br>";
		}
		if ($driver !== (string) $existing['Driver']) {
			$changed .= "Driver Changed from {$existing['Driver']} to {$driver}<br>";
		}
		if ($conductor !== (string) $existing['Conductor']) {
			$changed .= "Conductor Changed from {$existing['Conductor']} to {$conductor}<br>";
		}
		if ($route !== $oldRoute) {
			$changed .= "Route Changed from {$oldRoute} to {$route}<br>";
		}
		$changedEsc = $db->real_escape_string($changed);

		$upd = $db->query(
			"UPDATE vehicle_name SET Name='{$nameEsc}', Driver='{$drv}', Conductor='{$con}',
			 Route='{$rt}', date='{$dt}', date_sort='{$dt}',
			 changed='{$changedEsc}', changed_user='{$by}', change_time='{$now}'
			 WHERE id={$id}"
		);
		if (!$upd) {
			return ['ok' => false, 'error' => 'Update failed: ' . $db->error];
		}
		$db->query("UPDATE vehicles SET driver='{$drv}', conductor='{$con}' WHERE name='{$nameEsc}'");
		/* Best-effort cargo-side trip rename; accounts cascade remains classic. */
		$db->query(
			"UPDATE manifest SET trip_date='{$dt}', bus='{$nameEsc}'
			 WHERE trip_date='{$oldDate}' AND bus='{$oldName}'"
		);

		$gq2 = $db->query(
			"SELECT id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, remark_test, remark_html
			 FROM vehicle_name WHERE id={$id} LIMIT 1"
		);
		$grow = $gq2 ? $gq2->fetch_assoc() : $existing;
		return ['ok' => true, 'item' => tessera_cargo_manifests_row_to_item($db, $username, $grow)];
	}
}
