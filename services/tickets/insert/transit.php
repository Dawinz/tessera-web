<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';
 
session_start();
include '../connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date('h:i A', time());
$new_time = date("h:i A", strtotime('+1 hours'));
$booking_timer = date("h:i:s A", strtotime('+10 minute'));
$trip_start_tranit = $_POST['trip_start_tranit'];
$trip_end_tranit = $_POST['trip_end_tranit'];
$bus_t = $_POST['bus_t'];
$trip_date_t = $_POST['trip_date_t'];
$route_id_t = $_POST['route_id_t'];
$route_day_id_t = $_POST['route_day_id_t'];
$start_route_t = $_POST['start_route_t'];
$end_route_id_t = $_POST['end_route_id_t'];
$day = date('d', strtotime($trip_date_t));
$month = date('m', strtotime($trip_date_t));
$year = date('Y', strtotime($trip_date_t));
$n = 15;
$print_id = bin2hex(random_bytes($n));
$print_id = "$print_id$date_insert";
for($count = 0; $count < count($_POST["seat_id_trans"]); $count++)

	{
	$seat_id_trans = $_POST['seat_id_trans'] [$count];	
	$seat_id_no = $_POST['seat_id_no'][$count];	
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id_trans')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $trip_date= $get13['trip_date']; 
 $bus= $get13['bus'];
 $cost= $get13['cost'];
 $route_id= $get13['route_id']; 
 $seat_no= $get13['seat_no']; 
 $route_day_id= $get13['route_day_id'];

$start_location= $get13['start_location']; 
 $ending_location= $get13['ending_location'];
$firstname= $get13['firstname'];
$lastname= $get13['lastname'];
$phone_no= $get13['phone_no'];
$bus= $get13['bus'];
$route_day_id= $get13['route_day_id'];
$booker= $get13['booker'];
$issuer = $get13['issuer'];

$origin_status= $get13['status'];
$cost= $get13['cost'];
$international_cost= $get13['international_cost'];

$book_date= $get13['book_date'];
$book_time= $get13['book_time'];
$status2= $get13['status'];
$transit= $get13['transit'];
$sub_route_id= $get13['sub_route_id'];
$amount_status= $get13['amount_status'];
$amount_left= $get13['amount_left'];
$amount_paid= $get13['amount_paid'];
$foc= $get13['foc'];
$age_group= $get13['age_group'];
$gender= $get13['gender'];
$verification= $get13['validation'];
$initial= $get13['initial'];
$inter_number= $get13['inter_number'];
$start_route= $get13['start_route'];
$end_route= $get13['end_route'];
$reason= "$start_route to $end_route";

}}	

	$insert=mysqli_query($db,"INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,route_day_id,day,month,year,issuer,timer,
firstname,lastname,phone_no,validation,cost,reason,start_location,ending_location,gender,age_group,initial,currency,
transit,booker,print_id,amount_left,amount_status,amount_paid,inter_number,sub_route_id,start_route,end_route,book_status,disable_loan,foc,locked_status,last_id)
 VALUES ('$seat_id_no','$route_id_t','$bus_t','complete','$new_time','$date_time_insert','$date_insert','$trip_date_t','$route_day_id_t','$day','$month','$year','".$_SESSION['username']."','',
'$firstname','$lastname','$phone_no','$verification','0','$reason','$trip_start_tranit','$trip_end_tranit','$gender','$age_group','$initial','','on','".$_SESSION['username']."','$print_id',
'','','','$inter_number','','$start_route_t','$end_route_id_t','disabled','disabled','$foc','disabled','$seat_id_trans'
 )");
$insert=mysqli_query($db,"DELETE from transit_seat WHERE seat_id = '$seat_id_trans'");

	}
	echo $print_id;
	
?>