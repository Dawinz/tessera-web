<?php

function total_category($db,$name,$selected_currency,$route_name,$start_date,$end_date,$category_query_function){
//echo $category_query_function;
	$total_expenses =0; 
$select_status=mysqli_query($db,"SELECT expenses,current_route FROM   vehicle_account   where trip_date >= '$start_date' AND trip_date < '$end_date' and route_name='$route_name' $category_query_function and  expenses!='0'");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   
    	    $expenses =$get_status['expenses']; 
    	    $current_route =$get_status['current_route']; 

           
$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}

 if($selected_currency == $currency_curent){
$total_expenses += $expenses;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_expenses += $expenses*$currency_value;
}


    }}
return $total_expenses;
}
?>