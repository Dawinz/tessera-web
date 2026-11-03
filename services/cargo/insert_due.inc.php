<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i A', time());
$rand = rand(1,9);
$sort_month = date('Ym', time());

$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$route' and username='$username_check' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 

				$paid =$get133['paid']; 
				$new_to_paid = $to_be + $due_total;
$new_paid = $paid + $amount_paid;



	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$route' and username='$username_check'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO store (paid,to_be,location,username) 
VALUES
 ('$amount_paid','$due_total','$route','$username_check')");

}

$select134=mysqli_query($db,"SELECT *   FROM  daily_charges  WHERE date_insert ='$date_insert' and  location='$route' and username='$username_check' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$paid_daily =$get1334['paid']; 

		$tobe_daily =$get1334['to_be']; 
		$no_due =$get1334['no_due']; 
		$total_no_due=$no_due+1;
		
$new_to_be_daily = $tobe_daily + $due_total;
$new_new_paid_daily = $paid_daily + $amount_paid;

	   $insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_new_paid_daily',to_be='$new_to_be_daily',no_due='$total_no_due',month='$month' WHERE  date_insert ='$date_insert' and  location='$route' and username='$username_check'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO daily_charges (paid,to_be,location,date_insert,no_due,month,username) 
VALUES
                                            ('$amount_paid','$due_total','$route','$date_insert','1','$month','$username_check')");

}


$select1345=mysqli_query($db,"SELECT *   FROM  monthly_charges  WHERE date_insert ='$month' and  location='$route' and username='$username_check' ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$paid_monthly =$get13345['paid']; 
					$tobe_monthly =$get13345['to_be']; 
					$no_due_month =$get13345['no_due']; 
					$total_no_due_month=$no_due_month +1;
$new_paid_monthly = $paid_monthly + $amount_paid;
$new_tobe_monthly = $tobe_monthly + $due_total;
		



	}
		   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_paid_monthly',to_be='$new_tobe_monthly',no_due='$total_no_due_month' WHERE  date_insert ='$month' and  location='$route' and username='$username_check'");


}else{

$insert=mysqli_query($db,"INSERT INTO monthly_charges (paid,to_be,location,date_insert,no_due,sort,username) 
VALUES
                                            ('$amount_paid','$due_total','$route','$month','1','$sort_month','$username_check')");

}


?>