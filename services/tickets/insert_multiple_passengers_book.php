<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();

include 'connect.php';
$tesseraTenantId = (int) tessera_current_tenant_id();
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date('h:i A', time());
$new_time = date("h:i A", strtotime('+5 hours'));
$booking_timer = date("h:i:s A", strtotime('+5 hours'));

$select18=mysqli_query($db,"SELECT *  FROM users WHERE username='".@$_SESSION['username']."'  ");
$select_num18=mysqli_num_rows($select18);

if($select_num18 !=0){

	$n = 15;
$print_id = bin2hex(random_bytes($n));



	for($count = 0; $count < count($_POST["firstname"]); $count++)
	{

$firstname=  $_POST["firstname"][$count];
$seat_id=  $_POST["seat_id"][$count];
$lastname=  $_POST["lastname"][$count];
$phone_no=  $_POST["phone_no"][$count];
$gender=  @$_POST["gender"][$count];
$age_group =  $_POST["age"][$count];
$verification =  @$_POST["verification"][$count];
$reson =  @$_POST["Foc_info"][$count];
$reson =  @$_POST["trans_info"][$count];
$start_location =  @$_POST["start_location"][$count];
$ending_location =  @$_POST["ending_location"][$count];
$starting_point =  @$_POST["starting_point"][$count];
$end_point =  @$_POST["end_point"][$count];
$route_day_id =  @$_POST["route_day_id"][$count];
$trip_date =  @$_POST["trip_date"][$count];
$route_id =  @$_POST["route_id"][$count];
$inter_phone =  @$_POST["inter_phone"][$count];
$female =  $_POST["female"][$count];
$male =  $_POST["male"][$count];
$start_location = @preg_replace('/_/',' ', $start_location);
$ending_location = @preg_replace('/_/',' ', $ending_location);
if($female !="") {
	$gender = $female;
}else if ($male !=""){
	$gender = $male;
}else{
	$gender = "";
}

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='".$_POST['seat_id'][$count]."' and trip_date='' and route_day_id='$route_day_id' and route_id ='$route_id')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
 }


if(isset($_POST['transit'][$count])){
$transit = $_POST['transit'][$count];	
}else{
	$transit = "";
}
if(isset($_POST['foc'][$count])){
	$foc = $_POST['foc'][$count];
}else{
		$foc = "";
}

$select13=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $route_id= $get133['route_id'];
 $hours= $get133['12hours'];
 $bus= $get133['bus'];

	

}}

		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$start_location' AND end_route='$ending_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
		$fare_price =$get134['fare_price']; 
  $route_name= $get134['route_name'];
  $currency= $get134['currency']; 

		
		
		
	
}
$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial= $get1373['initial'];  

}}

if($_POST['adnce_info'][$count]==""){
$adnce_info = 0;
$advance_status ="";
$ticket_amount_left = 0;

}else{
	$adnce_info =  @$_POST["adnce_info"][$count];
		$advance_status ="yes";
$ticket_amount_left = $fare_price-$adnce_info;


}


$insert=mysqli_query($db,"INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,route_day_id,day,month,year,issuer,timer,
firstname,lastname,phone_no,validation,cost,reason,start_location,ending_location,gender,age_group,initial,currency,
transit,booker,print_id,amount_left,amount_status,amount_paid,start_route,end_route,disable_loan,locked_status,tenant_id)
 VALUES ('$seat_id','$route_id','$bus','booked','$new_time','$date_time_insert','$date_insert','$trip_date','$route_day_id','$day','$month','$year','".$_SESSION['username']."','$date_insert $booking_timer',
'$firstname','$lastname','$phone_no','$verification','','$reson','$starting_point','$end_point','$gender','$age_group','$initial','$currency','$transit','".$_SESSION['username']."','',
'','','','$start_location','$ending_location','disabled','disabled','$tesseraTenantId'
 )");


}else{
	
	$select14=mysqli_query($db,"SELECT *  FROM  sub_route WHERE start_route='$start_location' AND end_route='$ending_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['main_route_no']; 
		$fare_price =$get134['fare_price']; 
  $route_name= $get134['route_name'];
  $currency= $get134['currency']; 

		
		
		
	
}
$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial= $get1373['initial'];  

}}

if($_POST['adnce_info'][$count]==""){
$adnce_info = 0;
$advance_status ="";
$ticket_amount_left = 0;

}else{
	$adnce_info =  @$_POST["adnce_info"][$count];
		$advance_status ="yes";
$ticket_amount_left = $fare_price-$adnce_info;


}


$insert=mysqli_query($db,"INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,route_day_id,day,month,year,issuer,timer,
firstname,lastname,phone_no,validation,cost,reason,start_location,ending_location,gender,age_group,initial,currency,
transit,booker,print_id,amount_left,amount_status,amount_paid,start_route,end_route,disable_loan,locked_status,tenant_id)
 VALUES ('$seat_id','$route_id','$bus','booked','$new_time','$date_time_insert','$date_insert','$trip_date','$route_day_id','$day','$month','$year','".$_SESSION['username']."','$date_insert $booking_timer',
'$firstname','$lastname','$phone_no','$verification','','$reson','$starting_point','$end_point','$gender','$age_group','$initial','$currency','$transit','".$_SESSION['username']."','',
'','','','$start_location','$ending_location','disabled','disabled','$tesseraTenantId'
 )");


}
}



	}


}else{
echo "log_out";
}

?>
