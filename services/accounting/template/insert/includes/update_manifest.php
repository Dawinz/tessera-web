<?php
include'../connect/connect.php';
if($affect!="To be Paid"){
$select_manifest_sum=mysqli_query($db,"SELECT income,expenses FROM   manifest   where bus ='$busname' and trip_date='$trip_date' And route_name='$main_route' ");
$select_num_manifest_sum=mysqli_num_rows($select_manifest_sum);
if($select_num_manifest_sum !=0){
 	WHILE($get=mysqli_fetch_array($select_manifest_sum)){
    	    $total_income =$get['income']; 
    	    $total_expenses =$get['expenses']; 

    }}
$select_route_currency=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$main_route' and status='main' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get=mysqli_fetch_array($select_route_currency)){
    	    $currency_curent =$get['Currency']; 
 }}

if($currency_curent == $currency_name){
if($acc_type == "Income"){
$new_manifest_income = $amount+$total_income;
$update=mysqli_query($db,"UPDATE  manifest SET income='$new_manifest_income' where bus ='$busname' and trip_date='$trip_date' And route_name='$main_route'");

}else{
	$new_manifest_expnseses = $amount+$total_expenses;
$update=mysqli_query($db,"UPDATE  manifest SET expenses='$new_manifest_expnseses' where bus ='$busname' and trip_date='$trip_date' And route_name='$main_route'");

}
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_name' and second_point='$currency_curent' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$amount = $amount*$currency_value;

 if($acc_type == "Income"){
$new_manifest_income = $amount+$total_income;
$update=mysqli_query($db,"UPDATE  manifest SET income='$new_manifest_income' where bus ='$busname' and trip_date='$trip_date' And route_name='$main_route'");

}else{
	$new_manifest_expnseses = $amount+$total_expenses;
$update=mysqli_query($db,"UPDATE  manifest SET expenses='$new_manifest_expnseses' where bus ='$busname' and trip_date='$trip_date' And route_name='$main_route'");

}
}
}else{
	
}
 


?>