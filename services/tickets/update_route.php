<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';

$starting_point = $_POST['starting_point'];
$end_point = $_POST['end_point'];
$currency_main = $_POST['currency'];
$fair = $_POST['fair'];
$fair  = preg_replace('/[^0-9.]+/', '', $fair);
$initial_route = $_POST['initial'];
$detail = $_POST['details'];
$detail = base64_encode($detail);
$id_no = $_POST['id_no'];
$active_day = $_POST['active_day'];
$contact1_update = $_POST['contact1_update'];
$contact2_update = $_POST['contact2_update'];
include 'connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  routes WHERE id='$id_no' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $currency_sub =$get13['currency']; 
			$route_id =$get13['route_id'];
			
	

	}
}


$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency_main'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
  $initial= $get1373['initial']; 


}}




$route_currency =  $rate * $fair;
if($currency_sub==$currency_main){

$update=mysqli_query($db,"UPDATE  routes SET start_route='$starting_point',end_route='$end_point',fare_price='$fair',detail='$detail',currency='$currency_main',route_name='$initial_route',active_date='$active_day',contact_no1='$contact1_update',contact_no2='$contact2_update' WHERE id='$id_no'");
$update=mysqli_query($db,"UPDATE  assigned_routes SET route_name='$initial_route' WHERE route_id='$route_id'");

}else{
$update=mysqli_query($db,"UPDATE  routes SET start_route='$starting_point',end_route='$end_point',fare_price='$fair',fare_price_international='$route_currency',detail='$detail',currency='$currency_main',currency_symbol='$initial',route_name='$initial_route',active_date='$active_day',contact_no1='$contact1_update',contact_no2='$contact2_update' WHERE id='$id_no'");
$update=mysqli_query($db,"UPDATE  assigned_routes SET fare_price='$fair',fare_price_international='$route_currency',currency='$currency_main',currency_symbol='$initial',route_name='$initial_route' WHERE route_id='$route_id'");
}
echo "succesfull";


 
  ?>
