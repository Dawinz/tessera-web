<?php
include '../connect.php';
$trip="Tunduma to Lubumbashi";
$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE account='$trip' group by date_insert  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['month'];
             $date_insert =$get13['date_insert']; 
		
            $newDate = date("Ym", strtotime($date_insert));  
		$sql = $db->query("SELECT SUM(paid ) AS  paid  FROM parcel_info where   date_insert='$date_insert' and account='$trip'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid =$data['paid'];
				echo ''.number_format($paid ,2).'<br>'.$newDate.'';
				
	  $insert=mysqli_query($db,"INSERT INTO daily_charges 
(paid,location,date_insert,sort ) VALUES 
('$paid','$trip','$date_insert','$newDate')"); 

/*
	  $insert=mysqli_query($db,"UPDATE monthly_charges  SET paid='$paid' WHERE  date_insert ='$date' and  location='$trip'"); 
*/
		}}else{
			echo "0.00";
		}


		
	}
}

?>