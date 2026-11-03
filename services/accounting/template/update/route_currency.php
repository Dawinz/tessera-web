<?php

include '../connect/connect.php';

$route = $_POST['route_update'];
$route_currency = $_POST['route_currency_update'];
$status = $_POST['status_update'];
$id = $_POST['id_update'];


$select_currency=mysqli_query($db,"SELECT *  FROM route_currency    where  route_name='$route' and Currency='$route_currency' and status ='$status'");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency ==0){

$update=mysqli_query($db,"UPDATE  route_currency  SET 
	route_name='$route',Currency='$route_currency',status='$status'
	WHERE  id = '$id'");
}else{
	echo "found";
}
?>