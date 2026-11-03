<?php
include 'connect.php';
$fullname = $_POST['fullname'];
$lastname = $_POST['lastname'];
$username = $_POST['username'];
$password_no = $_POST['password_no'];
$number = $_POST['number'];
$email = $_POST['email'];
$role = $_POST['role'];






$select1=mysqli_query($db,"SELECT *  FROM  users WHERE username='$username' or number='$number' or email='$email'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
$select17=mysqli_query($db,"SELECT *  FROM  roles WHERE level='$role'");
$select_num17=mysqli_num_rows($select17);
if($select_num17 !=0){
   	WHILE($get137=mysqli_fetch_array($select17)){
 
 $name= $get137['name']; 
 $level= $get137['level']; 
 $file= $get137['file']; 
 $category= $get137['category']; 
 $arrangement= $get137['arrangement']; 
 
$insert=mysqli_query($db,"INSERT INTO roles_assigned (name,level,file,username,category,arrangement) VALUES 
('$name','$level','$file','$username','$category','$arrangement')");

}}	
$select17=mysqli_query($db,"SELECT *  FROM  routes ");
$select_num17=mysqli_num_rows($select17);
if($select_num17 !=0){
   	WHILE($get137=mysqli_fetch_array($select17)){
 
 $start_route= $get137['start_route']; 
 $end_route= $get137['end_route']; 
 $fare_price= $get137['fare_price']; 
 $route_id= $get137['route_id']; 
 
$insert=mysqli_query($db,"INSERT INTO assigned_routes (route_id,start_route,end_route,username,fare_price,selected) VALUES 
('$route_id','$start_route','$end_route','$username','$fare_price','false')");

}}	

$update=mysqli_query($db,"UPDATE  assigned_routes SET active='active',active_tab='show active',selected='true' WHERE username='$username' ORDER BY ID LIMIT 1");
	
	
$insert=mysqli_query($db,"INSERT INTO users (username,password,firstname,surname,email,number,role,status,button_name,button_icon,button_class,image) VALUES 
('$username','$password_no','$fullname','$lastname','$email','$number','$role','active','De activate','uil uil-exclamation-octagon me-2','btn btn-soft-danger','user.png')");
 
 
 echo "succesfull";
}else{
	echo "found";
	
}



?>