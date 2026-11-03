<?php
/**
 * Shared bootstrap for Schedule subpages (calendar, book, manage).
 */
require_once __DIR__ . '/tessera_staff_boot.php';
require_once __DIR__ . '/sr_env.php';
require_once __DIR__ . '/tessera_tenant.php';

date_default_timezone_set('Africa/Dar_es_Salaam');

include dirname(__DIR__) . '/connect.php';
include __DIR__ . '/../include_location_names.php';

$username = $_SESSION['username'] ?? '';
$userRole = '';
$isScheduleAdmin = false;
$roleQ = mysqli_query($db, "SELECT role FROM users WHERE username='" . mysqli_real_escape_string($db, $username) . "' LIMIT 1");
if ($roleQ && ($rr = mysqli_fetch_assoc($roleQ))) {
	$userRole = (string) ($rr['role'] ?? '');
	$isScheduleAdmin = in_array($userRole, ['Super Administrator', 'Administrator'], true);
}

$route_name = '';
$vehicle = '';
if ($isScheduleAdmin) {
	$connect = sr_mysqli('ticket');
	$q = mysqli_query($connect, "SELECT fullname,id FROM routes WHERE " . tessera_sql_tenant_eq() . " AND route_type='Main Route'");
	while ($q && ($row = mysqli_fetch_array($q))) {
		$route_name .= '<option value="' . $row['id'] . '">' . $row['fullname'] . '</option>';
	}
	$vq = mysqli_query($connect, "SELECT name FROM vehicle WHERE " . tessera_sql_tenant_eq());
	while ($vq && ($row = mysqli_fetch_array($vq))) {
		$vehicle .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
	}
}

$country = '';
$tomorrowmo = date('m/d/Y', strtotime('+1 day'));
if (isset($_GET['date'])) {
	$tomorrowmo = date('m/d/Y', strtotime((string) $_GET['date']));
}
