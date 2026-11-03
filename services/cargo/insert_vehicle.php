<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
header('Content-Type: text/plain; charset=utf-8');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}

include 'connect.php';

$vehicle = preg_replace('/\s+/', '_', trim((string) ($_POST['vehicle'] ?? '')));
$driver = trim((string) ($_POST['driver'] ?? ''));
$conductor = trim((string) ($_POST['conductor'] ?? ''));
$timeRaw = trim((string) ($_POST['time'] ?? ''));
$route = trim((string) ($_POST['route'] ?? ''));

if ($vehicle === '' || $route === '' || $timeRaw === '') {
	echo 'invalid';
	exit;
}

$time = date('Y-m-d', strtotime($timeRaw));
if ($time === false || $time === '1970-01-01') {
	echo 'invalid date';
	exit;
}

$routeEsc = mysqli_real_escape_string($db, $route);
$initial = '';
$currency_symbol = 'TZS';
$select_route_code = mysqli_query($db, "SELECT initial, currency_symbol FROM routes WHERE route_full_name='$routeEsc' LIMIT 1");
if ($select_route_code && ($get_route_code = mysqli_fetch_assoc($select_route_code))) {
	$initial = (string) $get_route_code['initial'];
	$currency_symbol = (string) ($get_route_code['currency_symbol'] ?: 'TZS');
}

$code = 1;
$select_code = mysqli_query($db, "SELECT code FROM manifest_code WHERE route_name='$routeEsc' LIMIT 1");
if (!$select_code || mysqli_num_rows($select_code) === 0) {
	mysqli_query($db, "INSERT INTO manifest_code VALUES ('','1','$routeEsc')");
} else {
	$row = mysqli_fetch_assoc($select_code);
	$code = ((int) $row['code']) + 1;
	mysqli_query($db, "UPDATE manifest_code SET code='$code' WHERE route_name='$routeEsc'");
}
$manifest_code = sprintf('%04d', $code) . $initial;

$vehicleEsc = mysqli_real_escape_string($db, $vehicle);
$timeEsc = mysqli_real_escape_string($db, $time);
$dup = mysqli_query($db, "SELECT id FROM vehicle_name WHERE Name='$vehicleEsc' AND date='$timeEsc' LIMIT 1");
if ($dup && mysqli_num_rows($dup) > 0) {
	echo 'found';
	exit;
}

$button = base64_encode('<strong><svg style="color:green" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326z"/></svg></strong>');

// STRICT SQL: vehicle_name has many NOT NULL columns with no defaults.
$name = $vehicleEsc;
$drv = mysqli_real_escape_string($db, $driver !== '' ? $driver : '-');
$con = mysqli_real_escape_string($db, $conductor !== '' ? $conductor : '-');
$rt = $routeEsc;
$st = 'open';
$dt = $timeEsc;
$by = mysqli_real_escape_string($db, (string) $_SESSION['username']);
$img = mysqli_real_escape_string($db, $button);
$codeEsc = mysqli_real_escape_string($db, $manifest_code);
$cur = mysqli_real_escape_string($db, $currency_symbol);

$insert = mysqli_query(
	$db,
	"INSERT INTO vehicle_name (
		Name, Driver, Conductor, Route, luggage_no, to_pay, paid, due, status, date, date_sort,
		passcode, creaded_by, locked_by, l_paid, l_to_be, l_amount, img, disable, disable2,
		updated, id2, changed, changed_user, change_time, code, remark_test, remark_html,
		username_remark, currency, routes, changes, manifest_routes, users
	) VALUES (
		'$name','$drv','$con','$rt','0','0','0','0','$st','$dt','$dt',
		'','$by','','0','0','0','$img','','',
		'','0','','','','$codeEsc','','',
		'','$cur','','','',''
	)"
);

if (!$insert) {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
	exit;
}

mysqli_query($db, "UPDATE vehicles SET driver='$drv', conductor='$con' WHERE name='$name'");
echo 'succesfull';
