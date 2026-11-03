<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$route_name = trim((string) ($_POST['currency_r'] ?? ''));
$r_status = trim((string) ($_POST['r_status'] ?? 'Main'));
$routes = $_POST['route_name'] ?? [];
if (!is_array($routes) || $route_name === '' || count($routes) < 1) {
	echo 'invalid';
	exit;
}

$any = false;
foreach ($routes as $currency_name) {
	$currency_name = trim((string) $currency_name);
	if ($currency_name === '') {
		continue;
	}
	$cEsc = mysqli_real_escape_string($db, $currency_name);
	$rEsc = mysqli_real_escape_string($db, $route_name);
	$select = mysqli_query($db, "SELECT id FROM route_currency WHERE Currency='$cEsc' AND route_name='$rEsc' LIMIT 1");
	if ($select && mysqli_num_rows($select) > 0) {
		echo 'found';
		continue;
	}

	$select_currency = mysqli_query($db, "SELECT Currency FROM route_currency WHERE route_name='$rEsc' LIMIT 1");
	if ($select_currency && ($get_currency = mysqli_fetch_assoc($select_currency))) {
		$Currency = (string) $get_currency['Currency'];
		$new_currncy = $Currency . ',' . $currency_name;
		mysqli_query($db, "UPDATE route SET currency='" . mysqli_real_escape_string($db, $new_currncy) . "' WHERE fullname='$rEsc'");
	} else {
		mysqli_query($db, "UPDATE route SET currency='$cEsc' WHERE fullname='$rEsc'");
	}

	$value_currency = '0';
	$sqlQ = 'INSERT INTO route_currency (Currency, route_name, value_currency, status) VALUES (?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$stmt->bind_param('ssss', $currency_name, $route_name, $value_currency, $r_status);
	if (!$stmt->execute()) {
		http_response_code(500);
		echo 'insert failed: ' . $stmt->error;
		exit;
	}
	$any = true;
}

if ($any) {
	echo 'succesfull';
}
