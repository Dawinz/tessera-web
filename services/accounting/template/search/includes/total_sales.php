<?php

function BusRoutes($bus,$db,$start_date,$end_date,$route_name){
	$route_name="and $route_name";
	$route_array=array();
$select_status=mysqli_query($db,"SELECT route_name FROM   vehicle_account   where ".$start_date." ".$end_date." ".$route_name." bus='$bus' 1 GROUP BY route_name  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   
    	   
    	   
    	    $route_name =$get_status['route_name']; 
			array_push($route_array,$route_name);
	}}
	echo implode(', ', $route_array);
}

function incomeTicket($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name){


    $total_ticket_income =0;

$select_status=mysqli_query($db,"SELECT status,bus,affect,income,route_name,current_route FROM   vehicle_account   where ".$start_date." ".$end_date." ".$route_name."  bus='$bus' and category='Passanger' and name='Ticket' and status='Income'");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   
    	    $bus =$get_status['bus']; 
    	   
    	    $route_name =$get_status['route_name']; 
    	    $income =$get_status['income']; 
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
$total_ticket_income += $income;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_ticket_income += $income*$currency_value;
}


	}
}
}
return $total_ticket_income;
};
function incomeRoadCollection($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name){
    $total_ticket_income =0;
$select_status=mysqli_query($db,"SELECT status,month,trip_date,bus,affect,currency,route_name,income,current_route FROM   vehicle_account   where ".$start_date." ".$end_date." ".$route_name." bus='$bus' and category='Passanger' and name='Road collection' and status='Income'   ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	    $status =$get_status['status']; 
    	    $trip_date =$get_status['trip_date']; 
    	    $bus =$get_status['bus']; 
    	   $currency =$get_status['currency']; 
    	    $route_name =$get_status['route_name']; 
    	    $income =$get_status['income']; 
    	    $current_route =$get_status['current_route']; 
    	    $affect =$get_status['affect']; 
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
if($selected_currency == $currency_curent){
$total_ticket_income += $income;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_ticket_income += $income*$currency_value;
}


	}
}
}
return $total_ticket_income;
};

function incomeCargo($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name){
    $total_ticket_income =0;

$select_status=mysqli_query($db,"SELECT status,month,trip_date,bus,affect,route_name,income,current_route FROM   vehicle_account   where ".$start_date." ".$end_date." ".$route_name." bus='$bus' and category='Cargo' and status='Income'  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	 
    	    $bus =$get_status['bus']; 
    	    $route_name =$get_status['route_name']; 
    	    $income =$get_status['income']; 
    	    $current_route =$get_status['current_route']; 
    	    $affect =$get_status['affect']; 
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
if($selected_currency == $currency_curent){
$total_ticket_income += $income;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_ticket_income += $income*$currency_value;
}


	}
}
}

return  $total_ticket_income;

};
function expenses($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name){
    $total_expenses =0;

$select_status=mysqli_query($db,"SELECT status,month,trip_date,bus,affect,currency,route_name,income,current_route,expenses FROM   vehicle_account   where ".$start_date." ".$end_date." ".$route_name." bus='$bus' and  status='expenses'  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	 
    	    $bus =$get_status['bus']; 
    	    $route_name =$get_status['route_name']; 
    	    $expenses =$get_status['expenses']; 
    	    $current_route =$get_status['current_route']; 
    	    $affect =$get_status['affect']; 
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


	}
}
}

return  $total_expenses;

};

function sumTwoNumbers($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name) {
    $cargo = incomeCargo($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    $road_collection = incomeRoadCollection($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    $ticket = incomeTicket($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    echo "". $cargo+$road_collection+$ticket."";
}
function balance($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name) {

    $cargo = incomeCargo($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    $road_collection = incomeRoadCollection($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    $ticket = incomeTicket($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    $expenses = expenses($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
	if($expenses >0){
    echo "". $cargo+$road_collection+$ticket-$expenses."";

	}else{
    echo "".$cargo+$road_collection+$ticket-$expenses."";
	}
}
function incomeCargoReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name) {
    $cargo = incomeCargo($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    
    echo "". $cargo."";
}
function incomeTicketReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name) {
    $ticket = incomeRoadCollection($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    
    echo "". $ticket."";
}
function incomeRoadCollectionReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name) {
    $road_collection = incomeTicket($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    
    echo "". $road_collection."";
}
function expensesReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name) {
    $expenses = expenses($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);
    
    echo "".$expenses."";
}
?>