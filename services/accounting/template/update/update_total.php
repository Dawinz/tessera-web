<?php
$total_income =0;
$total_expenses= 0;

if($affect=="To be Paid"){

}else{
	
$select_acc=mysqli_query($db,"SELECT income,expenses,current_route,affect FROM    vehicle_account    where bus ='$bus' and trip_date='$trip_date' ");
$select_num_acc=mysqli_num_rows($select_acc);
if($select_num_acc !=0){
   	WHILE($get_acc=mysqli_fetch_array($select_acc)){
    	    $income =$get_acc['income']; 
    	    $expenses =$get_acc['expenses']; 
			$current_route =$get_acc['current_route']; 
			$affect =$get_acc['affect']; 
if($affect=="To be Paid"){

}else{
    	

$select_route_currency=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$route_name' and status='main' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get=mysqli_fetch_array($select_route_currency)){
    	    $currency_main =$get['Currency']; 
 }}

$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}
if($currency_main == $currency_curent){
$total_income += $income;
$total_expenses += $expenses;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$currency_main' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_income += $income*$currency_value;
$total_expenses += $expenses*$currency_value;
}

}
	}
}

$update=mysqli_query($db,"UPDATE  manifest SET expenses='$total_expenses' , income = '$total_income' where bus ='$bus' and trip_date='$trip_date' And route_name='$route_name'");
}
?>