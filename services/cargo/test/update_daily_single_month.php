<?php
include '../connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);


        
$select_users=mysqli_query($db,"SELECT *  FROM  daily_charges ");
$select_num_users=mysqli_num_rows($select_users);
if($select_num_users !=0){
   	WHILE($get_users=mysqli_fetch_array($select_users)){
    	     
			$username =$get_users['username'];
			$location =$get_users['location'];
			$date_insert =$get_users['date_insert'];


 

		$sql = $db->query("SELECT SUM(paid ) AS  paid,SUM(to_be ) AS  to_be  FROM parcel_info where   date_insert='$date_insert' and account='$location' and username='$username' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid =$data['paid'];
				$to_be =$data['to_be'];
				
				echo ''.number_format($paid ,2).'<br>';
				



 
 

		}}
		  $insert=mysqli_query($db,"UPDATE daily_charges  SET paid='$paid',to_be='$to_be' WHERE  date_insert ='$date_insert' and  location='$location' and username='$username'"); 
	
		
		
}}
			 
			 
			 
		
  

/*
	  $insert=mysqli_query($db,"UPDATE monthly_charges  SET paid='$paid' WHERE  date_insert ='$date' and  location='$trip'"); 

		}}else{
			echo "0.00";
		}

*/
		


?>