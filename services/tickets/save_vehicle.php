<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
header('Content-Type: text/plain; charset=utf-8');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}

require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
require_once __DIR__ . '/includes/vehicle_seat_layout.php';

$vehicle_name = trim((string) ($_POST['vehicle_name'] ?? ''));
$driver_name = trim((string) ($_POST['driver_name'] ?? ''));
$conductor_name = trim((string) ($_POST['conductor_name'] ?? ''));
$seat_no = (int) ($_POST['seat_no'] ?? 0);
$seat_layout = trim((string) ($_POST['seat_layout'] ?? ''));
$detail_text = trim((string) ($_POST['detail'] ?? ''));
$check = (string) ($_POST['check'] ?? '');

if ($vehicle_name === '' || $driver_name === '' || $conductor_name === '' || $seat_no < 1) {
	echo 'invalid';
	exit;
}

if ($seat_layout !== '' && $detail_text !== '') {
	$detail_text = $seat_layout . ' - ' . $detail_text;
} elseif ($seat_layout !== '') {
	$detail_text = $seat_layout;
}

$detail = base64_encode($detail_text);
$status = ($check === 'on') ? 'active' : 'off';
$tenant_id = (int) tessera_current_tenant_id();
if ($tenant_id < 1) {
	http_response_code(403);
	echo 'no tenant';
	exit;
}

$stmt = $db->prepare('SELECT id FROM vehicle WHERE name = ? AND tenant_id = ? LIMIT 1');
if (!$stmt) {
	echo 'error';
	exit;
}
$stmt->bind_param('si', $vehicle_name, $tenant_id);
$stmt->execute();
$exists = $stmt->get_result();
if ($exists && $exists->num_rows > 0) {
	$stmt->close();
	echo 'found';
	exit;
}
$stmt->close();

/*
 * vehicle has several NOT NULL columns without DB defaults.
 * STRICT_TRANS_TABLES rejects partial INSERTs - supply safe empties used by legacy rows.
 */
$location = '';
$route_id = '';
$state = trim((string) ($_POST['state'] ?? 'normal'));
if (!in_array($state, ['normal', 'vip'], true)) {
	$state = 'normal';
}
$dedicate_seat_price = trim((string) ($_POST['dedicate_seat_price'] ?? ''));
$seat_layout_json = null;
if (tessera_vehicle_has_layout_column($db)) {
	$patterns = tessera_default_seat_patterns($seat_no);
	$extras = in_array($seat_no, [45, 59], true) ? ['xt1', 'xt2'] : [];
	if ($seat_layout !== '') {
		$clean = preg_replace('/[^i\-]/', '', $seat_layout);
		if ($clean !== '') {
			$patterns = [$clean];
		}
	}
	$seat_layout_json = tessera_vehicle_layout_to_json($patterns, $extras);
}

$has_tenant = false;
$col = $db->query("SHOW COLUMNS FROM vehicle LIKE 'tenant_id'");
if ($col && $col->num_rows > 0) {
	$has_tenant = true;
}

$has_layout_col = tessera_vehicle_has_layout_column($db);

if ($has_tenant && $has_layout_col) {
	$sql = 'INSERT INTO vehicle (name, location, driver, conductor, seats, route_id, detail, seat_layout_json, status, state, dedicate_seat_price, tenant_id)
	        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
	$ins = $db->prepare($sql);
	if (!$ins) {
		echo 'error';
		exit;
	}
	$ins->bind_param(
		'ssssissssssi',
		$vehicle_name,
		$location,
		$driver_name,
		$conductor_name,
		$seat_no,
		$route_id,
		$detail,
		$seat_layout_json,
		$status,
		$state,
		$dedicate_seat_price,
		$tenant_id
	);
} elseif ($has_tenant) {
	$sql = 'INSERT INTO vehicle (name, location, driver, conductor, seats, route_id, detail, status, state, dedicate_seat_price, tenant_id)
	        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
	$ins = $db->prepare($sql);
	if (!$ins) {
		echo 'error';
		exit;
	}
	$ins->bind_param(
		'ssssisssssi',
		$vehicle_name,
		$location,
		$driver_name,
		$conductor_name,
		$seat_no,
		$route_id,
		$detail,
		$status,
		$state,
		$dedicate_seat_price,
		$tenant_id
	);
} else {
	$sql = 'INSERT INTO vehicle (name, location, driver, conductor, seats, route_id, detail, status, state, dedicate_seat_price)
	        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
	$ins = $db->prepare($sql);
	if (!$ins) {
		echo 'error';
		exit;
	}
	$ins->bind_param(
		'ssssisssss',
		$vehicle_name,
		$location,
		$driver_name,
		$conductor_name,
		$seat_no,
		$route_id,
		$detail,
		$status,
		$state,
		$dedicate_seat_price
	);
}

if (!$ins->execute()) {
	$ins->close();
	echo 'error';
	exit;
}
$ins->close();

echo 'succesfull';
