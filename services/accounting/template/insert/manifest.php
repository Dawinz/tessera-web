<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
session_start();

include '../connect/connect.php';
include '../connect/connect_cargo.php';
include '../connect/connect_ticket.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$username=$_SESSION['username'];
 $select2=mysqli_query($db,"SELECT firstname,surname  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			//$system_user =$get12['username'];	
	}
}
$fullname_name = "$firstname $surname";




$route_name = trim((string) ($_POST['route'] ?? ''));
$trip_date = trim((string) ($_POST['trip_date'] ?? ''));
$vehicle_name = trim((string) ($_POST['vehicle_name'] ?? ''));
$vehicle_cag = trim((string) ($_POST['vehicle_cag'] ?? ''));
$route_day_id = "";
$manifet_id="";
$to_pay = 0;
$paid = 0;
$code = '';
$start_point = '';
$end_point = '';
$current_route = '';
$date_insert = date('Y-m-d', time());
$date_time_insert = date('h:i A', time());
$year = date("Y", strtotime($trip_date));
$month = date("F", strtotime($trip_date));
$insert_time = "$date_insert at $date_time_insert";
$parcel_detail ="";

if ($route_name === '' || $trip_date === '' || $vehicle_name === '') {
	http_response_code(400);
	echo 'invalid';
	exit;
}

$select_manifest=mysqli_query($db,"SELECT * FROM   manifest   where bus ='$vehicle_name' and trip_date='$trip_date' And month ='$month' and year='$year'");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest ==0){

$select=mysqli_query($db_up,"SELECT to_pay,paid,code FROM   vehicle_name   where Name ='$vehicle_name' and date='$trip_date'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $to_pay =$get['to_pay']; 
    	    $paid =$get['paid']; 
    	    $code =$get['code']; 
	}
$tamount = $to_pay + $paid;
$insert=mysqli_query($db,"INSERT INTO route_manifest 
(to_be,paid,amount,location,bus,trip_date,main_location) VALUES 
('$to_pay','$paid','$tamount','$route_name','$vehicle_name','$trip_date','$route_name')");	
	$parcel_detail = "<a href = '/cargo/vehicle_report_new.php?date=$trip_date&date2=$trip_date&date3=$vehicle_name&open=open'> Paid cargo</a>"	;

}


$total_ticket_cost = 0;
$select_ticket=mysqli_query($db_ticket,"SELECT * FROM   booking   WHERE " . tessera_sql_tenant_eq() . " AND (bus ='$vehicle_name' and trip_date='$trip_date' and cost !='0')");
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





$total_cargo = 0 ;

$total_income =  0 ;
$total_expenses = 0;
$icons = "typcn-lock-closed-outline";
$expenses = '0';
$balance = '0';
$checked = '';
$checked_count = '0';
$password = '';
$analysed = '';
$changed = '';
$changed_user = '';
$change_time = '';
$icon = '';

$select_point=mysqli_query($db,"SELECT start_point,end_point FROM   route   where fullname ='$route_name'  ");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
   	WHILE($get_point=mysqli_fetch_array($select_point)){
    	    $start_point =$get_point['start_point']; 
    	    $end_point =$get_point['end_point']; 
}
}
$location_start = $start_point !== '' ? $start_point : $route_name;

$select_r_currnecy=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$route_name'  ");
$select_num_r_currnecy=mysqli_num_rows($select_r_currnecy);
if($select_num_r_currnecy !=0){
   	WHILE($get_r_currnecy=mysqli_fetch_array($select_r_currnecy)){
    	    $current_route =$get_r_currnecy['Currency']; 
}
}

// STRICT SQL: supply every NOT NULL column (empty string is OK for several legacy fields).
$sqlQ = "INSERT INTO manifest (
	bus, trip_date, open_date, route_name, to_be, paid, amount, expenses, income, ticket, balance,
	checked, checked_count, lock_icon, password, analysed, month, year, location_start,
	changed, changed_user, change_time, icon, code
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $db->prepare($sqlQ);
if (!$stmt) {
	http_response_code(500);
	echo 'prepare failed: ' . $db->error;
	exit;
}
$stmt->bind_param(
	'ssssssssssssssssssssssss',
	$vehicle_name,
	$trip_date,
	$insert_time,
	$route_name,
	$to_pay,
	$paid,
	$total_cargo,
	$expenses,
	$total_income,
	$total_ticket_cost,
	$balance,
	$checked,
	$checked_count,
	$icons,
	$password,
	$analysed,
	$month,
	$year,
	$location_start,
	$changed,
	$changed_user,
	$change_time,
	$icon,
	$code
);
$insert = $stmt->execute();
if (!$insert) {
	http_response_code(500);
	echo 'insert failed: ' . $stmt->error;
	exit;
}

$sqlQ = "INSERT INTO   manifest_entries   (paid,to_be,trip_date,bus,main_route,route_name,start_route,end_route) VALUES (?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssssss", $paid,$to_pay,$trip_date,$vehicle_name,$route_name,$route_name,$start_point,$end_point); 
                $insert = $stmt->execute();
if (!$insert) {
	http_response_code(500);
	echo 'manifest_entries failed: ' . $stmt->error;
	exit;
}

include '../includes/vehicle_account.php';


$balance = $total_income  - $total_expenses;

//$update=mysqli_query($db,"UPDATE manifest SET expenses='$total_expenses',balance='$balance' WHERE bus ='$vehicle_name' and trip_date='$trip_date' ");

$busname = $vehicle_name;
$month = date("m-Y", strtotime($trip_date));
$select_acc=mysqli_query($db,"SELECT income,expenses FROM    vehicle_account    where bus ='$vehicle_name' and trip_date='$trip_date' ");
$select_num_acc=mysqli_num_rows($select_acc);
if($select_num_acc !=0){
   	WHILE($get_acc=mysqli_fetch_array($select_acc)){
    	    $income =$get_acc['income']; 
    	    $expenses =$get_acc['expenses']; 
    /*
    	  if($income !=0){
			  $amount =  $income;
			  include 'includes/combine_income.php';
	include 'includes/combine_account_monthly.php';
		  }else{
			  			   $amount =  $expenses;

			  include 'includes/combine_account_exp.php';
	include 'includes/combine_account_monthly_exp.php';
		  }
  */

	}
}

echo 'success';

}else{
echo "found";	
}


?>