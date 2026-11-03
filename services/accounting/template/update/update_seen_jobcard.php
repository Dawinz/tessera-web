<?php
session_start();

include '../connect/connect.php';

$date = $_POST['date'];
$route_name = $_POST['route_name'];
$fullname=$_SESSION['fullname'];

			
$select_currency=mysqli_query($db,"SELECT analysed FROM   job_card  WHERE analysed='' and date = '$date' and route_name='$route_name'  ");
$select_num_currency=mysqli_num_rows($select_currency);

 if($select_num_currency == 1){
	$update=mysqli_query($db,"UPDATE  job_card  SET 
	analysed='checked',user_checked='$fullname'
	WHERE date = '$date' and route_name='$route_name' ");	
	echo "Analysed by $fullname";
 }else{
	 $update=mysqli_query($db,"UPDATE  job_card  SET 
	analysed=''
	WHERE date = '$date' and route_name='$route_name' ");	
	echo "Click if full analysed";
 }
 
?>