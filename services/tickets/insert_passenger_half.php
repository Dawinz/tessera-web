<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
$username=$_SESSION['username'];
$firstname = $_POST['firstname_half'];
$lastname = $_POST['lastname_half'];
$phone_no = $_POST['phone_no_half'];
$verification = $_POST['verification_half'];
$internatinal_cost=0;
$starting_point = $_POST['starting_point_half'];
$end_point = $_POST['end_point_half'];

$age_group_half = $_POST['age_group_half'];
$gender_half = $_POST['gender_half'];
$reason_half = $_POST['reason_half'];
$focus_half = $_POST['focus_half'];
$trans_half = $_POST['trans_half'];

$seat_id = $_POST['seat_id'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());


include 'connect.php';



$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $trip_date= $get13['trip_date']; 
 $bus= $get13['bus'];
 $cost= $get13['cost'];
 $route_id= $get13['route_id']; 
 $seat_no= $get13['seat_no']; 
 $route_day_id= $get13['route_day_id'];


}}	


$select135=mysqli_query($db,"SELECT *  FROM  sub_route WHERE start_route='$starting_point' and end_route='$end_point'");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 

 $fare_price= $get1335['fare_price'];
  $currency= $get1335['currency']; 
 $currency_symbol= $get1335['currency_symbol'];
  $route_name= $get1335['route_name'];

}}

if(($focus_half=="checked") OR ($trans_half=="checked")){
	$fare_price=0;
}

$select13=mysqli_query($db,"SELECT *  FROM  route_day WHERE route_id='$route_id'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $route_id= $get133['route_id'];
 $hours= $get133['12hours'];

	

}}
$select135=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 

 $start_route= $get1335['start_route'];
 $end_route= $get1335['end_route'];
$route_name= $get1335['route_name'];

}}
$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial= $get1373['initial'];  
 $status= $get1373['status']; 

}}

if($status !="Main"){
	$internatinal_cost=$fare_price;
$fare_price=$fare_price*$rate;	

}else{



}
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $driver= $get13356['driver'];
 $conductor= $get13356['conductor'];

 

}}
/////// checking for half rout or complete route/////////

$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date' and bus='$bus'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 == 0){
if($end_point == $end_route){
	
$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',status='half_complete',start_location='$starting_point',ending_location='$end_point',cost='$fare_price',reason='$reason_half',booker='".$_SESSION['username']."',origin_status='half_complete',gender='$gender_half',age_group='$age_group_half',international_cost='$internatinal_cost',initial='$initial',currency='$currency',validation='$verification',book_time='$date_time_insert',book_date='$date_insert',transit='$trans_half' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
}else{
$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',status='half_complete',start_location='$starting_point',ending_location='$end_point',cost='$fare_price',reason='$reason_half',booker='".$_SESSION['username']."',origin_status='half_complete',gender='$gender_half',age_group='$age_group_half',international_cost='$internatinal_cost',initial='$initial',currency='$currency',validation='$verification',book_time='$date_time_insert',book_date='$date_insert',transit='$trans_half' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");

}
$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,route_id,driver,conductor,route_id,route_name) VALUES
 ('$bus','$trip_date','$date_insert','1','$fare_price','$start_route','$end_route','$hours','$route_day_id','$day','$month','$year','$route_id','$driver','$conductor','$route_id','$route_name')");

$select1353=mysqli_query($db,"SELECT *  FROM  sub_route WHERE main_route_no='$route_id'");
$select_num1353=mysqli_num_rows($select1353);
if($select_num1353 !=0){
   	WHILE($get13353=mysqli_fetch_array($select1353)){
 $start_route= $get13353['start_route']; 
 $end_route= $get13353['end_route'];
 

}}


}else{
$select135=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date' and bus='$bus' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 $amount= $get1335['amount'];
 $seats= $get1335['seats'];



}}


$select15=mysqli_query($db,"SELECT *  FROM  booking  WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' and bus='$bus' and seat_no='$seat_no' and start_location='$starting_point' and ending_location='$end_point')");
$select_num15=mysqli_num_rows($select15);
if($select_num15 == 1){
	$insert=mysqli_query($db,"DELETE from booking WHERE " . tessera_sql_tenant_eq() . " AND (id = '$seat_id')");
	echo "exist";
}else{
	




//////////select form booking///////
	$new_amount = $amount + $fare_price;
	$totla_seat = $seats+1;

	if($end_point == $end_route){
		
		
				$select158=mysqli_query($db,"SELECT *  FROM  booking  WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' and bus='$bus' and seat_no='$seat_no' and  ending_location='$end_point')");
$select_num158=mysqli_num_rows($select158);
if($select_num158 == 1){
		$insert=mysqli_query($db,"DELETE from booking WHERE " . tessera_sql_tenant_eq() . " AND (id = '$seat_id')");

	echo "exist_half";
}else{
	$insert=mysqli_query($db,"UPDATE  booking SET status='half_complete' WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' and bus='$bus' and seat_no='$seat_no')");
$insert=mysqli_query($db,"UPDATE  booking SET cost='$fare_price',firstname='$firstname',lastname='$lastname',phone_no='$phone_no',start_location='$starting_point',ending_location='$end_point',reason='$reason_half',origin_status='half_complete',booker='".$_SESSION['username']."',gender='$gender_half',age_group='$age_group_half',international_cost='$internatinal_cost',initial='$initial',currency='$currency',validation='$verification',book_time='$date_time_insert',book_date='$date_insert',transit='$trans_half' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
}
		

	}else{
		$insert=mysqli_query($db,"UPDATE  booking SET status='half_complete' WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' and bus='$bus' and seat_no='$seat_no')");
$insert=mysqli_query($db,"UPDATE  booking SET cost='$fare_price',firstname='$firstname',lastname='$lastname',phone_no='$phone_no',start_location='$starting_point',ending_location='$end_point',reason='$reason_half',origin_status='half_complete',booker='".$_SESSION['username']."',gender='$gender_half',age_group='$age_group_half',international_cost='$internatinal_cost',initial='$initial',currency='$currency',validation='$verification',book_time='$date_time_insert',book_date='$date_insert',transit='$trans_half' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
	}

$insert=mysqli_query($db,"UPDATE  manifest SET amount='$new_amount',seats='$totla_seat' WHERE trip_date='$trip_date' and bus='$bus'  ");


	
}
}
?>

