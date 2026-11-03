<?php
include '../connect/connect.php';

$update=mysqli_query($db,"UPDATE  job_card_entries  SET 
	date='".$_POST['insert_date']."',route_name='".$_POST['route_name']."'
	WHERE date = '".$_POST['first_date']."' and route_name = '".$_POST['route_name']."' ");	
	
	$update=mysqli_query($db,"UPDATE  job_card  SET 
    date='".$_POST['insert_date']."',route_name='".$_POST['route_name']."'
	WHERE date = '".$_POST['first_date']."' and route_name = '".$_POST['route_name']."' ");	
?>