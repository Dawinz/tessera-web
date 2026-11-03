<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';

$starting_point = $_POST['starting_point'];
$end_point = $_POST['end_point'];
$currency_main = $_POST['currency'];
$contact_1_update = $_POST['contact_1_update'];
$contact_2_update = $_POST['contact_2_update'];
$address_1_update = $_POST['address_1_update'];
$address_2_update = $_POST['address_2_update'];
$terms_1_update = $_POST['terms_1_update'];
$terms_2_update = $_POST['terms_2_update'];
$terms_1_update=base64_encode($terms_1_update);
$terms_2_update=base64_encode($terms_2_update);


$initial_route = $_POST['initial'];
$initial_route=base64_encode($initial_route);
$id_no = $_POST['id_no'];
$r_initial_update = $_POST['r_initial_update'];
$route_full_name = '$starting_point to $end_point';
include 'connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  routes WHERE id='$id_no' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $currency_sub =$get13['currency']; 
			$route_id =$get13['route_id'];
			$og_route_name =$get13['route_full_name'];
	

	}
}
$select14=mysqli_query($db,"SELECT *  FROM  vehicle_name  WHERE Route='$og_route_name' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){


$select146=mysqli_query($db,"SELECT *  FROM  parcel_info   WHERE account='$og_route_name' ");
$select_num146=mysqli_num_rows($select146);
if($select_num146 !=0){
$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency_main'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
  $initial= $get1373['initial']; 


}}




$update=mysqli_query($db,"UPDATE  routes SET currency='$currency_main',start_route='$starting_point',end_route='$end_point',currency_symbol='$initial',route_name='$initial_route',initial='$r_initial_update',route_full_name='$starting_point to $end_point',contact_1='$contact_1_update',contact_2='$contact_2_update',address_1='$address_1_update',address_2='$address_2_update',term_1_new1='$terms_1_update',term_2_new='$terms_2_update' WHERE id='$id_no'");
$update=mysqli_query($db,"UPDATE  assigned_routes SET currency='$currency_main',currency_symbol='$initial' WHERE route_id='$route_id'");
echo "succesfull";

}else{
echo "succesfull";
		$update=mysqli_query($db,"UPDATE  routes SET contact_1='$contact_1_update',contact_2='$contact_2_update',address_1='$address_1_update',address_2='$address_2_update',term_1_new1='$terms_1_update',term_2_new='$terms_2_update' WHERE id='$id_no'");

}



}else{
	echo "succesfull";
	$update=mysqli_query($db,"UPDATE  routes SET contact_1='$contact_1_update',contact_2='$contact_2_update',address_1='$address_1_update',address_2='$address_2_update',term_1_new1='$terms_1_update',term_2_new='$terms_2_update' WHERE id='$id_no'");
}




 
  ?>
