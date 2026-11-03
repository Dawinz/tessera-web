<?php
include '../connect/connect.php';
$total_income = 0;
$total_income2 = 0;
$select_income=mysqli_query($db,"SELECT income  FROM    vehicle_account    where month='$month' ");
$select_num_income=mysqli_num_rows($select_income);
if($select_num_income !=0){
   	WHILE($get_income=mysqli_fetch_array($select_income)){
    	    $particular_income =$get_income['income']; 
  $total_income += $particular_income;	
  
}}

 	$insert=mysqli_query($db,"UPDATE  monthly_account  SET 
	income='$total_income'
	WHERE month='$month'"); 	
	
	
$select_income2=mysqli_query($db,"SELECT income  FROM    vehicle_account    where bus='$bus' and trip_date = '$trip_date' ");
$select_num_income2=mysqli_num_rows($select_income2);
if($select_num_income2 !=0){
   	WHILE($get_income2=mysqli_fetch_array($select_income2)){
    	    $particular_income2 =$get_income2['income']; 
  $total_income2 += $particular_income2;	
  
}}	
	
	$insert=mysqli_query($db,"UPDATE  account_transaction  SET 
	income='$total_income2'
	WHERE bus='$bus' and trip_date = '$trip_date'");

?>