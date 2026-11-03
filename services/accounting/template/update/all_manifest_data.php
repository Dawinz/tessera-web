<?php
 include '../connect/connect.php';

$select=mysqli_query($db,"SELECT bus,trip_date,route_name FROM   manifest    ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $bus =$get['bus']; 
    	    $trip_date =$get['trip_date']; 
    	    $route_name =$get['route_name']; 

$select_selected_currency=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$route_name'  ");
$select_num_currency=mysqli_num_rows($select_selected_currency);
if($select_num_currency !=0){
 	WHILE($get=mysqli_fetch_array($select_selected_currency)){
    	    $selected_currency =$get['Currency']; 
 }}


  $total_income =0;
    $total_expenses =0;

  
$select_status=mysqli_query($db,"SELECT status,bus,affect,income,expenses,route_name,current_route FROM   vehicle_account   where route_name='$route_name' and bus='$bus' and trip_date='$trip_date' ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){

    	    $income =$get_status['income']; 
    	    $expenses =$get_status['expenses']; 
    	    $current_route =$get_status['current_route']; 
    	    $affect =$get_status['affect']; 

if($affect=="To be Paid"){

}else{


$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}

if($selected_currency == $currency_curent){
$total_income += $income;
$total_expenses +=$expenses;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_income += $income*$currency_value;
$total_expenses += $expenses*$currency_value;
}


	}



            
    }}

echo "$bus $total_income $total_expenses <br>";
$update=mysqli_query($db,"UPDATE  manifest SET expenses='$total_expenses' , income = '$total_income' where bus ='$bus' and trip_date='$trip_date' And route_name='$route_name'");

	}
}
?>