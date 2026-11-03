<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$route_names = $_POST['route_name'] ?? [];
if (!is_array($route_names) || count($route_names) < 1) {
	echo 'invalid';
	exit;
}

$any = false;
for ($count = 0; $count < count($route_names); $count++) {
	$part_Category = trim((string) ($_POST['part_Category'][$count] ?? ''));
	$name = trim((string) ($_POST['name'][$count] ?? ''));
	$route_name = trim((string) ($route_names[$count] ?? ''));
	$route_category = trim((string) ($_POST['route_category'][$count] ?? ''));
	$currency = trim((string) ($_POST['currency'][$count] ?? ''));
	$deduction = trim((string) ($_POST['deduction'][$count] ?? ''));
	$vehilce_category = trim((string) ($_POST['vehilce_category'][$count] ?? ''));
	$value = preg_replace('/[^0-9.]+/', '', (string) ($_POST['value'][$count] ?? '0'));
	if ($value === '') {
		$value = '0';
	}
	if ($name === '' || $route_name === '') {
		continue;
	}

	$status = '';
	$pcEsc = mysqli_real_escape_string($db, $part_Category);
	$select_status = mysqli_query($db, "SELECT category FROM particular_category WHERE name='$pcEsc' LIMIT 1");
	if ($select_status && ($get_status = mysqli_fetch_assoc($select_status))) {
		$status = (string) $get_status['category'];
	}

	$rnEsc = mysqli_real_escape_string($db, $route_name);
	$nEsc = mysqli_real_escape_string($db, $name);
	$rcEsc = mysqli_real_escape_string($db, $route_category);
	$vEsc = mysqli_real_escape_string($db, $vehilce_category);
	$select = mysqli_query(
		$db,
		"SELECT id FROM constants WHERE route_name='$rnEsc' AND name='$nEsc' AND route_category='$rcEsc' AND name_category='$pcEsc' AND vehicle='$vEsc' LIMIT 1"
	);
	if ($select && mysqli_num_rows($select) > 0) {
		echo 'found';
		continue;
	}

	$sqlQ = 'INSERT INTO constants (name, route_name, price_value, currency, route_category, name_category, affect, status, vehicle) VALUES (?,?,?,?,?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$stmt->bind_param('sssssssss', $name, $route_name, $value, $currency, $route_category, $part_Category, $deduction, $status, $vehilce_category);
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
