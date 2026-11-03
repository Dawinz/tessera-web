<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i A', time());
$rand = rand(1,9);
$sort_month = date('Ym', time());


$select13=mysqli_query($db,"SELECT to_be   FROM  store  WHERE location='$route' and username='$username_check' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
$new_to_paid = $to_be + $amount;
		

	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid' WHERE location='$route' and username='$username_check'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO store (to_be,location,username) 
VALUES
                                            ('$amount','$route','$username_check')");

}


$select134=mysqli_query($db,"SELECT to_be,no_to_be   FROM  daily_charges  WHERE date_insert ='$date_insert' and  location='$route' and username='$username_check' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$tobe_daily =$get1334['to_be']; 
					$no_to_be =$get1334['no_to_be']; 
					$total_no_to_be =$no_to_be+1;
					
$new_to_be_daily = $tobe_daily + $amount;
		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_be_daily',no_to_be='$total_no_to_be',month='$month' WHERE  date_insert ='$date_insert' and location='$route' and username='$username_check'");

	}
}else{

$insert=mysqli_query($db,"INSERT INTO daily_charges (to_be,location,date_insert,no_to_be,month,username) 
VALUES
                                            ('$amount','$route','$date_insert','1','$month','$username_check')");

}


$select1345=mysqli_query($db,"SELECT to_be,no_to_be  FROM  monthly_charges  WHERE date_insert ='$month' and  location='$route' and username='$username_check' ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$tobe_monthly =$get13345['to_be']; 
					$no_to_be =$get13345['no_to_be']; 
					$total_no_to_be_month = $no_to_be+1;
$new_to_be_monthly = $tobe_monthly + $amount;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_be_monthly',no_to_be='$total_no_to_be_month' WHERE  date_insert ='$month' and  location='$route' and username='$username_check' ");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO monthly_charges (to_be,location,date_insert,no_to_be,sort,username) 
VALUES
                                            ('$amount','$route','$month','1','$sort_month','$username_check')");

}




?>