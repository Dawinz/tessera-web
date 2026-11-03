<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
include '../connect/connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i A', time());
$point_value= $_POST['point_value'];	
$busname= $_POST['busname'];	
$trip_date= $_POST['trip_date'];	
$route_name= $_POST['route_name'];	
$main_route= $_POST['main_route'];	
$current_route= $_POST['current_route'];	
$row_num= $_POST['row_num'];	
$end_point= $_POST['end_point'];	
$affect = "paid";

			$select_route_name=mysqli_query($db,"SELECT end_point,start_point FROM   route   where fullname ='$main_route'  ");
$select_num_route_name=mysqli_num_rows($select_route_name);
if($select_num_route_name !=0){
   	WHILE($get_route_name=mysqli_fetch_array($select_route_name)){
    	    $end_point =$get_route_name['end_point']; 
    	    $start_point =$get_route_name['start_point']; 

    	  
    	  

	}
}

for($count = 0; $count < count($_POST["part_Category_exp"]); $count++)

	{
$part_Category= $_POST['part_Category_exp'][$count];
$part_detail= $_POST['part_detail_exp'][$count];
$part_Currency= $_POST['part_Currency_exp'][$count];
$amount= $_POST['amount_exp'][$count];
$detail= $_POST['detail_exp'][$count];
$amount = preg_replace('/[^0-9.]+/', '', $amount);
$detail = str_replace("'" , "_", $detail);
$detail = str_replace("," , "~", $detail);

$select_currency=mysqli_query($db,"SELECT symbol,value_no  FROM   currency   where name ='$part_Currency'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $currency_symbol =$get_currency['symbol']; 
    	    $currency_value_no =$get_currency['value_no']; 
	}
}

$status_name = "Expenses";
$sqlQ = "INSERT INTO   vehicle_account  (bus,trip_date,expenses,price,category,name,detail,currency,date_insert,time_insert,affect,route_name,point,month,currency_sign,status,current_route) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssss", $busname,$trip_date,$amount,$amount,$part_Category,$part_detail,$detail,$part_Currency,$date_insert,$date_time_insert,$affect,$route_name,$point_value,$month,$currency_symbol,$status_name,$current_route); 
                $insert = $stmt->execute();		
	
	$amount = $amount * $currency_value_no;
	include 'includes/combine_account_exp.php';
	include 'includes/combine_account_monthly_exp.php';
	include 'includes/update_exp.php';
	}

$location = "point=".str_replace(" " , "_",$point_value)."&route_name=".str_replace(" " , "_",$route_name)."&trip_date=$trip_date&bus=$busname&main_route=".str_replace(" " , "_",$main_route)."&current_route=".str_replace(" " , "_",$current_route)."&row_num=".$row_num."&end_point=".$end_point."";
echo $location;
?>