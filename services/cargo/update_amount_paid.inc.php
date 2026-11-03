<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
		$select13=mysqli_query($db,"SELECT paid   FROM  store  WHERE location='$trip' and username='$user_charge'  ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
		$paid =$get133['paid']; 
			
$old_paid = $paid - $amount_orginal;
$new_am_paid = $old_paid + $amount;
		

	   $insert=mysqli_query($db,"UPDATE store SET paid='$new_am_paid' WHERE location='$trip' and username='$user_charge' ");


	}
}	
$select135=mysqli_query($db,"SELECT paid   FROM daily_charges where date_insert = '$date_insert' and  location='$trip' and username='$user_charge'  ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	 
		$paid2 =$get1335['paid']; 
			
$old_paid2 = $paid2 - $amount_orginal;
$new_am_paid2 = $old_paid2 + $amount;
		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_am_paid2' WHERE date_insert = '$date_insert' and location='$trip' and username='$user_charge' ");


	}
}
$select1356=mysqli_query($db,"SELECT paid   FROM monthly_charges where date_insert = '$month' and  location='$trip' and username='$user_charge'  ");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
    	 
		$paid3 =$get13356['paid']; 
			
$old_paid3 = $paid3 - $amount_orginal;
$new_am_paid3 = $old_paid3 + $amount;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_am_paid3' WHERE date_insert = '$month' and location='$trip' and username='$user_charge' ");


	}
}
?>