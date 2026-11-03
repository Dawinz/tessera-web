<?php
$route = isset($_GET['route']) ? base64_decode($_GET['route']) : '';
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$month = date('m-Y');
$sum_no_to_be = 0;
$sum_no_paid = 0;
$sum_no_due = 0;
$total_number = 0;

$select1 = mysqli_query($db, "SELECT * FROM monthly_charges WHERE date_insert = '$month' AND location='$route'");
if ($select1 && mysqli_num_rows($select1) !== 0) {
	while ($get13 = mysqli_fetch_array($select1)) {
		$sum_no_to_be += (int) $get13['no_to_be'];
		$sum_no_paid += (int) $get13['no_paid'];
		$sum_no_due += (int) $get13['no_due'];
	}
	$total_number = $sum_no_to_be + $sum_no_paid + $sum_no_due;
}

echo number_format($total_number);
