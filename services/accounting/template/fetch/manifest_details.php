<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

session_start();


include '../connect/connect_cargo.php';
include '../connect/connect.php';
include '../connect/connect_ticket.php';
$trip_date = $_POST['trip_date'];
$route_name = $_POST['route_name'];
$og_trip_date = $_POST['og_trip_date'];
$main_route = $_POST['main_route'];
$ticket_category = "Passangers";
$bus_particular = "Paid";
$ticket_particular = "Tickets";
$bus_category = "Cargo";
$date_insert = date('Y-m-d', time());
$date_time_insert = date('h:i A', time());
$year = date("Y", strtotime($og_trip_date));
$month = date("F", strtotime($og_trip_date)); 
$insert_time = "$date_insert at $date_time_insert";
$route_day_id="";
$username_select=$_SESSION['username'];

 $select2=mysqli_query($db,"SELECT firstname,surname  FROM  users  where username='$username_select'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			//$system_user =$get12['username'];	
	}
}
$fullname_name = "$firstname $surname";

$select_currency=mysqli_query($db_up,"SELECT to_pay,paid,Name FROM   vehicle_name   where Route ='$route_name' and date='$trip_date'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $paid =$get_currency['paid']; 
    	    $to_pay =$get_currency['to_pay']; 
    	    $name =$get_currency['Name']; 
    	    $bus =$get_currency['Name']; 
			
	}
}
$tamount = $paid + $to_pay;
$total_ticket_cost = 0;
$select_ticket=mysqli_query($db_ticket,"SELECT cost,route_day_id FROM   booking   WHERE " . tessera_sql_tenant_eq() . " AND (bus ='$name' and trip_date='$og_trip_date' and cost !='0')");
$select_num_ticket=mysqli_num_rows($select_ticket);
if($select_num_ticket !=0){
   	WHILE($get_ticket=mysqli_fetch_array($select_ticket)){
    	    $cost =$get_ticket['cost']; 
    	    $route_day_id =$get_ticket['route_day_id']; 
    	  
    	  
$total_ticket_cost += $cost;

	}
}


$select_ticket_manifest=mysqli_query($db_ticket,"SELECT id FROM   manifest   where bus ='$name' and trip_date='$og_trip_date' ");
$select_num_ticket_manifest=mysqli_num_rows($select_ticket_manifest);
if($select_num_ticket_manifest !=0){
   	WHILE($get_ticket_manifest=mysqli_fetch_array($select_ticket_manifest)){
    	    $manifet_id =$get_ticket_manifest['id']; 
    	  
    	  

	}
}
if($route_day_id !=""){
$ticket_detail = "<a href  ='/tickets/sales_summary.php?id=$route_day_id&trip_date=$og_trip_date&manifest=$manifet_id'>$select_num_ticket Total tickets</a>";
$parcel_detail = "<a href = '/cargo/vehicle_report_new.php?date=$og_trip_date&date2=$og_trip_date&date3=$name&open=open'> Paid cargo</a>"	;
}else{
	
$ticket_detail ="";
$parcel_detail ="";
}

$affect = "paid"; 
$select_route=mysqli_query($db,"SELECT start_point,currency FROM   route   where fullname ='$route_name' ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
$start_point =$get_route['start_point']; 

$currency =$get_route['currency']; 

}}
$status="Income";
$source="api";

	  $select_currency=mysqli_query($db,"SELECT * FROM   currency   where name ='$currency'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $currency_sign =$get_currency['symbol']; 
}}


/*

*/
	if($total_ticket_cost!=0){
	$sqlQ = "INSERT INTO  vehicle_account  (category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month,current_route,year) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssssss", $ticket_category,$ticket_particular,$total_ticket_cost,$affect,$total_ticket_cost,$date_insert,$date_time_insert,$status,$name,$og_trip_date,$main_route,$start_point,$parcel_detail,$currency,$currency_sign,$source,$month,$route_name,$year); 
                $insert = $stmt->execute();	
	
	}
	if($tamount!=0){
	$sqlQ = "INSERT INTO  vehicle_account  (category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month,current_route,year) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssssss", $bus_category,$bus_particular,$paid,$affect,$paid,$date_insert,$date_time_insert,$status,$name,$og_trip_date,$main_route,$start_point,$parcel_detail,$currency,$currency_sign,$source,$month,$route_name,$year); 
                $insert = $stmt->execute();	
	
	}

$select_manifest_entries=mysqli_query($db,"SELECT paid FROM  route_manifest   where bus ='$name' and trip_date='$trip_date' and location='$route_name'");
$select_num_manifest_entries=mysqli_num_rows($select_manifest_entries);
if($select_num_manifest_entries ==0){
$insert=mysqli_query($db,"INSERT INTO route_manifest 
(to_be,paid,amount,location,bus,trip_date,main_location,og_trip_date) VALUES 
('$to_pay','$paid','$tamount','$route_name','$name','$og_trip_date','$main_route','$trip_date')");	
}else{
	
}
$select_constant=mysqli_query($db,"SELECT * FROM   constants   where route_name ='$route_name' and route_category='Sub Route' and vehicle='Bus' ");
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
    	  
    	  echo $name;

		  $select_currency=mysqli_query($db,"SELECT * FROM   currency   where name ='$currency'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $symbol_value =$get_currency['symbol']; 
}}
		  
		  
		  $sqlQ = "INSERT INTO  vehicle_account  (category,name,expenses,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,currency,currency_sign,month,source,current_route,year,fullname_user,username) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssssssssssssssssss", $name_category,$bus,$price_value,$affect,$price_value,$date_insert,$date_time_insert,$status,$name,$trip_date,$route_name,$start_point,$currency,$currency_sign,$month,$source,$route_name,$year,$fullname_name,$username_select); 
                $insert = $stmt->execute();	
		  
		  


	}
}
?>