<?php
include '../connect.php';
$trip="Dar es salaam to Lubumbashi";

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('09-2024', time());
		$sql = $db->query("SELECT SUM(paid) AS  paid  FROM parcel_info where   month='09-2024' and account='$trip'  and open ='' ORDER BY month desc");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid =$data['paid'];
				echo ''.number_format($paid ,2).'<br>';
				


		}}

?>