<?php
require_once __DIR__ . '/includes/tessera_tenant.php';
session_start();
$tesseraTenantId = (int) tessera_current_tenant_id();

if(isset($_SESSION['username'])){
$username=$_SESSION['username'];

include 'connect.php';
$bus=$_POST['bus'];
$seat_number=$_POST['seat_number'];
$route_id=$_POST['route_id'];
$trip_date=$_POST['trip_date'];
$cost=$_POST['cost'];
$rout_day_id=$_POST['rout_day_id'];




date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());
$new_time = date("h:i A", strtotime('+1 hours'));
$booking_timer = date("h:i:s A", strtotime('+10 minute'));
$select135=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date' and bus='$bus' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 ==0){
	
$insert=mysqli_query($db,"INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,cost,route_day_id,day,month,year,issuer,timer,tenant_id)
 VALUES ('$seat_number','$route_id','$bus','booked','$new_time','','$date_insert','$trip_date','','$rout_day_id','$day','$month','$year','".$_SESSION['username']."','$booking_timer','$tesseraTenantId')");	
 echo "first";
}else{
$insert=mysqli_query($db,"INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,cost,route_day_id,day,month,year,issuer,timer,tenant_id)
 VALUES ('$seat_number','$route_id','$bus','booked','$new_time','','$date_insert','$trip_date','','$rout_day_id','$day','$month','$year','".$_SESSION['username']."','$booking_timer','$tesseraTenantId')");	
}


}else{
echo "log_out";	
}

?>