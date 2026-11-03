<?php
session_start();
include 'connect.php';
$username=$_SESSION['username'];
$day = $_POST['day'];
$date = $_POST['date'];
$route_id = $_POST['route_id'];
$route_day_id = $_POST['route_day_id'];


$select1=mysqli_query($db,"SELECT *  FROM  pin WHERE username='$username' and route_id='$route_id' and date='$date'  and day='$day'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	$insert=mysqli_query($db,"INSERT INTO pin (username,route_id,date,day,route_day_id) VALUES 
('$username','$route_id','$date','$day','$route_day_id')");
echo "succesfull";
}else{
	echo "found";
}




?>