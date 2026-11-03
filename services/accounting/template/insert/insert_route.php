<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect_cargo.php';
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$route = trim((string) ($_POST['route'] ?? ''));
$currency = trim((string) ($_POST['currency'] ?? ''));
$detail = str_replace(["'", ','], ['_', '~'], (string) ($_POST['detail'] ?? ''));
$category = trim((string) ($_POST['category'] ?? ''));
$Main_route = trim((string) ($_POST['Main_route'] ?? ''));

if ($route === '' || $category === '') {
	echo 'invalid';
	exit;
}

if ($category === 'Main Route') {
	$badge = '<span class="badge badge-primary">Main Route</span>';
	$status = 'Main';
} else {
	$badge = '<span class="badge badge-secondary">Sub Route</span>';
	$status = 'Sub route';
}

$route_name = '';
$start_route = '';
$end_route = '';
$route_full_name = $route;
$routeEsc = mysqli_real_escape_string($db_up, $route);
$select = mysqli_query($db_up, "SELECT route_name, start_route, end_route, route_full_name FROM routes WHERE route_full_name='$routeEsc' LIMIT 1");
if ($select && ($get = mysqli_fetch_assoc($select))) {
	$route_name = (string) $get['route_name'];
	$start_route = (string) $get['start_route'];
	$end_route = (string) $get['end_route'];
	$route_full_name = (string) $get['route_full_name'];
} else {
	echo 'route not found in cargo';
	exit;
}

$value_no_name = '0';
$curEsc = mysqli_real_escape_string($db, $currency);
$select_currency = mysqli_query($db, "SELECT value_no FROM currency WHERE name='$curEsc' LIMIT 1");
if ($select_currency && ($get_currency = mysqli_fetch_assoc($select_currency))) {
	$value_no_name = (string) $get_currency['value_no'];
}

$button = '<i class="typcn typcn-media-stop  "></i> De active';
$button_view = 'danger';
$status_r = 'active';
$currency_val = $currency !== '' ? $currency : 'Tsh';
$main_empty = '';

$dupEsc = mysqli_real_escape_string($db, $route);
$select_route = mysqli_query($db, "SELECT fullname FROM route WHERE fullname='$dupEsc' LIMIT 1");
if ($select_route && mysqli_num_rows($select_route) > 0) {
	echo 'found';
	exit;
}

$ok = true;
$err = '';

if ($category === 'Main Route') {
	$sqlQ = 'INSERT INTO route (start_point, end_point, fullname, name, detail, status, badge, availability, button, button_view, currency, currency_value, main_route) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$stmt->bind_param(
		'sssssssssssss',
		$start_route,
		$end_route,
		$route,
		$route_name,
		$detail,
		$status,
		$badge,
		$status_r,
		$button,
		$button_view,
		$currency_val,
		$value_no_name,
		$main_empty
	);
	$ok = $stmt->execute();
	$err = $stmt->error;

	if ($ok) {
		$sub_fullname = "$end_route to $start_route";
		$status_sub = 'Sub route';
		$badge_sec = '<span class="badge badge-secondary">Sub Route</span>';
		$sqlQ = 'INSERT INTO route (start_point, end_point, fullname, name, detail, status, badge, main_route, availability, button, button_view, currency, currency_value) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
		$stmt = $db->prepare($sqlQ);
		$stmt->bind_param(
			'sssssssssssss',
			$end_route,
			$start_route,
			$sub_fullname,
			$route_name,
			$detail,
			$status_sub,
			$badge_sec,
			$route_full_name,
			$status_r,
			$button,
			$button_view,
			$currency_val,
			$value_no_name
		);
		$ok = $stmt->execute();
		$err = $stmt->error;
	}
} else {
	$sqlQ = 'INSERT INTO route (start_point, end_point, fullname, name, detail, status, badge, main_route, availability, button, button_view, currency, currency_value) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$stmt->bind_param(
		'sssssssssssss',
		$end_route,
		$start_route,
		$route_full_name,
		$route_name,
		$detail,
		$status,
		$badge,
		$Main_route,
		$status_r,
		$button,
		$button_view,
		$currency_val,
		$value_no_name
	);
	$ok = $stmt->execute();
	$err = $stmt->error;

	if ($ok) {
		$sqlQ = 'INSERT INTO sub_route (start_point, end_point, fullname, name, detail, main_route, currency, status, currency_value) VALUES (?,?,?,?,?,?,?,?,?)';
		$stmt = $db->prepare($sqlQ);
		$sub_status = $status;
		$stmt->bind_param(
			'sssssssss',
			$start_route,
			$end_route,
			$route,
			$route_name,
			$detail,
			$Main_route,
			$currency_val,
			$sub_status,
			$value_no_name
		);
		$ok = $stmt->execute();
		$err = $stmt->error;
	}
}

if ($ok) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . $err;
}
