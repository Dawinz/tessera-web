<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

require_once __DIR__ . '/includes/transfer_direct_visit.php';
if (tessera_transfer_direct_visit('Transfer all')) {
	return;
}
include 'connect.php';
session_start();
$username=$_SESSION['username'];
$route_day_id_old = $_POST['route_day_id_old'];
$route_id_old = $_POST['route_id_old'];
$trip_date_old = $_POST['trip_date_old'];

$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());


$route_day_id_new_one = $_POST['route_day_id_new_one'];
$route_id_new_one = $_POST['route_id_new_one'];
$trip_date_new_one = $_POST['trip_date_new_one'];

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_old' and route_day_id='$route_day_id_old' and trip_date='$trip_date_old')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
WHILE($get13=mysqli_fetch_array($select1)){
 $id= $get13['id'];
 $trip_date= $get13['trip_date']; 
 $route_id = $get13['route_id']; 
 $status = $get13['status'];  
 $bus = $get13['bus']; 
 $route_day = $get13['route_day'];
 $seat_no = $get13['seat_no']; 
 $route_day_id = $get13['route_day_id'];
 $issuer = $get13['issuer']; 
 
 $book_time = $get13['book_time']; 
 $book_date = $get13['book_date']; 
 $cost = $get13['cost']; 
 $exp_time = $get13['exp_time'];
 $firstname = $get13['firstname']; 
 $lastname = $get13['lastname']; 
 $phone_no = $get13['phone_no']; 
 $validation = $get13['validation']; 
 $start_location = $get13['start_location']; 
 $ending_location = $get13['ending_location']; 
 $reason = $get13['reason']; 
 $booker = $get13['booker']; 
 $gender = $get13['gender']; 
 $age_group = $get13['age_group']; 
 $international_cost = $get13['international_cost']; 
 $initial = $get13['initial']; 
 $currency = $get13['currency']; 
 $transit = $get13['transit']; 
 $origin_status = $get13['origin_status']; 
 $select12=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (seat_no = '$seat_no' and route_id='$route_id_new_one' and route_day_id='$route_day_id_new_one' and trip_date='$trip_date_new_one')");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){
	$select13=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id_new_one'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $new_bus= $get133['bus'];
 $hours= $get133['12hours'];
	

}}

$select135=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id_new_one'");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 

 $start_route= $get1335['start_route'];
 $end_route= $get1335['end_route'];
 $fare_price= $get1335['fare_price'];
  $currency= $get1335['currency']; 
 $currency_symbol= $get1335['currency_symbol'];
  $route_name= $get1335['route_name'];
 

}}

$insert=mysqli_query($db,"UPDATE  booking SET 
		trip_date='$trip_date_new_one',
		bus='$new_bus',
		route_id='$route_id_new_one',
		seat_no='$seat_no',
		route_day_id='$route_day_id_new_one',
		cost='$fare_price'

		WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");





}else{
	echo "$seat_no,";
	
}
 
 
 
 $insert=mysqli_query($db,"INSERT INTO transit (ticket_id,old_route_day_id,old_route_id,old_trip_date,new_route_day_id, new_route_id ,new_trip_date,old_seat,date_insert,time_insert,user,status) VALUES
('$id','$route_day_id','$route_id','$trip_date','$route_day_id_new_one','$route_id_new_one','$trip_date_new_one','$seat_no','$date_insert','$date_time_insert','".$_SESSION['username']."','Transfer')");
 
 
 
}}

$select13=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id_new_one'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $new_bus= $get133['bus'];
 $hours= $get133['12hours'];
	

}}
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$new_bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $driver= $get13356['driver'];
 $conductor= $get13356['conductor'];

 

}}




$select15=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_new_one' and route_day_id='$route_day_id_new_one' and trip_date='$trip_date_new_one')");
$select_num15=mysqli_num_rows($select15);

$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE  route_day_id='$route_day_id_new_one' and route_id='$route_id_new_one' and trip_date='$trip_date_new_one'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){

$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_new_one' and route_day_id='$route_day_id_new_one' and trip_date='$trip_date_new_one')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_cost=$data['cost'];
		}}else{
			
		}
				
$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
('$new_bus','$trip_date_new_one','$trip_date_new_one','$select_num15','$total_cost','$start_route','$end_route','$hours','$route_day_id_new_one','$day','$month','$year','$driver','$conductor','$route_id_new_one','$route_name')");
	
	
}else{
	
$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_new_one' and route_day_id='$route_day_id_new_one' and trip_date='$trip_date_new_one')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_cost=$data['cost'];
		}}else{
			
		}
	$insert=mysqli_query($db,"UPDATE  manifest SET amount='$total_cost',seats='$select_num15' WHERE route_day_id='$route_day_id_new_one' and route_id='$route_id_new_one' and trip_date='$trip_date_new_one'");
	
}

$select157=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_old' and route_day_id='$route_day_id_old' and trip_date='$trip_date_old')");
$select_num157=mysqli_num_rows($select157);

$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (status='complete' and route_id='$route_id_old' and route_day_id='$route_day_id_old' and trip_date='$trip_date_old')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_cost_old=$data['cost'];
		}}else{
			
		}
	$insert=mysqli_query($db,"UPDATE  manifest SET amount='$total_cost_old',seats='$select_num157' WHERE route_day_id='$route_day_id_old' and route_id='$route_id_old' and trip_date='$trip_date_old'");
		mysqli_query($db,"UPDATE  manifest SET delete_book='deleted' WHERE  id = '$id' ");
mysqli_query($db,"DELETE from manifest WHERE bus = ''");

?>