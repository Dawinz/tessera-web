<?php
include '../connect.php';
$trip="Tunduma to Lubumbashi";
$select1=mysqli_query($db,"SELECT *  FROM  parcel_info where status='To be Payed' and account='$trip' group by date_insert  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['date_insert']; 
			
			$status =$get13['status']; 
			
			
			$select12=mysqli_query($db,"SELECT *  FROM  parcel_info where status='To be Payed' and account='$trip' and date_insert='$date'  ");
$select_num12=mysqli_num_rows($select12);
		
		echo "$select_num12<br>";
		
		 $insert=mysqli_query($db,"UPDATE daily_charges  SET  no_to_be ='$select_num12' WHERE  date_insert ='$date' and  location='$trip'");
		
		
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