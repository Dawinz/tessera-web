<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';

$code = isset($_POST['code']) ? (string)$_POST['code'] : '';
if ($code === '') {
	echo 'missing code';
	exit;
}

$codeEsc = mysqli_real_escape_string($db, $code);
$select = mysqli_query($db, "SELECT id FROM parcel_info WHERE code ='$codeEsc' LIMIT 1");
$select_num = $select ? mysqli_num_rows($select) : 0;

if ($select_num != 0) {
	exit;
}

$p = static function (string $key, $default = '') {
	return isset($_POST[$key]) ? (string)$_POST[$key] : (string)$default;
};

// STRICT SQL: several NOT NULL columns are omitted by the legacy form - supply safe defaults.
$date = mysqli_real_escape_string($db, $p('date'));
$quantity = mysqli_real_escape_string($db, $p('quantity', '1'));
$trip = mysqli_real_escape_string($db, $p('trip'));
$s_no = mysqli_real_escape_string($db, $p('s_no'));
$s_name = mysqli_real_escape_string($db, $p('s_name'));
$s_surname = mysqli_real_escape_string($db, $p('s_surname', '0'));
$r_name = mysqli_real_escape_string($db, $p('r_name'));
$r_no = mysqli_real_escape_string($db, $p('r_no'));
$cost = mysqli_real_escape_string($db, $p('cost', '0'));
$due = mysqli_real_escape_string($db, $p('due', '0'));
$date_insert = mysqli_real_escape_string($db, $p('date_insert'));
$detail = mysqli_real_escape_string($db, $p('detail'));
$status = mysqli_real_escape_string($db, $p('status', 'Payed'));
$time = mysqli_real_escape_string($db, $p('time'));
$uploader = mysqli_real_escape_string($db, $p('uploader'));
$username = mysqli_real_escape_string($db, $p('username'));
$bus = mysqli_real_escape_string($db, $p('bus'));
$trip_date = mysqli_real_escape_string($db, $p('trip_date'));
$paid = mysqli_real_escape_string($db, $p('paid', '0'));
$to_be = mysqli_real_escape_string($db, $p('to_be', '0'));
$month = mysqli_real_escape_string($db, $p('month'));
$payment_status = mysqli_real_escape_string($db, $p('payment_status', '0'));
$rec_status = mysqli_real_escape_string($db, $p('rec_status', ''));
$answer = mysqli_real_escape_string($db, $p('answer', '0'));
$store = mysqli_real_escape_string($db, $p('store', 'store'));
$edited = mysqli_real_escape_string($db, $p('edited', 'no'));
$scanned = mysqli_real_escape_string($db, $p('scanned', '0'));
$amount_remain = mysqli_real_escape_string($db, $p('amount_remain', '0'));
$open = mysqli_real_escape_string($db, $p('open', 'open'));
$lugg_value = mysqli_real_escape_string($db, $p('lugg_value', '0'));
$paid_remain = mysqli_real_escape_string($db, $p('paid_remain', $paid));
$to_be_remain = mysqli_real_escape_string($db, $p('to_be_remain', $to_be));
$quantity_remain = mysqli_real_escape_string($db, $p('quantity_remain', $quantity !== '' ? $quantity : '1'));
$printed_time = mysqli_real_escape_string($db, $p('printed_time', '0'));

$insert = mysqli_query(
	$db,
	"INSERT INTO parcel_info (
		date, question, account, s_no, s_name, s_surname, r_name, r_no, amount, due, date_insert, detail, code,
		status, time, uploader, username, bus, trip_date, paid, to_be, month, payment_status, rec_status, answer,
		store, edited, scanned, amount_remain, open, lugg_value, paid_remain, to_be_remain, quantity_remain, printed_time
	) VALUES (
		'$date','$quantity','$trip','$s_no','$s_name','$s_surname','$r_name','$r_no','$cost','$due','$date_insert','$detail','$codeEsc',
		'$status','$time','$uploader','$username','$bus','$trip_date','$paid','$to_be','$month','$payment_status','$rec_status','$answer',
		'$store','$edited','$scanned','$amount_remain','$open','$lugg_value','$paid_remain','$to_be_remain','$quantity_remain','$printed_time'
	)"
);

if ($insert) {
	echo $p('detail');
} else {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
}
