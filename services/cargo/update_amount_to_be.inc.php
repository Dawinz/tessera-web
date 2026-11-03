<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' and username='$user_charge' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
			
$old_to_paid = $to_be - $amount_orginal;
$new_am_to_be = $old_to_paid + $amount;

		

	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_am_to_be'WHERE location='$trip' and username='$user_charge' ");


	}
}	

$select135=mysqli_query($db,"SELECT to_be   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' and username='$user_charge'  ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	 
					$to_be2 =$get1335['to_be']; 
			
$old_to_paid2 = $to_be2 - $amount_orginal;
$new_am_to_be2 = $old_to_paid2 + $amount;

		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_am_to_be2' WHERE date_insert = '$date_insert' and location='$trip' and username='$user_charge' ");


	}
}
$select1356=mysqli_query($db,"SELECT to_be   FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' and username='$user_charge'  ");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
    	 
					$to_be3 =$get13356['to_be']; 
			
$old_to_paid3 = $to_be3 - $amount_orginal;
$new_am_to_be3 = $old_to_paid3 + $amount;

		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_am_to_be3' WHERE date_insert = '$month' and location='$trip' and username='$user_charge' ");


	}
}

?>