<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

require_once __DIR__ . '/includes/transfer_direct_visit.php';
if (tessera_transfer_direct_visit('Transfer multiple')) {
	return;
}
session_start();
$username=$_SESSION['username'];
$route_day_id ="";
$route_id='';
$trip_date='';
?>

<?php
$new_bus = $_POST['bus'];
$seat_new = $_POST['new_seat_no'];
$route_id_new = $_POST['route_id'];
$trip_date_new = $_POST['trip_date'];
$route_day_new = $_POST['rout_day_id'];
$prev_id = $_POST['final_new_total_transferd_operator_new'];

$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());

include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
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
 
 
}}else{
	echo "no";
}
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$new_bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $driver= $get13356['driver'];
 $conductor= $get13356['conductor'];

 

}}

$select_manifest=mysqli_query($db,"SELECT *  FROM  manifest  WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
   	WHILE($get_manifest=mysqli_fetch_array($select_manifest)){
$amount_manifest= $get_manifest['amount'];
 $seats_manifest= $get_manifest['seats']; 


}}

$select135=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id_new'");
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



$select_manifest_current=mysqli_query($db,"SELECT *  FROM  manifest  WHERE route_day_id='$route_day_new' and route_id='$route_id_new' and trip_date='$trip_date_new' ");
$select_num_manifest_current=mysqli_num_rows($select_manifest_current);
if($select_num_manifest_current !=0){
WHILE($get_manifest_current=mysqli_fetch_array($select_manifest_current)){
$amount_manifest_current= $get_manifest_current['amount']; 
$seats_manifest_current= $get_manifest_current['seats']; 


}}

$select_route_day=mysqli_query($db,"SELECT *  FROM  route_day WHERE route_id='$route_id_new'");
$select_num_route_day=mysqli_num_rows($select_route_day);
if($select_num_route_day !=0){
   	WHILE($get_route_day=mysqli_fetch_array($select_route_day)){
 


 $hours= $get_route_day['12hours'];

	

}}



$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date_new' and bus='$new_bus'   ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
	
	
		$insert=mysqli_query($db,"UPDATE  booking SET 
		trip_date='$trip_date_new',
		bus='$new_bus',
		route_id='$route_id_new',
		seat_no='$seat_new',
		route_day_id='$route_day_new'

		WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");
	

$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
('$new_bus','$trip_date_new','$date_insert','1','$cost','$start_route','$end_route','$hours','$route_day_new','$day','$month','$year','$driver','$conductor','$route_id_new','$route_name')");
	
$insert=mysqli_query($db,"INSERT INTO transit (ticket_id,old_route_day_id,old_route_id,old_trip_date,new_route_day_id, new_route_id ,new_trip_date,old_seat,date_insert,time_insert,user,status) VALUES
('$prev_id','$route_day_id','$route_id','$trip_date','$route_day_new','$route_id_new','$trip_date_new','$seat_no','$date_insert','$date_time_insert','".$_SESSION['username']."','Transfer')");


		echo "$prev_id - $trip_date_new - $new_bus - $seat_new ";

}else{
	

		$insert=mysqli_query($db,"UPDATE  booking SET 
		trip_date='$trip_date_new',
		bus='$new_bus',
		route_id='$route_id_new',
		seat_no='$seat_new',
		route_day_id='$route_day_new'

		WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");



$insert=mysqli_query($db,"INSERT INTO transit (ticket_id,old_route_day_id,old_route_id,old_trip_date,new_route_day_id, new_route_id ,new_trip_date,old_seat,date_insert,time_insert,user,status) VALUES
('$prev_id','$route_day_id','$route_id','$trip_date','$route_day_new','$route_id_new','$trip_date_new','$seat_no','$date_insert','$date_time_insert','".$_SESSION['username']."','Transfer')");
$amount_manifest_current =  $amount_manifest_current+$cost;	
$seats_manifest_current = $seats_manifest_current + 1;
$insert=mysqli_query($db,"UPDATE  manifest SET amount='$amount_manifest_current',seats='$seats_manifest_current' WHERE route_day_id='$route_day_new' and route_id='$route_id_new' and trip_date='$trip_date_new'");

	echo "$prev_id - $trip_date_new - $new_bus - $seat_new- $trip_date_new - $route_id_new - $route_day_new";
}


mysqli_query($db,"DELETE from manifest WHERE bus = ''");


?>