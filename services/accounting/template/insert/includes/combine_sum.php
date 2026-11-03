<?php

$total_income =0;
$total_expenses =0;

$select_combine=mysqli_query($db,"SELECT income,currency,expenses FROM   vehicle_account   where bus ='$busname' and trip_date='$trip_date' ");
$select_num_combine=mysqli_num_rows($select_combine);
if($select_num_combine !=0){
   	WHILE($get_combine=mysqli_fetch_array($select_combine)){
    	    $combine_income =$get_combine['income']; 
    	    $combine_expenses =$get_combine['expenses']; 
    	    $currency =$get_combine['currency'];


$select_combine_currency=mysqli_query($db,"SELECT name,value_no FROM   currency  Where name ='$currency'");
$select_num_combine_currency=mysqli_num_rows($select_combine_currency);
if($select_num_combine_currency !=0){
   	WHILE($get_combine_currency=mysqli_fetch_array($select_combine_currency)){
    	    $name_currency =$get_combine_currency['name']; 
    	    $cost_currency=$get_combine_currency['value_no']; 
			$total_income +=$cost_currency *  $combine_income;
			$total_expenses +=$cost_currency *  $combine_expenses;
}}

			

	}
$total_amount = $total_expenses + $total_income; 
$update=mysqli_query($db,"UPDATE  manifest SET income='$total_income',expenses='$total_expenses',amount='$total_amount' where bus ='$busname' and trip_date='$trip_date'");

}
?>