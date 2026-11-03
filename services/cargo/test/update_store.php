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
		$sum_amount_remain_paid = 0;
		$sum_amount_remain_to_be = 0;
		$sum_amount_remain_due_answer = 0;
		$sum_amount_remain_due_paym = 0;
		
		
$select_location1=mysqli_query($db,"SELECT * From parcel_info WHERE    store='half_out' and username='$username' and status='paid' ");
$select_num_location1=mysqli_num_rows($select_location1);
if($select_num_location1 !=0){
   	WHILE($get_location1=mysqli_fetch_array($select_location1)){
		
		
			$amount_remain =$get_location1['amount_remain']; 
						$status  =$get_location1['status']; 

$sum_amount_remain_paid += $amount_remain;
}}	

$select_location1_to_be=mysqli_query($db,"SELECT * From parcel_info WHERE    store='half_out' and username='$username' and status='To be Payed' ");
$select_num_location1_to_be=mysqli_num_rows($select_location1_to_be);
if($select_num_location1_to_be !=0){
   	WHILE($get_location1_to_be=mysqli_fetch_array($select_location1_to_be)){
		
		
			$amount_remain_to_be =$get_location1_to_be['amount_remain']; 
						$status_to_be  =$get_location1_to_be['status']; 

$sum_amount_remain_to_be += $amount_remain_to_be;
}}	

$select_location1_due=mysqli_query($db,"SELECT * From parcel_info WHERE    store='half_out' and username='$username' and status='Due payment' ");
$select_num_location1_due=mysqli_num_rows($select_location1_due);
if($select_num_location1_due !=0){
   	WHILE($get_location1_due=mysqli_fetch_array($select_location1_due)){
		
		
			$amount_remain_to_be_answer =$get_location1_due['answer']; 
			$amount_remain_paid =$get_location1_due['payment_status']; 
						$status_to_be  =$get_location1_due['status']; 

$sum_amount_remain_due_answer += $amount_remain_to_be_answer;
$sum_amount_remain_due_paym += $amount_remain_paid;
}}	




echo "".number_format($sum_amount_remain_paid,2)."  to be ".number_format($sum_amount_remain_to_be,2)." to_be_due ".number_format($sum_amount_remain_due_answer,2)."paid_due ".number_format($sum_amount_remain_due_paym,2)."   $trip $status_to_be username<br>";


/*


		$insert=mysqli_query($db,"INSERT INTO store (paid,to_be,location,username) 
VALUES
 ('$sum_paid','$sum_to_be','$trip','$username')");	
	*/
}}	

	}
}




?>