					<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain  FROM parcel_info   where status='Due payment' and  trip_date >= ' $date1' and trip_date <= ' $date2'  and bus='$date3'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$due_status=$data['amount_remain'];
			echo $due_status;
		}}else{
			echo "Nothing";
		}
								?>