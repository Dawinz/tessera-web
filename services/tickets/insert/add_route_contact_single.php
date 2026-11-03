<?php
include '../connect.php';



for($count = 0; $count < count($_POST["route_type"]); $count++)

	{
	$route_name_cont = $_POST['route_type'] [$count];	
	$name = $_POST['name'][$count];	
	$contact = $_POST['contact'][$count];	
	
$select=mysqli_query($db,"SELECT *  FROM  routes WHERE fullname='$route_name_cont'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
 

 $route_id= $get['route_id'];



}}


$insert=mysqli_query($db,"INSERT INTO route_contact (route_name,name,contact,route_id) 
VALUES  ('$route_name_cont','$name','$contact','$route_id')");


	}
	
	
	?>