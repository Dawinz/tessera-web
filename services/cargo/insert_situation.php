<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$d_time =  $_POST['d_time'];
$strDate =  $_POST['strDate'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('h:i A', time());

		$query = "SELECT * FROM locations ";
	$result = $db->query($query);
	if ($result->num_rows > 0 ) {
			
		 while ($row = $result->fetch_assoc()) {
$location_id = $row['name'];
   $insert=mysqli_query($db,"INSERT INTO situation_report VALUES('','$location_id','no_p','','no','$d_time','','$strDate','','$date_insert')");

		 }
		 echo "$date_insert";
	}else{

		echo '<option>Destination not found</option>';
	}

	?>

