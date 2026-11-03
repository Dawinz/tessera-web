<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$select13=mysqli_query($db,"SELECT paid   FROM  store  WHERE location='$trip' and username='$username_uploader' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 $paid =$get133['paid']; 
$new_paid = $paid - $paid_original;
$insert=mysqli_query($db,"UPDATE store SET paid='$new_paid' WHERE location='$trip' and username='$username_uploader'");


	}
}	
$select134=mysqli_query($db,"SELECT paid,no_paid   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' and username='$username_uploader' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
$paid1 =$get1334['paid']; 
$new_paid1 = $paid1 - $paid_original;

$no_paid =$get1334['no_paid']; 
$totol_no_paid = $no_paid - 1;

$insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_paid1',no_paid='$totol_no_paid' WHERE date_insert = '$date_insert' and  location='$trip' and username='$username_uploader'");



	}
}

$select1345=mysqli_query($db,"SELECT paid,no_paid   FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' and username='$username_uploader'");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					
					$paid2 =$get13345['paid']; 

$new_paid2 = $paid2 - $paid_original;
		$no_paid_month =$get13345['no_paid']; 
$totol_no_paid_month = $no_paid_month - 1;

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_paid2',no_paid='$totol_no_paid_month' WHERE date_insert = '$month' and  location='$trip' and username='$username_uploader'");


	}
}
	
?>