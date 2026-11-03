<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
session_start();
date_default_timezone_set('Africa/Dar_es_Salaam');

$date_insert = date('Y-m-d', time());
$date_time_insert = date('h:i A', time());
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$route_name = (string) ($_POST['route_name'] ?? '');
if ($route_name === '' || empty($_POST['group_cat']) || !is_array($_POST['group_cat'])) {
	http_response_code(400);
	echo 'invalid';
	exit;
}

$username = (string) ($_SESSION['username'] ?? '');
$any = false;

for ($count = 0; $count < count($_POST['group_cat']); $count++) {
	$group_cat = trim((string) ($_POST['group_cat'][$count] ?? ''));
	$expenses = trim((string) ($_POST['expenses'][$count] ?? ''));
	$vehicle = trim((string) ($_POST['vehicle'][$count] ?? ''));
	$date = trim((string) ($_POST['date'][$count] ?? ''));
	$detail = str_replace(',', '^', (string) ($_POST['detail'][$count] ?? ''));
	$price = preg_replace('/[^0-9.]+/', '', (string) ($_POST['price'][$count] ?? '0'));
	if ($price === '') {
		$price = '0';
	}
	$account = trim((string) ($_POST['account'][$count] ?? 'Cash'));
	$item = trim((string) ($_POST['item'][$count] ?? ''));
	$quantity = trim((string) ($_POST['quantity'][$count] ?? '0'));
	if ($quantity === '') {
		$quantity = '0';
	}
	if ($group_cat === '' || $expenses === '' || $date === '') {
		continue;
	}

	$year = date('Y', strtotime($date_insert));
	$month = date('F', strtotime($date_insert));
	$day = date('d', strtotime($date));
	$open_id = '0';
	$amount_remain = 0;
	$amount_remain_cash = '0';
	$open_date = '';

	$rnEsc = mysqli_real_escape_string($db, $route_name);
	$select = mysqli_query($db, "SELECT amount_remain,opening_stock,id,open_date FROM cash WHERE route_name='$rnEsc' ORDER BY id DESC LIMIT 1");
	if ($select && ($get = mysqli_fetch_assoc($select))) {
		$amount_remain = (int) $get['amount_remain'];
		$amount_remain_cash = (string) $get['opening_stock'];
		$open_date = (string) $get['open_date'];
		$open_id = (string) $get['id'];
	} else {
		http_response_code(400);
		echo 'no cash for route';
		exit;
	}

	$new_amout_remain = (string) ($amount_remain - (int) $price);
	$zero = '0';
	$approved = '';
	$account_from = '';
	$icon = '';
	$cash_hand = '0';
	$cash_id = '0';

	$sqlQ = 'INSERT INTO office_expenses (
		approved, cat_group, vehicle, date, detail, amount, amount_curr, username, time, insert_date_time,
		stock_amount, amount_remain, name, account, day, month, year, balance, account_from, icon,
		route_name, cash_hand, cash_date, cash_id, item, quantity, open_id
	) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$stmt->bind_param(
		'sssssssssssssssssssssssssss',
		$approved,
		$group_cat,
		$vehicle,
		$date,
		$detail,
		$price,
		$zero,
		$username,
		$date_time_insert,
		$date_insert,
		$amount_remain_cash,
		$new_amout_remain,
		$expenses,
		$account,
		$day,
		$month,
		$year,
		$zero,
		$account_from,
		$icon,
		$route_name,
		$cash_hand,
		$open_date,
		$cash_id,
		$item,
		$quantity,
		$open_id
	);
	if (!$stmt->execute()) {
		http_response_code(500);
		echo 'insert failed: ' . $stmt->error;
		exit;
	}

	$oidEsc = mysqli_real_escape_string($db, $open_id);
	$nrEsc = mysqli_real_escape_string($db, $new_amout_remain);
	mysqli_query($db, "UPDATE cash SET amount_remain='$nrEsc' WHERE id='$oidEsc'");
	$any = true;
}

echo $any ? 'success' : 'invalid';
?>
