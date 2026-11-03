<?php
include '../connect.php';
$trip="Dar es salaam to Lubumbashi";
		$sql = $db->query("SELECT SUM(paid ) AS  paid  FROM parcel_info where   month='07-2023' and account='$trip'  ORDER BY month desc");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid =$data['paid'];
				echo ''.number_format($paid ,2).'<br>'.$newDate.'';
				




?>