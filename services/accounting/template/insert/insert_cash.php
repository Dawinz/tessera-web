<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
session_start();
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$route_name = trim((string) ($_POST['route_name'] ?? ''));
$insert_date_raw = trim((string) ($_POST['insert_date'] ?? ''));
$amount = preg_replace('/[^0-9.]+/', '', (string) ($_POST['amount'] ?? ''));
$Authorizer = trim((string) ($_POST['Authorizer'] ?? ''));
$username = (string) ($_SESSION['username'] ?? '');

if ($route_name === '' || $insert_date_raw === '' || $amount === '' || $Authorizer === '') {
	http_response_code(400);
	echo 'invalid';
	exit;
}

$insert_date = date('Y-m-d', strtotime($insert_date_raw));
$year = date('Y', strtotime($insert_date));
$month = date('F', strtotime($insert_date));
$day = date('d', strtotime($insert_date));
$date_insert = date('Y-m-d');
$date_time_insert = date('h:i A');

$fullname = trim((string) ($_SESSION['fullname'] ?? ''));
if ($fullname === '' && $username !== '') {
	$uEsc = mysqli_real_escape_string($db, $username);
	$sel = mysqli_query($db, "SELECT firstname, surname FROM users WHERE username='$uEsc' LIMIT 1");
	if ($sel && ($u = mysqli_fetch_assoc($sel))) {
		$fullname = trim(($u['firstname'] ?? '') . ' ' . ($u['surname'] ?? ''));
	}
}
$issuer = $fullname !== '' ? ($username . ' (' . $fullname . ')') : $username;

$amount_remain_last = 0;
$rnEsc = mysqli_real_escape_string($db, $route_name);
$select_last = mysqli_query($db, "SELECT amount_remain FROM cash WHERE route_name='$rnEsc' ORDER BY id DESC LIMIT 1");
if ($select_last && ($get_last = mysqli_fetch_assoc($select_last))) {
	$amount_remain_last = (int) $get_last['amount_remain'];
}
$new_amount_remain = (string) ($amount_remain_last + (int) $amount);
$zero = '0';
$empty = '';
$status = '';
$percentage = '0';
$color = '';
$amount_dollar = '0.00';
$account = '';
$bank = '';
$check_no = '';
$check_to = '';
$vehicle = '';
$name = '';

$sqlQ = 'INSERT INTO cash (
	opening_stock, issuer, date, time, authorizer, open_date, stock_no, amount_remain, initial_am_remain,
	status, percentage, color, amount_dollar, account, day, month, year, bank, check_no, check_to, vehicle, name, route_name
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
$stmt = $db->prepare($sqlQ);
if (!$stmt) {
	http_response_code(500);
	echo 'prepare failed: ' . $db->error;
	exit;
}
$stmt->bind_param(
	'sssssssssssssssssssssss',
	$amount,
	$issuer,
	$date_insert,
	$date_time_insert,
	$Authorizer,
	$insert_date,
	$zero,
	$new_amount_remain,
	$amount,
	$status,
	$percentage,
	$color,
	$amount_dollar,
	$account,
	$day,
	$month,
	$year,
	$bank,
	$check_no,
	$check_to,
	$vehicle,
	$name,
	$route_name
);
if (!$stmt->execute()) {
	http_response_code(500);
	echo 'insert failed: ' . $stmt->error;
	exit;
}

echo 'success';
?>
