<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' and username='$user_charge' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
$new_paid = $paid - $paid_org;
		
if($status=="Due payment"){
	$new_to_paid = $to_be - $to_be_org + $amount_orginal;

		   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip' and username='$user_charge'");

}else{
	$new_to_paid = $to_be + $amount_orginal;

			   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip' and username='$user_charge'");

}

	}
}
	
$select134=mysqli_query($db,"SELECT to_be,paid   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' and username='$user_charge' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be']; 
					$paid1 =$get1334['paid']; 

$new_paid1= $paid1 - $paid_org;
		
if($status=="Due payment"){
	$new_to_paid1 =  $to_be1 - $to_be_org + $amount_orginal;
$insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1' WHERE date_insert = '$date_insert' and location='$trip' and username='$user_charge'");

}else{
	$new_to_paid1 = $to_be1 + $paid_org;
	$new_paid1= $paid1 - $paid_org;
$insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1' WHERE date_insert = '$date_insert' and location='$trip' and username='$user_charge'");

}


	}
}
$select1346=mysqli_query($db,"SELECT to_be,paid,no_to_be,no_paid    FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' and username='$user_charge' ");
$select_num1346=mysqli_num_rows($select1346);
if($select_num1346 !=0){
   	WHILE($get13346=mysqli_fetch_array($select1346)){
    	 
					$to_be2 =$get13346['to_be']; 
					$paid2 =$get13346['paid']; 
					$no_to_be =$get13346['no_to_be'];
					$no_paid =$get13346['no_paid']; 
					
				
$new_to_paid2 = $to_be2 + $amount_orginal;
$new_paid2= $paid2 - $paid_org;
		
if($status=="Due payment"){
	$new_to_paid2 = $to_be2 - $to_be_org + $amount_orginal;
$insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2' WHERE date_insert = '$month' and location='$trip' and username='$user_charge'");
	
}else{
	$new_paid2= $paid2 - $paid_org;
$insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2' WHERE date_insert = '$month' and location='$trip' and username='$user_charge'");
	
}


	}
}

?>