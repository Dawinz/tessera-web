<?php
require_once __DIR__ . '/../../includes/tessera_tenant.php';

include '../../connect/connect.php';
include '../../connect/connect_cargo.php';
include '../../connect/connect_ticket.php';

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i A', time());
$bus_category = "Cargo";
$ticket_category = "Passangers";
$bus_particular = "Paid";
$ticket_particular = "Tickets";

$current_route = $_POST['current_route'];
$route_name = $_POST['route_name'];
$vehicle_name = $_POST['bus_name'];
$trip_date = $_POST['trip_date'];
$current_route = preg_replace('/_/',' ', $current_route);
$route_name = preg_replace('/_/',' ', $route_name);
$start_point = preg_replace('/_/',' ', $_POST['start_point']);
$end_point = preg_replace('/_/',' ', $_POST['end_point']);
$to_pay = 0;
$paid = 0;
$total_ticket_cost = 0;
 $affect = "paid"; 
$select_ticket=mysqli_query($db_ticket,"SELECT * FROM   booking   WHERE " . tessera_sql_tenant_eq() . " AND (bus ='$vehicle_name' and trip_date='$trip_date' and cost !='0' and start_route='$start_point')");
$select_num_ticket=mysqli_num_rows($select_ticket);
if($select_num_ticket !=0){
   	WHILE($get_ticket=mysqli_fetch_array($select_ticket)){
    	    $cost =$get_ticket['cost']; 
    	  
    	  
$total_ticket_cost += $cost;

	}
} 


$select_ticket_manifest=mysqli_query($db_ticket,"SELECT * FROM   manifest   where bus ='$vehicle_name' and trip_date='$trip_date' ");
$select_num_ticket_manifest=mysqli_num_rows($select_ticket_manifest);
if($select_num_ticket_manifest !=0){
   	WHILE($get_ticket_manifest=mysqli_fetch_array($select_ticket_manifest)){
    	    $route_day_id =$get_ticket_manifest['route_day_id']; 
    	    $manifet_id =$get_ticket_manifest['id']; 
    	  
    	  

	}
}
if($route_day_id !=""){
$ticket_detail = "<a href  ='/tickets/sales_summary.php?id=$route_day_id&trip_date=$trip_date&manifest=$manifet_id'>$select_num_ticket Total tickets</a>";
$parcel_detail = "<a href = '/cargo/vehicle_report_new.php?date=$trip_date&date2=$trip_date&date3=$vehicle_name&open=open'> Paid cargo</a>"	;
}else{
	
	$ticket_detail ="";

}
$select=mysqli_query($db_up,"SELECT * FROM   vehicle_name   where Name ='$vehicle_name' and date='$trip_date' and Route='$current_route'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $to_pay =$get['to_pay']; 
    	    $paid =$get['paid']; 
    	   

	}
}



$select_entries=mysqli_query($db,"SELECT * FROM   manifest_entries   where bus ='$vehicle_name' and trip_date='$trip_date' and route_name='$current_route'");
$select_num_entries=mysqli_num_rows($select_entries);
if($select_num_entries ==0){
	
$sqlQ = "INSERT INTO   manifest_entries   (paid,to_be,trip_date,bus,main_route,route_name,start_route,end_route) VALUES (?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssssss", $paid,$to_pay,$trip_date,$vehicle_name,$route_name,$current_route,$start_point,$end_point); 
                $insert = $stmt->execute();		
	

}else{


$update=mysqli_query($db,"UPDATE  manifest_entries  SET 
	paid='".$paid."',
	to_be='".$to_pay."'
	where bus ='$vehicle_name' and trip_date='$trip_date' and route_name='$current_route'");	
	
	include 'includes/update_manifest.php';
}



$status="Income";
$currency = "Tsh";
$currency_sign = "/=";
$source="api";
if($select_num !=0){
$insert=mysqli_query($db,"DELETE from vehicle_account WHERE bus ='$vehicle_name' and trip_date='$trip_date' and source='api'");
$sqlQ = "INSERT INTO  vehicle_account  (category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssss", $bus_category,$bus_particular,$paid,$affect,$paid,$date_insert,$date_time_insert,$status,$vehicle_name,$trip_date,$route_name,$start_point,$parcel_detail,$currency,$currency_sign,$source,$month); 
                $insert = $stmt->execute();	
}
if($select_num_ticket !=0){
$insert=mysqli_query($db,"DELETE from vehicle_account WHERE bus ='$vehicle_name' and trip_date='$trip_date' and source='api'");

$sqlQ = "INSERT INTO  vehicle_account  (category,name,income,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,detail,currency,currency_sign,source,month) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssss", $ticket_category,$ticket_particular,$total_ticket_cost,$affect,$total_ticket_cost,$date_insert,$date_time_insert,$status,$vehicle_name,$trip_date,$route_name,$start_point,$ticket_detail,$currency,$currency_sign,$source,$month); 
                $insert = $stmt->execute();	

}



$select=mysqli_query($db,"SELECT * FROM   vehicle_account   where bus ='$vehicle_name' and trip_date='$trip_date' and route_name='$route_name' and point='$start_point'");
$select_num=mysqli_num_rows($select);
if($select_num ==0){


		  $source = "constants";
$total_expenses = 0;
$select_constant=mysqli_query($db,"SELECT * FROM   constants   where route_name ='$current_route'  ");
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
    	  
		  echo $price_value;
    	  

		  $select_currency=mysqli_query($db,"SELECT * FROM   currency   where name ='$currency'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $symbol_value =$get_currency['symbol']; 
}}
		  
		  
		  $sqlQ = "INSERT INTO  vehicle_account  (category,name,expenses,affect,price,date_insert,time_insert,status,bus,trip_date,route_name,point,currency,currency_sign,month,source) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssssssssssssss", $name_category,$name,$price_value,$affect,$price_value,$date_insert,$date_time_insert,$status,$vehicle_name,$trip_date,$route_name,$start_point,$currency,$currency_sign,$month,$source); 
                $insert = $stmt->execute();	
		  
		  
$total_expenses += $price_value;

	}
}



}


$busname = $vehicle_name;
$month = date("m-Y", strtotime($trip_date));
$select_acc=mysqli_query($db,"SELECT income,expenses FROM    vehicle_account    where bus ='$vehicle_name' and trip_date='$trip_date' ");
$select_num_acc=mysqli_num_rows($select_acc);
if($select_num_acc !=0){
   	WHILE($get_acc=mysqli_fetch_array($select_acc)){
    	    $income =$get_acc['income']; 
    	    $expenses =$get_acc['expenses']; 
    	
    	  
    	  if($income !=0){
			  $amount =  $income;
			  include '../includes/combine_income.php';
	include '../includes/combine_account_monthly.php';
		  }else{
			   $amount =  $expenses;
			  include '../includes/combine_account_exp.php';
	include '../includes/combine_account_monthly_exp.php';
		  }

	}
}



?>