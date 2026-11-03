<?php

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());

$total_income =0;
$total_expenses =0;

$select_combine=mysqli_query($db,"SELECT income,currency,expenses FROM   vehicle_account   where month ='$month'");
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
$update=mysqli_query($db,"UPDATE  monthly_account SET income='$total_income',expenses='$total_expenses' where  month='$month'");

}
?>