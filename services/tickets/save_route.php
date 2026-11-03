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
$active_day = trim((string) ($_POST['active_day'] ?? ''));
$currency = trim((string) ($_POST['currency'] ?? ''));
$fair = preg_replace('/[^0-9.]+/', '', (string) ($_POST['fair'] ?? '0'));
$initial_route_name = trim((string) ($_POST['initial'] ?? ''));
$detail = base64_encode((string) ($_POST['details'] ?? ''));
$contact_no1 = trim((string) ($_POST['contact_no1'] ?? ''));
$contact_no2 = trim((string) ($_POST['contact_no2'] ?? ''));

include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$tenant_id = (int) tessera_current_tenant_id();
if ($tenant_id < 1) {
	http_response_code(403);
	echo 'no tenant';
	exit;
}

if ($starting_point === '' || $end_point === '' || $starting_point === $end_point || $initial_route_name === '') {
	echo 'invalid';
	exit;
}

$rate = 1.0;
$initial = '';
$currencyEsc = mysqli_real_escape_string($db, $currency);
$select173 = mysqli_query($db, "SELECT rate, initial FROM currency WHERE country='$currencyEsc' LIMIT 1");
if ($select173 && ($row = mysqli_fetch_assoc($select173))) {
	$rate = (float) $row['rate'];
	$initial = (string) $row['initial'];
}
$route_currency = $rate * (float) $fair;

$active_date = preg_replace('/\D+/', '', $active_day);
if ($active_date === '') {
	$active_date = '7';
}
$active_date = (int) $active_date;

$code_plus = 0;
$select1 = mysqli_query($db, 'SELECT route_id FROM routes ORDER BY id DESC LIMIT 1');
if ($select1 && ($get13 = mysqli_fetch_assoc($select1))) {
	$code_plus = ((int) $get13['route_id']) + 1;
}

$sp = mysqli_real_escape_string($db, $starting_point);
$ep = mysqli_real_escape_string($db, $end_point);
$dup = mysqli_query($db, "SELECT id FROM routes WHERE start_route='$sp' AND end_route='$ep' AND tenant_id='$tenant_id' LIMIT 1");
if ($dup && mysqli_num_rows($dup) > 0) {
	echo 'found';
	exit;
}

$fullname = mysqli_real_escape_string($db, $starting_point . ' to ' . $end_point);
$route_type = 'Main Route';
$badge = mysqli_real_escape_string($db, '<span class="badge bg-primary">Main Route</span>');
$rn = mysqli_real_escape_string($db, $initial_route_name);
$cur = mysqli_real_escape_string($db, $currency);
$sym = mysqli_real_escape_string($db, $initial);
$det = mysqli_real_escape_string($db, $detail);
$c1 = mysqli_real_escape_string($db, $contact_no1);
$c2 = mysqli_real_escape_string($db, $contact_no2);
$fare = (float) $fair;
$fareIntl = (float) $route_currency;

$insert = mysqli_query(
	$db,
	"INSERT INTO routes (
		start_route, end_route, fare_price, fare_price_international, sub_route_id, route_name, route_id,
		arrival, departure, detail, currency, currency_symbol, active_date, contact_no1, contact_no2,
		fullname, transit, terms, route_type, badge, main_id, route_id_fullname, vip_price, id_type, tenant_id
	) VALUES (
		'$sp','$ep','$fare','$fareIntl','','$rn','$code_plus',
		'','','$det','$cur','$sym','$active_date','$c1','$c2',
		'$fullname','','','$route_type','$badge','','$fullname','0','0','$tenant_id'
	)"
);

if ($insert) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
}

$select1 = mysqli_query($db, 'SELECT route_id FROM assigned_routes GROUP BY route_id');
if ($select1 && mysqli_num_rows($select1) == 1) {
	mysqli_query($db, "UPDATE assigned_routes SET active='active', active_tab='show active', selected='true'");
}
