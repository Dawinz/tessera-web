<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/luggage_mysqli.php';

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now = date('Y-m-d');
$username = (string) ($_SESSION['username'] ?? '');

try {
	$db = luggage_mysqli();
} catch (RuntimeException $e) {
	echo '0.00';
	exit;
}

$user = mysqli_real_escape_string($db, $username);
$day = mysqli_real_escape_string($db, $date_now);
$sql = mysqli_query($db, "SELECT SUM(amount) AS amount FROM parcel_info WHERE username='{$user}' AND date_insert='{$day}'");
if ($sql && ($data = mysqli_fetch_assoc($sql)) && $data['amount'] !== null) {
	echo number_format((float) $data['amount'], 2);
} else {
	echo '0.00';
}
