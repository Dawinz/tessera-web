<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
		$select13=mysqli_query($db,"SELECT to_be   FROM  store  WHERE location='$trip' and username='$username_uploader' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					
$new_to_paid = $to_be - $to_be_orginal;

		

	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid' WHERE location='$trip'");


	}
}	

$select134=mysqli_query($db,"SELECT to_be,no_to_be   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' and username='$username_uploader'");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be'];
					$no_to_be =$get1334['no_to_be']; 
				$total_no_to_be=$no_to_be-1;
$new_to_paid1 = $to_be1 - $to_be_orginal;

		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',no_to_be='$total_no_to_be' WHERE date_insert = '$date_insert' and location='$trip' and username='$username_uploader'");


	}
}


$select1346=mysqli_query($db,"SELECT to_be,no_to_be  FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' and username='$username_uploader' ");
$select_num1346=mysqli_num_rows($select1346);
if($select_num1346 !=0){
   	WHILE($get13346=mysqli_fetch_array($select1346)){
    	 
					$to_be2 =$get13346['to_be']; 
				$no_to_be_month =$get13346['no_to_be']; 
				$total_no_to_be_month = $no_to_be_month-1;
$new_to_paid2 = $to_be2 - $to_be_orginal;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',no_to_be='$total_no_to_be_month' WHERE date_insert = '$month' and location='$trip' and username='$username_uploader'");


	}
}

?>
