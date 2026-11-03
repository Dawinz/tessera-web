<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
include 'connect.php';
$select18=mysqli_query($db,"SELECT *  FROM users WHERE username='".@$_SESSION['username']."'  ");
$select_num18=mysqli_num_rows($select18);

if($select_num18 !=0){



$username=$_SESSION['username'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone_no = $_POST['phone_no'];
$reason = $_POST['reason'];
$gender = $_POST['gender'];
$tran = $_POST['tran'];
$age_group = $_POST['age_group'];
$verification = $_POST['verification'];
$internatinal_cost=0;
$validation = $_POST['verification'];
$starting_point = $_POST['starting_point'];
$end_point = $_POST['end_point'];
$foc = $_POST['foc'];
$starting_location = $_POST['starting_location'];
$ending_location = $_POST['ending_location'];
$seat_id = $_POST['seat_id'];
$sub_route_id ="";
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());


	


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




$select13=mysqli_query($db,"SELECT *  FROM  route_day WHERE route_id='$route_id'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $route_id= $get133['route_id'];
 $hours= $get133['12hours'];

	

}}

//////
$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$starting_location' AND end_route='$ending_location' ");
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

$status_booked='complete';

}else{
	
	
			$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE start_route='$starting_location' AND end_route='$ending_location' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $id =$get1345['id'];
	
			$route_id = $get1345['main_route_no']; 
		$fare_price =$get1345['fare_price']; 
  $route_name= $get1345['route_name'];
  $currency= $get1345['currency']; 
  $sub_route_id= $get1345['sub_route_id']; 

		echo "$fare_price";
		
		
	
}}
$status_booked='half_complete';
	
		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];


		
		
		
	
}}
}
/////


//////
if(($foc =="checked") or($tran =="checked")){
	$fare_price=0;
}
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $driver= $get13356['driver'];
 $conductor= $get13356['conductor'];

 

}}


$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial= $get1373['initial'];  
 $status= $get1373['status']; 

}}




	



$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date' and bus='$bus'   ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
			$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',status='$status_booked',start_location='$starting_point',ending_location='$end_point',cost='$fare_price',reason='$reason',booker='".$_SESSION['username']."',gender='$gender',age_group='$age_group',international_cost='$internatinal_cost',initial='$initial',currency='$currency',book_time='$date_time_insert',transit='$tran',sub_route_id='$sub_route_id' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");





	$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
 ('$bus','$trip_date','$date_insert','1','$fare_price','$start_route','$end_route','$hours','$route_day_id','$day','$month','$year','$driver','$conductor','$route_id','$route_name')");


echo "first";
}else{
$select135=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date' and bus='$bus' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 $amount= $get1335['amount'];
 $seats= $get1335['seats'];



}}


	$new_amount = $amount+$fare_price;
	$totla_seat = $seats+1;
	
			$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',status='$status_booked',cost='$fare_price',reason='$reason',start_location='$starting_point',ending_location='$end_point',booker='".$_SESSION['username']."',gender='$gender',age_group='$age_group',international_cost='$internatinal_cost',initial='$initial',currency='$currency',book_time='$date_time_insert',transit='$tran',sub_route_id='$sub_route_id'  WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
			$insert=mysqli_query($db,"UPDATE  manifest SET amount='$new_amount',seats='$totla_seat' WHERE trip_date='$trip_date' and bus='$bus'  ");
	

	

}



	$insert=mysqli_query($db,"DELETE from printing WHERE attribute='$username'");

}else{
	echo "log_out";
}


?>
   