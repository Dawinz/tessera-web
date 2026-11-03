<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
header('Content-Type: text/plain; charset=utf-8');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}

$starting_point = trim((string) ($_POST['starting_point'] ?? ''));
$end_point = trim((string) ($_POST['end_point'] ?? ''));
$currency = trim((string) ($_POST['currency'] ?? ''));
$fair = preg_replace('/[^0-9.]+/', '', (string) ($_POST['fair'] ?? '0'));
$initial_sub_r = trim((string) ($_POST['initial'] ?? ''));
$detail = base64_encode((string) ($_POST['details'] ?? ''));
$main_route = trim((string) ($_POST['main_route'] ?? ''));
$a_aft_raw = trim((string) ($_POST['a_aft'] ?? '0'));
$a_time = trim((string) ($_POST['a_time'] ?? ''));
$d_ptime = trim((string) ($_POST['d_ptime'] ?? ''));

include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$tenant_id = (int) tessera_current_tenant_id();

if ($starting_point === '' || $end_point === '' || $main_route === '' || $initial_sub_r === '') {
	echo 'invalid';
	exit;
}

$a_time = date('h:i A', strtotime($a_time ?: '08:00'));
$d_ptime = date('h:i A', strtotime($d_ptime ?: '06:00'));

$rate = 1.0;
$initial = '';
$currencyEsc = mysqli_real_escape_string($db, $currency);
$select173 = mysqli_query($db, "SELECT rate, initial FROM currency WHERE country='$currencyEsc' LIMIT 1");
if ($select173 && ($row = mysqli_fetch_assoc($select173))) {
	$rate = (float) $row['rate'];
	$initial = (string) $row['initial'];
}
$route_currency = $rate * (float) $fair;

$mr = mysqli_real_escape_string($db, $main_route);
$select1 = mysqli_query(
	$db,
	"SELECT route_id, start_route, end_route FROM routes
	 WHERE route_name='$mr' OR fullname='$mr'
	 LIMIT 1"
);
$route_id = null;
$start_route = $starting_point;
$end_route = $end_point;
if ($select1 && ($get13 = mysqli_fetch_assoc($select1))) {
	$route_id = (int) $get13['route_id'];
	$start_route = (string) $get13['start_route'];
	$end_route = (string) $get13['end_route'];
}
if ($route_id === null) {
	echo 'route not found';
	exit;
}

$code_plus = 0;
$select1 = mysqli_query($db, 'SELECT sub_route_id FROM sub_route ORDER BY id DESC LIMIT 1');
if ($select1 && ($get13 = mysqli_fetch_assoc($select1))) {
	$code_plus = ((int) $get13['sub_route_id']) + 1;
}

$sp = mysqli_real_escape_string($db, $starting_point);
$ep = mysqli_real_escape_string($db, $end_point);
$dup = mysqli_query($db, "SELECT id FROM sub_route WHERE start_route='$sp' AND end_route='$ep' AND main_route_no='$route_id' LIMIT 1");
if ($dup && mysqli_num_rows($dup) > 0) {
	echo 'found';
	exit;
}

$active_day = preg_replace('/\D+/', '', $a_aft_raw);
if ($active_day === '') {
	$active_day = '0';
}
$active_day = (int) $active_day;

$name = mysqli_real_escape_string($db, $initial_sub_r);
$det = mysqli_real_escape_string($db, $detail);
$rname = mysqli_real_escape_string($db, $main_route);
$cur = mysqli_real_escape_string($db, $currency);
$sym = mysqli_real_escape_string($db, $initial);
$arr = mysqli_real_escape_string($db, $a_time);
$dep = mysqli_real_escape_string($db, $d_ptime);
$fare = (float) $fair;
$fareIntl = (float) $route_currency;

$insert = mysqli_query(
	$db,
	"INSERT INTO sub_route (
		start_route, end_route, fare_price, fare_price_international, sub_route_id, detail, name,
		main_route_no, a_time, d_time, active_day, route_name, currency, currency_symbol, status, vip_price
	) VALUES (
		'$sp','$ep','$fare','$fareIntl','$code_plus','$det','$name',
		'$route_id','$arr','$dep','$active_day','$rname','$cur','$sym','active','0'
	)"
);

if (!$insert) {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
	exit;
}

echo 'succesfull';

$sr = mysqli_real_escape_string($db, $start_route);
$er = mysqli_real_escape_string($db, $end_route);
$select1 = mysqli_query($db, "SELECT id FROM sub_route_list WHERE start_route='$sr' AND end_route='$er' AND location='$ep' LIMIT 1");
if ($select1 && mysqli_num_rows($select1) == 0) {
	mysqli_query($db, "INSERT INTO sub_route_list (start_route, end_route, location) VALUES ('$sr','$er','$ep')");
}
