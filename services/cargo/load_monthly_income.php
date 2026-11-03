				<?php
							date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));
$month =  date('m/Y', strtotime($query_date));


									$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
				echo number_format($amount,2);
			
		}}else{
			echo "0.00";
		}
							?>