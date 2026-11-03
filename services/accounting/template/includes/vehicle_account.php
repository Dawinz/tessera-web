<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('F', time());
$year = date('Y', time());
$date_time_insert = date('h:i A', time());
$bus_category = "Cargo";
$ticket_category = "Passangers";
$bus_particular = "Paid";
$ticket_particular = "Tickets";


if($route_day_id !=""){
$ticket_detail = "<a href  ='/tickets/sales_summary.php?id=$route_day_id&trip_date=$trip_date&manifest=$manifet_id'>$select_num_ticket Total tickets</a>";
}else{
	
$ticket_detail ="";

}



 $affect = "Paid"; 
 		  $select_route=mysqli_query($db,"SELECT * FROM   route   where fullname ='$route_name' ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
$start_point =$get_route['start_point']; 

}}
$status="Income";
$currency = "Tsh";
$currency_sign = "/=";
$source="api";
	if($paid !=0){	

$sqlQ = "INSERT INTO  vehicle_account  (category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month,current_route,year,fullname_user,username) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssssssss", $bus_category,$bus_particular,$paid,$affect,$paid,$date_insert,$date_time_insert,$status,$vehicle_name,$trip_date,$route_name,$start_point,$parcel_detail,$currency,$currency_sign,$source,$month,$route_name,$year,$fullname_name,$username); 
                $insert = $stmt->execute();	
	}	
		if($total_ticket_cost !=0){		
$sqlQ = "INSERT INTO  vehicle_account  (category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month,current_route,year,fullname_user,username) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssssssss", $ticket_category,$ticket_particular,$total_ticket_cost,$affect,$total_ticket_cost,$date_insert,$date_time_insert,$status,$vehicle_name,$trip_date,$route_name,$start_point,$ticket_detail,$currency,$currency_sign,$source,$month,$route_name,$year,$fullname_name,$username); 
                $insert = $stmt->execute();	
		}
$source = "constants";
$total_expenses = 0;
$select_constant=mysqli_query($db,"SELECT * FROM   constants   where route_name ='$route_name' and route_category='Main' and vehicle='$vehicle_cag' ");
$select_num_constant=mysqli_num_rows($select_constant);
if($select_num_constant !=0){
   	WHILE($get_constant=mysqli_fetch_array($select_constant)){
    	    $price_value =$get_constant['price_value']; 
    	    $name =$get_constant['name']; 
    	    $route_category =$get_constant['route_category']; 
    	    $name_category =$get_constant['name_category']; 
    	    $status =$get_constant['status'];     	  
    	    $affect =$get_constant['affect']; 
    	    $currency =$get_constant['currency']; 
    	  
    	  

		  $select_currency=mysqli_query($db,"SELECT * FROM   currency   where name ='$currency'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $symbol_value =$get_currency['symbol']; 
}}
		  
		  
		  $sqlQ = "INSERT INTO  vehicle_account  (category,name,expenses,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,currency,currency_sign,month,source,current_route,year,fullname_user,username) VALUES
		   (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssssssssssssssssss", $name_category,$name,$price_value,$affect,$price_value,$date_insert,$date_time_insert,$status,$vehicle_name,$trip_date,$route_name,$start_point,$currency,$currency_sign,$month,$source,$route_name,$year,$fullname_name,$username); 
                $insert = $stmt->execute();	
		  
		  
$total_expenses += $price_value;

	}
}
include'update/constant_manifest.php';


?>