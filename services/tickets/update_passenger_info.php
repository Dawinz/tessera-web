<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$username=$_SESSION['username'];


$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone_no = $_POST['phone_no'];
$reason = $_POST['reason'];
$gender = $_POST['gender'];
$age_group = $_POST['age_group'];
$foc = $_POST['foc'];
$trip_date = $_POST['trip_date'];
$route_id  = $_POST['route_id'];
$verif  = $_POST['verif'];

$validation = "";
$starting_point = $_POST['starting_point'];
$end_point = $_POST['end_point'];

$seat_id = $_POST['seat_id'];


date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());

include 'connect.php';


$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
$route_day_id =$get13['route_day_id']; 

}}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	  
		$fare_price =$get134['fare_price']; 
	
}
}



 $date1=date_create($trip_date);
$date2=date_create($date_insert);
$diff=date_diff($date2,$date1);
$date_left =  $diff->format("%R%a");

if($date_left < 0){
			
echo "past";
}else{


if($foc =="checked"){
$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',start_location='$starting_point',ending_location='$end_point',cost='',reason='$reason',updater='".$_SESSION['username']."',gender='$gender',age_group='$age_group',foc='on',validation='$verif' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
	
}else{
	$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',start_location='$starting_point',ending_location='$end_point',reason='$reason',updater='".$_SESSION['username']."',gender='$gender',age_group='$age_group',cost='$fare_price',reason='',validation='$verif' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");

}
}








?>