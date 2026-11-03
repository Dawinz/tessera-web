<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i A', time());
$rand = rand(1,9);
$sort_month = date('Ym', time());


$select13=mysqli_query($db,"SELECT paid   FROM  store  WHERE location='$route' and username='$username_check' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
$paid =$get133['paid']; 
$new_paid = $paid + $amount;
$insert=mysqli_query($db,"UPDATE store SET paid='$new_paid' WHERE location='$route' and username='$username_check'");
}}else{
$insert=mysqli_query($db,"INSERT INTO store (paid,location,username) 
VALUES
('$amount','$route','$username_check')");
																						
}	

$select134=mysqli_query($db,"SELECT paid,no_paid  FROM  daily_charges  WHERE date_insert ='$date_insert' and  location='$route' and username='$username_check'");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$paid_daily =$get1334['paid']; 
					$no_paid =$get1334['no_paid']; 
					$total_no_paid = $no_paid +1;
$new_new_paid_daily = $paid_daily + $amount;
		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_new_paid_daily',no_paid='$total_no_paid',month='$month' WHERE  date_insert ='$date_insert' and  location='$route' and username='$username_check'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO daily_charges (paid,location,date_insert,no_paid,month,username) 
VALUES
                                            ('$amount','$route','$date_insert','1','$month','$username_check')");

}

$select1345=mysqli_query($db,"SELECT *   FROM  monthly_charges  WHERE date_insert ='$month' and  location='$route' and username='$username_check'  ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$paid_monthly =$get13345['paid'];
					$no_paid =$get13345['no_paid']; 
					$total_no_paid_month = $no_paid +1;
					
$new_paid_monthly = $paid_monthly + $amount;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_paid_monthly',no_paid='$total_no_paid_month' WHERE  date_insert ='$month' and  location='$route' and username='$username_check' ");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO monthly_charges (paid,location,date_insert,no_paid,sort,username) 
VALUES
                                            ('$amount','$route','$month','1','$sort_month','$username_check')");

}


?>