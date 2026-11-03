<?php
include '../connect/connect.php';
$total_expenses = 0;
$total_expenses2 = 0;
$select_expenses=mysqli_query($db,"SELECT expenses  FROM    vehicle_account    where month='$month' ");
$select_num_expenses=mysqli_num_rows($select_expenses);
if($select_num_expenses !=0){
   	WHILE($get_expenses=mysqli_fetch_array($select_expenses)){
    	    $particular_expenses =$get_expenses['expenses']; 
  $total_expenses += $particular_expenses;	
  
}}

 	$insert=mysqli_query($db,"UPDATE  monthly_account  SET 
	expenses='$total_expenses'
	WHERE month='$month'"); 	
	
	
$select_expenses2=mysqli_query($db,"SELECT expenses  FROM    vehicle_account    where bus='$bus' and trip_date = '$trip_date' ");
$select_num_expenses2=mysqli_num_rows($select_expenses2);
if($select_num_expenses2 !=0){
   	WHILE($get_expenses2=mysqli_fetch_array($select_expenses2)){
    	    $particular_expenses2 =$get_expenses2['expenses']; 
  $total_expenses2 += $particular_expenses2;	
  
}}	
	
	$insert=mysqli_query($db,"UPDATE  account_transaction  SET 
	expenses='$total_expenses2'
	WHERE bus='$bus' and trip_date = '$trip_date'");

?>