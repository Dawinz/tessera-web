<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
header('Content-Type: text/plain; charset=utf-8');

include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$tenant_id = (int) tessera_current_tenant_id();
if ($tenant_id < 1) {
	http_response_code(403);
	echo 'no tenant';
	exit;
}

$route_name = trim((string) ($_POST['route_name'] ?? ''));
$day_of_week = trim((string) ($_POST['day_of_week'] ?? ''));
$vehicle_name = trim((string) ($_POST['vehicle_name'] ?? ''));
$d_ptime = trim((string) ($_POST['d_ptime'] ?? ''));
$a_time = trim((string) ($_POST['a_time'] ?? ''));
$detail = base64_encode((string) ($_POST['detail'] ?? ''));
$check = (string) ($_POST['check'] ?? '');

if ($route_name === '' || $day_of_week === '' || $vehicle_name === '' || $d_ptime === '' || $a_time === '') {
	echo 'invalid';
	exit;
}

$a_time = date('h:i A', strtotime($a_time));
$d_ptime = date('h:i A', strtotime($d_ptime));
$report_time = date('H:i A', strtotime('-30 minutes', strtotime($d_ptime)));
$route_active_time = date('H:i A', strtotime('+300 minutes', strtotime($d_ptime)));

$rn = mysqli_real_escape_string($db, $route_name);
$select1 = mysqli_query(
	$db,
	"SELECT route_id, fullname, route_name FROM routes
	 WHERE tenant_id='$tenant_id'
	   AND (fullname='$rn' OR route_name='$rn' OR CONCAT(start_route,' to ',end_route)='$rn')
	 LIMIT 1"
);
$route_id = null;
$fullname = $route_name;
if ($select1 && ($get13 = mysqli_fetch_assoc($select1))) {
	$route_id = (int) $get13['route_id'];
	$fullname = (string) ($get13['fullname'] ?: $get13['route_name'] ?: $route_name);
}
if ($route_id === null) {
	echo 'route not found';
	exit;
}

$bus = mysqli_real_escape_string($db, $vehicle_name);
$day = mysqli_real_escape_string($db, $day_of_week);
$dup = mysqli_query(
	$db,
	"SELECT id FROM route_day
	 WHERE bus='$bus' AND day='$day' AND route_id='$route_id' AND tenant_id='$tenant_id'
	 LIMIT 1"
);
if ($dup && mysqli_num_rows($dup) > 0) {
	echo 'found';
	exit;
}

$status = ($check === 'on') ? 'active' : 'off';
$button_name = ($status === 'active') ? 'De activate' : 'Activate';
$det = mysqli_real_escape_string($db, $detail);
$fn = mysqli_real_escape_string($db, $fullname);
$rname = mysqli_real_escape_string($db, $route_name);
$arr = mysqli_real_escape_string($db, $a_time);
$dep = mysqli_real_escape_string($db, $d_ptime);
$rep = mysqli_real_escape_string($db, $report_time);
$rat = mysqli_real_escape_string($db, $route_active_time);

$insert = mysqli_query(
	$db,
	"INSERT INTO route_day (
		route_id, day, `24day`, `12hours`, mints, bus, arrival, departure, detail, status,
		reporting_time, route_name, route_active_time, button_name, button_icon, button_class,
		position, fullname, tenant_id
	) VALUES (
		'$route_id','$day','','$arr','0','$bus','$arr','$dep','$det','$status',
		'$rep','$rname','$rat','$button_name','uil uil-exclamation-octagon me-2','btn btn-soft-danger',
		'0','$fn','$tenant_id'
	)"
);

if ($insert) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
}
