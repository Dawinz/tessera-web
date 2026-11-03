<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$username=$_SESSION['username'];
$starting_point = $_POST['starting_point'];
$end_point = $_POST['end_point'];
$fair = $_POST['fair'];
$fair  = preg_replace('/[^0-9.]+/', '', $fair);
$initial_sub = $_POST['initial'];
$detail = $_POST['details'];
$detail = base64_encode($detail);
$currency = $_POST['currency'];

$id_no = $_POST['id_no'];
$main_route = $_POST['main_route'];
$a_aft = $_POST['a_aft'];
$a_time = $_POST['a_time'];
$a_time = date('h:i A', strtotime($a_time));
$d_ptime = $_POST['d_ptime'];
$d_ptime = date('h:i A', strtotime($d_ptime));

$sub_details= "$starting_point to $end_point - $d_ptime";


include 'connect.php';


$select1=mysqli_query($db,"SELECT *  FROM  sub_route WHERE id='$id_no' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $currency_sub =$get13['currency'];
			
	

	}
}


$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial= $get1373['initial']; 

}}



$select1734=mysqli_query($db,"SELECT *  FROM  routes Where route_name='$main_route'");
$select_num1734=mysqli_num_rows($select1734);
if($select_num1734 !=0){
   	WHILE($get13734=mysqli_fetch_array($select1734)){
 
 $main_route_no= $get13734['route_id'];
 $route_name= $get13734['route_name']; 

}}


$route_currency =  $rate * $fair;
if($currency_sub==$currency){
$update=mysqli_query($db,"UPDATE  sub_route SET detail='$detail',main_route_no='$main_route_no',route_name='$main_route',start_route='$starting_point',end_route='$end_point',a_time='$a_time',d_time='$d_ptime',fare_price='$fair',name='$initial_sub',currency='$currency',active_day='$a_aft' WHERE id='$id_no'");
	
}else{
$update=mysqli_query($db,"UPDATE  sub_route SET detail='$detail',main_route_no='$main_route_no',route_name='$main_route',start_route='$starting_point',end_route='$end_point',a_time='$a_time',d_time='$d_ptime',fare_price='$fair',fare_price_international='$route_currency',name='$initial_sub',currency='$currency',currency_symbol='$initial',active_day='$a_aft' WHERE id='$id_no'");
	
}
$update=mysqli_query($db,"UPDATE  sub_route_points SET detail='$sub_details' WHERE start_location='$starting_point' and end_location='$end_point'");

echo"succesfull";
 
  ?>
