<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
header('Content-Type: text/plain; charset=utf-8');

include 'connect.php';

$currecy_name = trim((string) ($_POST['currecy_name'] ?? ''));
$Country = trim((string) ($_POST['Country'] ?? ''));
$fair = preg_replace('/[^0-9.]+/', '', (string) ($_POST['fair'] ?? '0'));
$initial = trim((string) ($_POST['initial'] ?? ''));
$currency_status = trim((string) ($_POST['currency_status'] ?? ''));

if ($fair === '' || (float) $fair == 0) {
	$fair = '1';
}

if ($currency_status === 'Main') {
	echo 'not found';
	exit;
}

if ($currecy_name === '' || $Country === '' || $initial === '') {
	echo 'invalid';
	exit;
}

$name = mysqli_real_escape_string($db, $currecy_name);
$status = mysqli_real_escape_string($db, $currency_status);
$init = mysqli_real_escape_string($db, $initial);
$country = mysqli_real_escape_string($db, '(' . $initial . ')' . $Country);
$rate = (float) $fair;

$insert = mysqli_query(
	$db,
	"INSERT INTO currency (name, status, rate, initial, country) VALUES ('$name','$status','$rate','$init','$country')"
);

if ($insert) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
}
