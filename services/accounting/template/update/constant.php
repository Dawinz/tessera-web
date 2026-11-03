<?php
include '../connect/connect.php';
$update=mysqli_query($db,"UPDATE  constants  SET 
	name='".$_POST['particular_name_update']."',
	route_name='".$_POST['choose_route_update']."',
	price_value='".preg_replace('/[^0-9.]+/', '', $_POST['amount'])."',
	route_category='".$_POST['route_category_update']."',
	name_category='".$_POST['part_Category_updat']."',
	vehicle='".$_POST['vehicle_update']."',
	currency='".$_POST['currency_v_update'] ."',
	affect='".$_POST['affect_update']."'
	
	WHERE id='".$_POST['id']."' ");	

?>