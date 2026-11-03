<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info group by month  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['month']; 
			$trip =$get13['account']; 
			 
		$sql = $db->query("SELECT SUM(paid ) AS  paid  FROM parcel_info where   month='$date' and account='$trip' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid =$data['paid'];
				echo ''.number_format($paid ,2).'<br>';
				
	   $insert=mysqli_query($db,"UPDATE monthly_charges  SET paid='$paid' WHERE  date_insert ='$date' and  location='$trip'");

		}}else{
			echo "0.00";
		}


		
	}
}

?>