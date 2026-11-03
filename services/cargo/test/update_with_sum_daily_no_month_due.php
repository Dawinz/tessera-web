<?php
include '../connect.php';
$trip="Tunduma to Lubumbashi";
$select1=mysqli_query($db,"SELECT *  FROM  parcel_info where status='Due payment' and account='$trip'  group by month  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['month']; 
			
			$status =$get13['status']; 
			
			$date_insert =$get13['date_insert']; 
		
      

			$select12=mysqli_query($db,"SELECT *  FROM  parcel_info where status='Due payment' and account='Dar es Salaam to Lubumbashi' and month='$date'  ");
$select_num12=mysqli_num_rows($select12);
		
		echo "$select_num12<br>";
		
        
		 $insert=mysqli_query($db,"UPDATE monthly_charges  SET  no_due ='$select_num12' WHERE  date_insert ='$date' and  location='$trip'");
		
		
	/*	$sql = $db->query("SELECT SUM(question ) AS  question  FROM parcel_info where   month='$date' and account='$trip' and status='To be Payed' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$question =$data['question'];
				echo ''.number_format($question ,2).' '.$status.' '.$date.'<br>';
				
/* $insert=mysqli_query($db,"INSERT INTO daily_charges  
(paid,location,date_insert ) VALUES 
('$To be Payed','$trip','$date')"); 


		}}else{
			echo "0.00";


		
	}
 */
}}
?>