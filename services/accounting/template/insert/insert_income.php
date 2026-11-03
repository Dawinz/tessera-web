<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
include '../connect/connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date('h:i A', time());
$point_value= $_POST['point_value'];	
$busname= $_POST['busname'];	
$trip_date= $_POST['trip_date'];	
$route_name= $_POST['route_name'];	
$main_route= $_POST['main_route'];	
$current_route= $_POST['current_route'];	
$end_point= $_POST['end_point'];	
$acc_type = $_POST['acc_type'];	

	session_start();
$username=$_SESSION['username'];
 if(isset($username)){
	 


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


$year = date("Y", strtotime($trip_date));
$month = date("F", strtotime($trip_date));


for($count = 0; $count < count($_POST["part_Category"]); $count++)

	{
$part_Category= $_POST['part_Category'][$count];
$part_detail= $_POST['part_detail'][$count];
$part_Currency= $_POST['part_Currency'][$count];
$amount= $_POST['amount'][$count];
$detail= $_POST['detail'][$count];
$amount = preg_replace('/[^0-9.]+/', '', $amount);
$detail = str_replace("'" , "_", $detail);
$detail = str_replace("," , "~", $detail);

$select_currency=mysqli_query($db,"SELECT symbol,value_no,name FROM   currency   where name ='$part_Currency'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $currency_symbol =$get_currency['symbol']; 
	    	    $currency_value_no =$get_currency['value_no']; 
	    	    $currency_name =$get_currency['name']; 

	}
}
$select_affect=mysqli_query($db,"SELECT affect FROM   particulars   where category ='$part_Category' and name='$part_detail' ");
$select_num_affect=mysqli_num_rows($select_affect);
if($select_num_affect!=0){
   	WHILE($get_affect=mysqli_fetch_array($select_affect)){
    	    $affect =$get_affect['affect']; 

	}
}

if (!isset($affect) || $affect === '') {
	$affect = 'Paid';
}
if (!isset($currency_symbol) || $currency_symbol === '') {
	$currency_symbol = '';
}
$zero = '0';
$source = '';

if ($acc_type == 'Income') {
	$status_name = 'Income';
	$sqlQ = 'INSERT INTO vehicle_account (bus,trip_date,income,expenses,price,category,name,detail,currency,date_insert,time_insert,affect,route_name,point,month,currency_sign,status,current_route,year,fullname_user,username,source,changed) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$changed = '';
	$stmt->bind_param(
		'sssssssssssssssssssssss',
		$busname,
		$trip_date,
		$amount,
		$zero,
		$amount,
		$part_Category,
		$part_detail,
		$detail,
		$part_Currency,
		$date_insert,
		$date_time_insert,
		$affect,
		$route_name,
		$point_value,
		$month,
		$currency_symbol,
		$status_name,
		$current_route,
		$year,
		$fullname_name,
		$username,
		$source,
		$changed
	);
	$insert = $stmt->execute();
	if (!$insert) {
		http_response_code(500);
		echo 'insert failed: ' . $stmt->error;
		exit;
	}
} else {
	$status_name = 'Expenses';
	$sqlQ = 'INSERT INTO vehicle_account (bus,trip_date,income,expenses,price,category,name,detail,currency,date_insert,time_insert,affect,route_name,point,month,currency_sign,status,current_route,year,fullname_user,username,source,changed) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$changed = '';
	$stmt->bind_param(
		'sssssssssssssssssssssss',
		$busname,
		$trip_date,
		$zero,
		$amount,
		$amount,
		$part_Category,
		$part_detail,
		$detail,
		$part_Currency,
		$date_insert,
		$date_time_insert,
		$affect,
		$route_name,
		$point_value,
		$month,
		$currency_symbol,
		$status_name,
		$current_route,
		$year,
		$fullname_name,
		$username,
		$source,
		$changed
	);
	$insert = $stmt->execute();
	if (!$insert) {
		http_response_code(500);
		echo 'insert failed: ' . $stmt->error;
		exit;
	}
}



				
		$location_start = "";		
$select_manifest=mysqli_query($db,"SELECT location_start FROM   manifest   where bus ='$busname' and trip_date='$trip_date' And route_name='$main_route' and location_start != ''");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
 	WHILE($get=mysqli_fetch_array($select_manifest)){
    	    $location_start =$get['location_start']; 
	
		$new_array = explode(",", "$location_start");
	  if (($key = array_search($point_value, $new_array)) !== false) {


$update=mysqli_query($db,"UPDATE  manifest  SET 
	location_start='$point_value'
	WHERE     bus ='$busname' and trip_date='$trip_date' And route_name='$main_route'");	

}else{
$new_bus =  implode(",",$new_array);	
$new_point_value = "$location_start,$point_value";
$update=mysqli_query($db,"UPDATE  manifest  SET 
	location_start='$new_point_value'
	WHERE     bus ='$busname' and trip_date='$trip_date' And route_name='$main_route'");	

}
	
	
}

}	else{
$update=mysqli_query($db,"UPDATE  manifest  SET 
	location_start='$point_value'
	WHERE     bus ='$busname' and trip_date='$trip_date' And route_name='$main_route'");	




}		
				
	include'includes/update_manifest.php';			

	}

echo 'success';

 }else{
echo "log_out";
 }
 


?>