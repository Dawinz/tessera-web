<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$username=$_SESSION['username'];
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';


$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $role =$get12['role'];  		
	}
}



date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('m/d/Y', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());
$deleted_time="$date_insert at $date_time_insert  ";

$id= $_POST['id'];
$reason= base64_encode($_POST['reason']);

$select135=mysqli_query($db,"SELECT *  FROM  booking WHERE  id = '$id' AND " . tessera_sql_tenant_eq() . "");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 $cost= $get1335['cost'];
 $trip_date= $get1335['trip_date'];
 $bus= $get1335['bus'];
 $issuer= $get1335['issuer']; 
 $status= $get1335['status'];
$seat_no= $get1335['seat_no'];
$firstname= $get1335['firstname'];
$lastname= $get1335['lastname'];
$route_id = $get1335['route_id'];
$phone_no = $get1335['phone_no'];
$book_date = $get1335['book_date'];
$book_time = $get1335['book_time'];
$locker = $get1335['locker'];


$seat_booked_date="$book_date at $book_time ";
$user_fullname= "$firstname $lastname";
}}
if($status=="booked"){
	


	$insert=mysqli_query($db,"DELETE from booking WHERE id = '$id' AND " . tessera_sql_tenant_eq() . "");
	$delete=mysqli_query($db,"INSERT INTO deleted  
(seat_id,seat_no,username,delete_date,ticket_user,ticket_deleter,status,location,phone_no,seat_insert_time,trip_date,reason) VALUES 
('$id','$seat_no','$issuer','$deleted_time','$user_fullname','".$_SESSION['username']."','deleted','','$phone_no','$seat_booked_date','$trip_date','$reason')");



}else{


if(($locker != $_SESSION['username'])&&($status == "locked")){
	echo "locked $locker";
}else{
	

 $date1=date_create($trip_date);
$date2=date_create($date_insert);
$diff=date_diff($date2,$date1);
$date_left =  $diff->format("%R%a");


if($date_left < 0){
			
echo "past";
}else{


$select1=mysqli_query($db,"SELECT *  FROM  assigned_routes  WHERE route_id='$route_id' and username='".$_SESSION['username']."' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $route_name =$get13['route_name']; 

}}

	

$select1356=mysqli_query($db,"SELECT *  FROM  manifest WHERE  bus = '$bus' and trip_date='$trip_date'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 $amount= $get13356['amount'];
 $canceled= $get13356['canceled'];
 $seats= $get13356['seats'];


}}
if(($role=="Administrator")and ($select_num1 !=0) OR ($role=="Super Administrator") ){
	$new_amount = $amount- $cost;
$canenled_1 = $canceled + 1;
$t_seats = $seats-1;
//$insert=mysqli_query($db,"DELETE from booking WHERE id = '$id' AND " . tessera_sql_tenant_eq() . "");

$update=mysqli_query($db,"UPDATE  booking SET delete_book='deleted' WHERE " . tessera_sql_tenant_eq() . " AND (id = '$id')");


	

	
if($status=="booked"){
$delete=mysqli_query($db,"INSERT INTO deleted  
(seat_id,seat_no,username,delete_date,ticket_user,ticket_deleter,status,location,phone_no,seat_insert_time,trip_date,reason) VALUES 
('$id','$seat_no','$issuer','$deleted_time','$user_fullname','".$_SESSION['username']."','deleted','$route_name','$phone_no','$seat_booked_date','$trip_date','$reason')");
		$insert=mysqli_query($db,"UPDATE  manifest SET amount='$new_amount',canceled='$canenled_1',seats='$t_seats' WHERE  bus = '$bus' and trip_date='$trip_date'");


}else{
		$insert=mysqli_query($db,"UPDATE  manifest SET amount='$new_amount',canceled='$canenled_1',seats='$t_seats' WHERE  bus = '$bus' and trip_date='$trip_date'");

$delete=mysqli_query($db,"INSERT INTO deleted  
(seat_id,seat_no,username,delete_date,ticket_user,ticket_deleter,status,location,phone_no,seat_insert_time,trip_date,reason) VALUES 
('$id','$seat_no','$issuer','$deleted_time','$user_fullname','".$_SESSION['username']."','deleted','$route_name','$phone_no','$seat_booked_date','$trip_date','$reason')");


		$update=mysqli_query($db,"UPDATE  booking SET delete_book='deleted' WHERE " . tessera_sql_tenant_eq() . " AND (id = '$id')");

}	


}else{
	echo "not";
}
}
}
}
?>