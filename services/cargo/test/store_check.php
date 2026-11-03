<?php
include '../connect.php';


$select12=mysqli_query($db,"SELECT * From parcel_info WHERE store='store' or   store='half_out' group by username  ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
    
		
			$username =$get132['username']; 
		
		$select_location=mysqli_query($db,"SELECT * From parcel_info WHERE username='$username' group by account  ");
$select_num_location=mysqli_num_rows($select_location);
if($select_num_location !=0){
   	WHILE($get_location=mysqli_fetch_array($select_location)){
			$trip =$get_location['account']; 
			
			

		$sum_paid = 0;
		$sum_to_be = 0;
$select_location1=mysqli_query($db,"SELECT * From parcel_info WHERE    store='store' and username='$username'  ");
$select_num_location1=mysqli_num_rows($select_location1);
if($select_num_location1 !=0){
   	WHILE($get_location1=mysqli_fetch_array($select_location1)){
		
			$paid  =$get_location1['paid']; 
			$code  =$get_location1['code']; 
			$to_be  =$get_location1['to_be']; 
			$status  =$get_location1['status']; 
			$amount_remain =$get_location1['amount_remain']; 
			$total_paid = $paid - $amount_remain;
			$total_to_be = $to_be - $amount_remain;
$sum_to_be += $to_be;
		$sum_paid += $paid;
	


	
	
			echo "$username $trip $total_paid or $total_to_be - $amount_remain =  <br>";

}}	
/*
		$insert=mysqli_query($db,"INSERT INTO store (paid,to_be,location,username) 
VALUES
 ('$sum_paid','$sum_to_be','$trip','$username')");	
	*/
}}	

	}
}




?>