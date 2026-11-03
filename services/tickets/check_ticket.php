<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

include 'connect.php';
$ticket_no = $_POST['ticket_no'];
$prev_id = $_POST['prev_id'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());

session_start();
$username=$_SESSION['username'];
$ticket_price = 150000;
$amount_manifest_current=0;
$amount_manifest=0;
$seats_manifest_current =0;

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$select_this=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$ticket_no')");
$select_num_this=mysqli_num_rows($select_this);
if($select_num_this ==0){
	echo "nfound";
}else{
	
 WHILE($get_this=mysqli_fetch_array($select_this)){
 
 $trip_date_this= $get_this['trip_date']; 
$route_id_this = $get_this['route_id']; 
$route_day_this = $get_this['route_day_id'];
 $seat_this = $get_this['seat_no']; 








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

 


 
 $date1=date_create($trip_date);
$date2=date_create($date_insert);
$diff=date_diff($date2,$date1);
$date_left =  $diff->format("%R%a");




 
if($date_left < 0){
			
echo "past";

}else{
	
	
$select_manifest=mysqli_query($db,"SELECT *  FROM  manifest  WHERE route_day_id='$route_day_this' and route_id='$route_id_this' and trip_date='$trip_date_this'");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
   	WHILE($get_manifest=mysqli_fetch_array($select_manifest)){
$amount_manifest= $get_manifest['amount'];
 $seats_manifest= $get_manifest['seats']; 


}}

$amount_manifest = $amount_manifest - $ticket_price;
$seats_manifest = $seats_manifest-1;


$select_manifest_current=mysqli_query($db,"SELECT *  FROM  manifest  WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date' ");
$select_num_manifest_current=mysqli_num_rows($select_manifest_current);
if($select_num_manifest_current !=0){
WHILE($get_manifest_current=mysqli_fetch_array($select_manifest_current)){
$amount_manifest_current= $get_manifest_current['amount']; 
$seats_manifest_current= $get_manifest_current['seats']; 


}}
$amount_manifest_current =  $amount_manifest_current+$ticket_price;	
$seats_manifest_current = $seats_manifest_current + 1;
	
	
	$insert=mysqli_query($db,"UPDATE  booking SET trip_date='$trip_date',bus='$bus',route_id='$route_id',seat_no='$seat_no',route_day_id='$route_day_id' WHERE " . tessera_sql_tenant_eq() . " AND (id='$ticket_no')");
 

$select_check_manifest=mysqli_query($db,"SELECT *  FROM  manifest   WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date' ");
$select_num_check_manifest=mysqli_num_rows($select_check_manifest);
if($select_num_check_manifest ==0){

$select_bus=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num_bus=mysqli_num_rows($select_bus);
if($select_num_bus !=0){
   	WHILE($get_bus=mysqli_fetch_array($select_bus)){
 

 $driver= $get_bus['driver'];
 $conductor= $get_bus['conductor'];

 

}}

$select_route_day=mysqli_query($db,"SELECT *  FROM  route_day WHERE route_id='$route_id'");
$select_num_route_day=mysqli_num_rows($select_route_day);
if($select_num_route_day !=0){
   	WHILE($get_route_day=mysqli_fetch_array($select_route_day)){
 


 $hours= $get_route_day['12hours'];

	

}}

$select_route_details=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'");
$select_num_route_details=mysqli_num_rows($select_route_details);
if($select_num_route_details !=0){
   	WHILE($get_route_details=mysqli_fetch_array($select_route_details)){
 

 $start_route= $get_route_details['start_route'];
 $end_route= $get_route_details['end_route'];
 $fare_price= $get_route_details['fare_price'];
  $currency= $get_route_details['currency']; 
 $currency_symbol= $get_route_details['currency_symbol']; 
 $route_name= $get_route_details['route_name'];
 

}}



$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
('$bus','$trip_date','$date_insert','1','$fare_price','$start_route','$end_route','$hours','$route_day_id','$day','$month','$year','$driver','$conductor','$route_id','$route_name')");
$insert=mysqli_query($db,"UPDATE  manifest SET amount='$amount_manifest',seats='$seats_manifest' WHERE route_day_id='$route_day_this' and route_id='$route_id_this' and trip_date='$trip_date_this'");
echo "check_ticket";
}else{
	
if(($trip_date==$trip_date_this) and ($route_id=="$route_id_this") and ($route_day_id== $route_day_this)){
	
}else{
$insert=mysqli_query($db,"UPDATE  manifest SET amount='$amount_manifest_current',seats='$seats_manifest_current' WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'");
$insert=mysqli_query($db,"UPDATE  manifest SET amount='$amount_manifest',seats='$seats_manifest' WHERE route_day_id='$route_day_this' and route_id='$route_id_this' and trip_date='$trip_date_this'");	
}
	

}
 
 
 $insert=mysqli_query($db,"INSERT INTO changes  (from_trip_date,to_trip_date,from_seat_number,to_trip_number,username,ticket_id,changed_time) VALUES
                                                ('$trip_date_this','$trip_date','$seat_this','$seat_no','$username','$ticket_no','$date_insert at $date_time_insert')");
 $insert=mysqli_query($db,"DELETE from booking WHERE " . tessera_sql_tenant_eq() . " AND (id = '$prev_id')");
}


}}else{
	echo "nfound";
}

}}
?>