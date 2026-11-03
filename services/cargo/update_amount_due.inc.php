<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
	$due_total= $amount-$due_amount2;
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' and username='$user_charge'  ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
$new_to_paid = $to_be - $to_be_org;
$new_paid = $paid - $paid_org;
$new_to_paid_total = $new_to_paid + $due_total;
$new_paid_total = $new_paid + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid_total',paid='$new_paid_total' WHERE location='$trip' and username='$user_charge' ");


	}
}	
$select135=mysqli_query($db,"SELECT to_be,paid   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' and username='$user_charge' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	 
					$to_be2 =$get1335['to_be']; 
					$paid2 =$get1335['paid']; 
$new_to_paid2 = $to_be2 - $to_be_org;
$new_paid2 = $paid2 - $paid_org;
$new_to_paid_total2 = $new_to_paid2 + $due_total;
$new_paid_total2 = $new_paid2 + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid_total2',paid='$new_paid_total2' WHERE date_insert = '$date_insert' and location='$trip' and username='$user_charge' ");


	}
}	
$select1356=mysqli_query($db,"SELECT to_be,paid   FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' and username='$user_charge' ");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
    	 
					$to_be3 =$get13356['to_be']; 
					$paid3 =$get13356['paid']; 
$new_to_paid3 = $to_be3 - $to_be_org;
$new_paid3 = $paid3 - $paid_org;
$new_to_paid_total3 = $new_to_paid3 + $due_total;
$new_paid_total3 = $new_paid3 + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid_total3',paid='$new_paid_total3' WHERE date_insert = '$month' and location='$trip' and username='$user_charge' ");


	}
}	


?>