<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$username = (string) ($_SESSION['username'] ?? '');
$id = trim((string) ($_POST['id'] ?? ''));
$bus = trim((string) ($_POST['bus'] ?? ''));
$trip_date = trim((string) ($_POST['trip_date'] ?? ''));
$route_name = trim((string) ($_POST['route_name'] ?? ''));

if ($id === '' || $bus === '' || $trip_date === '') {
	http_response_code(400);
	echo 'invalid';
	exit;
}

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d');
$date_time_insert = date('h:i A');

$idEsc = mysqli_real_escape_string($db, $id);
$select = mysqli_query($db, "SELECT bus, trip_date, route_name, analysed FROM manifest WHERE id = '$idEsc' LIMIT 1");
if (!$select || mysqli_num_rows($select) === 0) {
	http_response_code(404);
	echo 'not found';
	exit;
}

$get = mysqli_fetch_assoc($select);
$analysed = (string) ($get['analysed'] ?? '');
$bus = (string) ($get['bus'] ?? $bus);
$trip_date = (string) ($get['trip_date'] ?? $trip_date);
$route_name = (string) ($get['route_name'] ?? $route_name);

if ($analysed !== '') {
	echo 'analysed';
	exit;
}

$busEsc = mysqli_real_escape_string($db, $bus);
$tripEsc = mysqli_real_escape_string($db, $trip_date);
$routeEsc = mysqli_real_escape_string($db, $route_name);

mysqli_query($db, "DELETE FROM manifest WHERE id = '$idEsc'");
mysqli_query($db, "DELETE FROM vehicle_account WHERE trip_date = '$tripEsc' AND bus='$busEsc' AND route_name='$routeEsc'");

$fullname = $username;
$uEsc = mysqli_real_escape_string($db, $username);
$select2 = mysqli_query($db, "SELECT firstname, surname FROM users WHERE username='$uEsc' LIMIT 1");
if ($select2 && ($u = mysqli_fetch_assoc($select2))) {
	$fullname = trim(($u['firstname'] ?? '') . ' ' . ($u['surname'] ?? ''));
	if ($fullname === '') {
		$fullname = $username;
	}
}

$category = 'Manifest';
$status = 'Delete';
$sqlQ = 'INSERT INTO history (category, status, username, fullname, date, time) VALUES (?,?,?,?,?,?)';
$stmt = $db->prepare($sqlQ);
if ($stmt) {
	$stmt->bind_param('ssssss', $category, $status, $username, $fullname, $date_insert, $date_time_insert);
	$stmt->execute();
}

echo 'success';
?>
