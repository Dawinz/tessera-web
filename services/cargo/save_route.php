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
$currency = trim((string) ($_POST['currency'] ?? ''));
$contact_1 = trim((string) ($_POST['contact_1'] ?? ''));
$contact_2 = trim((string) ($_POST['contact_2'] ?? ''));
$address_1 = trim((string) ($_POST['address_1'] ?? ''));
$address_2 = trim((string) ($_POST['address_2'] ?? ''));
$terms_1 = base64_encode((string) ($_POST['terms_1'] ?? ''));
$terms_2 = base64_encode((string) ($_POST['terms_2'] ?? ''));
$initial_route_name = base64_encode(trim((string) ($_POST['initial'] ?? '')));
$r_initial = trim((string) ($_POST['r_initial'] ?? ''));
$to_be_paid_currency = trim((string) ($_POST['to_be_paid_currency'] ?? ''));

include 'connect.php';

if ($starting_point === '' || $end_point === '' || $starting_point === $end_point || $r_initial === '') {
	echo 'invalid';
	exit;
}

$rInitEsc = mysqli_real_escape_string($db, $r_initial);
$select15 = mysqli_query($db, "SELECT id FROM routes WHERE initial='$rInitEsc' LIMIT 1");
if ($select15 && mysqli_num_rows($select15) > 0) {
	echo 'change';
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

$code_plus = 0;
$select1 = mysqli_query($db, 'SELECT route_id FROM routes ORDER BY id DESC LIMIT 1');
if ($select1 && ($get13 = mysqli_fetch_assoc($select1))) {
	$code_plus = ((int) $get13['route_id']) + 1;
}

$sp = mysqli_real_escape_string($db, $starting_point);
$ep = mysqli_real_escape_string($db, $end_point);
$dup = mysqli_query($db, "SELECT id FROM routes WHERE start_route='$sp' AND end_route='$ep' LIMIT 1");
if ($dup && mysqli_num_rows($dup) > 0) {
	echo 'found';
	exit;
}

$full = mysqli_real_escape_string($db, $starting_point . ' to ' . $end_point);
$rn = mysqli_real_escape_string($db, $initial_route_name);
$cur = mysqli_real_escape_string($db, $currency);
$sym = mysqli_real_escape_string($db, $initial);
$c1 = mysqli_real_escape_string($db, $contact_1);
$c2 = mysqli_real_escape_string($db, $contact_2);
$a1 = mysqli_real_escape_string($db, $address_1);
$a2 = mysqli_real_escape_string($db, $address_2);
$t1 = mysqli_real_escape_string($db, $terms_1);
$t2 = mysqli_real_escape_string($db, $terms_2);
$tb = mysqli_real_escape_string($db, $to_be_paid_currency);

$insert = mysqli_query(
	$db,
	"INSERT INTO routes (
		start_route, end_route, route_id, route_name, currency, currency_symbol, route_full_name, initial,
		contact_1, contact_2, address_1, address_2, term_1_new1, term_2_new, to_be_paid_currency
	) VALUES (
		'$sp','$ep','$code_plus','$rn','$cur','$sym','$full','$rInitEsc',
		'$c1','$c2','$a1','$a2','$t1','$t2','$tb'
	)"
);

if ($insert) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
}
