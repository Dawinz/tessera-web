<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
		$select13=mysqli_query($db,"SELECT to_be,paid  FROM  store  WHERE location='$trip' and username='$username_uploader' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
					
$new_to_paid = $to_be - $to_be_orginal;
$new_paid = $paid - $paid_original;
$insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip' and username='$username_uploader'");


	}
}
$select134=mysqli_query($db,"SELECT to_be,paid,no_due   FROM   daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' and username='$username_uploader' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be']; 
					$paid1 =$get1334['paid']; 
					$no_due =$get1334['no_due']; 
					$total_no_due=$no_due-1;
$new_to_paid1 = $to_be1 - $to_be_orginal;
$new_paid1 = $paid1 - $paid_original;

		


	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1',no_due='$total_no_due' WHERE date_insert = '$date_insert' and location='$trip' and username='$username_uploader'");


	}
}	

$select1345=mysqli_query($db,"SELECT to_be,paid,no_due   FROM   monthly_charges  WHERE date_insert = '$month' and  location='$trip' and username='$username_uploader'  ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$to_be2 =$get13345['to_be']; 
					$paid2 =$get13345['paid'];
					$no_due_month =$get13345['no_due']; 
					$totoal_no_due_motnth = $no_due_month-1;
$new_to_paid2 = $to_be2 - $to_be_orginal;
$new_paid2 = $paid2 - $paid_original;

		


	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2',no_due='$totoal_no_due_motnth' WHERE date_insert = '$month' and location='$trip' and username='$username_uploader'");


	}
}	
	

?>