<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
$username=$_SESSION['username'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone_no = $_POST['phone_no'];
$reason = $_POST['reason'];
$gender = $_POST['gender'];
$age_group = $_POST['age_group'];
$internatinal_cost=0;
$validation = $_POST['verification'];
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
$select135=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 

 $start_route= $get1335['start_route'];
 $end_route= $get1335['end_route'];
 $fare_price= $get1335['fare_price'];
  $currency= $get1335['currency']; 
 $currency_symbol= $get1335['currency_symbol'];
 

}}
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

if($status !="Main"){
	$internatinal_cost=$fare_price;
$fare_price=$fare_price*$rate;	

}else{
$fare_price= $get1335['fare_price'];	


}


$select17=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (seat_no='$seat_no' and route_id='$route_id' and bus='$bus' and trip_date='$trip_date' and status='half_complete')");
$select_num17=mysqli_num_rows($select17);
if($select_num17 !=0){
	echo "half";
}else{
	
	



$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date' and bus='$bus'   ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
			$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',status='complete',start_location='$starting_point',ending_location='$end_point',cost='$fare_price',reason='$reason',booker='".$_SESSION['username']."',gender='$gender',age_group='$age_group',international_cost='$internatinal_cost',initial='$initial',currency='$currency' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");

	$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id) VALUES
 ('$bus','$trip_date','$date_insert','1','$fare_price','$start_route','$end_route','$hours','$route_day_id','$day','$month','$year','$driver','$conductor','$route_id')");
}else{
$select135=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date' and bus='$bus' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 $amount= $get1335['amount'];
 $seats= $get1335['seats'];



}}

//////////select form booking///////
$select137=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id' and status='complete')");
$select_num137=mysqli_num_rows($select137);
if($select_num137 !=1){
	$new_amount = $amount+$fare_price;
	$totla_seat = $seats+1;
	
			$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',status='complete',cost='$fare_price',reason='$reason',start_location='$starting_point',ending_location='$end_point',booker='".$_SESSION['username']."',gender='$gender',age_group='$age_group',international_cost='$internatinal_cost',initial='$initial',currency='$currency'  WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
			$insert=mysqli_query($db,"UPDATE  manifest SET amount='$new_amount',seats='$totla_seat' WHERE trip_date='$trip_date' and bus='$bus'  ");
	
}else{
				$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',start_location='$starting_point',ending_location='$end_point',cost='$fare_price',reason='$reason',booker='".$_SESSION['username']."',gender='$gender',age_group='$age_group',international_cost='$internatinal_cost',initial='$initial',currency='$currency'  WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");

echo "updated";
}

	

}

}


	$insert=mysqli_query($db,"INSERT INTO printing (detail,attribute) VALUES
 ('$seat_id','".$_SESSION['username']."')");

?>
    <?php 
  include "qrcode/meRaviQr/qrlib.php";
  include "qrcode/config.php";

    $qc =  "https://217.29.139.126/print_ticket.php?id=$seat_id";
    $qrUname = "classic coach";
    $qrImgName = "meravi".rand();
    if($qc=="" && $qrUname=="")
    {
      echo "<script>alert('Please Enter Your Name And Msg For QR Code');</script>";
    }
    elseif($qc=="")
    {
      echo "<script>alert('Please Enter QR Code Msg');</script>";
    }
    elseif($qrUname=="")
    {
      echo "<script>alert('Please Enter Your Name');</script>";
    }
    else
    {
    $dev = " ...Develop By Teccross";
    $final = $qc.$dev;
    $qrs = QRcode::png($final,"qrcode/userQr/$qrImgName.png","H","3","3");
    $qrimage = $qrImgName.".png";
    $workDir = $_SERVER['HTTP_HOST'];
    $qrlink = $workDir."/qrcode".$qrImgName.".png";
    $insQr = $meravi->insertQr($qrUname,$final,$qrimage,$qrlink,$seat_id);
  
  }
 
  ?>
