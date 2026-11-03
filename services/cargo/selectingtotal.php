	<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM parcel_info  where   store != 'out' and username='Nasir'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
	$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
				echo ''.number_format($total_amount_t ,2).'<br>';
			
		}}else{
			echo "Nothing";
		}
								?>		
								
								<?php
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest  where   uploader='Nasir'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
					$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t2 = $paid + $to_be;
				echo ''.number_format($total_amount_t2 ,2).'<br>';
			
		}}else{
			echo "Nothing";
		}
$total=$total_amount_t - $total_amount_t2;
echo ''.number_format($total,2).'';
						?>	