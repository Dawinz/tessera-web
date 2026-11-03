<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info group by date_insert  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['date_insert']; 
			$trip =$get13['account']; 
			 
		$sql = $db->query("SELECT SUM(to_be ) AS  to_be  FROM parcel_info where   date_insert='$date' and account='$trip' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be =$data['to_be'];
				echo ''.number_format($to_be ,2).'<br>';
				
/* $insert=mysqli_query($db,"INSERT INTO daily_charges  
(paid,location,date_insert ) VALUES 
('$paid','$trip','$date')"); */
 $insert=mysqli_query($db,"UPDATE daily_charges  SET to_be='$to_be' WHERE  date_insert ='$date' and  location='$trip'");

		}}else{
			echo "0.00";
		}


		
	}
}

?>