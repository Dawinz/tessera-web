<?php
include '../connect.php';
$trip="Dar es salaam to Lubumbashi";
		$sql = $db->query("SELECT SUM(paid) AS  paid,SUM(to_be ) AS  to_be   FROM parcel_info where  store='store'  and  account='$trip'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid =$data['paid'];
				$to_be =$data['to_be'];
				echo ''.number_format($paid ,2).'<br>';
				


		}}		
		$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain  FROM parcel_info where status='paid'  and store='half_out' and  account='$trip'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_remain =$data['amount_remain'];
				echo ''.number_format($amount_remain ,2).'<br>';
				


		}}
		$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain  FROM parcel_info where status='To be Payed'  and store='half_out' and  account='$trip'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_remain_to_be =$data['amount_remain'];
				echo ''.$amount_remain_to_be .'<br>';
				


		}}
		
	echo "
	paid store $paid - $amount_remain <br>
	to_be stoer $to_be - $amount_remain_to_be
	";

?>