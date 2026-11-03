<?php
$route = isset($_GET['route']) ? base64_decode($_GET['route']) : '';
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$month = date('m-Y');
$sum_paid = 0;
$sum_to_be = 0;
$total = 0;

$select1 = mysqli_query($db, "SELECT * FROM monthly_charges WHERE date_insert = '$month' AND location='$route'");
if ($select1 && mysqli_num_rows($select1) !== 0) {
	while ($get13 = mysqli_fetch_array($select1)) {
		$sum_paid += (float) $get13['paid'];
		$sum_to_be += (float) $get13['to_be'];
	}
	$total = $sum_paid + $sum_to_be;
}

echo number_format($total, 2) . ' <span class="sr-kpi-currency">TZS</span>';
