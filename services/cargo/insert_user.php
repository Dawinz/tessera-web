<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
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
 $branch= $get137['branch']; 
 
 if($branch=="yes"){
	$insert=mysqli_query($db,"INSERT INTO roles_assigned (name,level,file,username,category,arrangement,sub_url) VALUES 
('$name','$level','','$username','$category','$arrangement','yes')"); 
 }else{
	$insert=mysqli_query($db,"INSERT INTO roles_assigned (name,level,file,username,category,arrangement) VALUES 
('$name','$level','$file','$username','$category','$arrangement')"); 
 }
 


}}	



	
$insert=mysqli_query($db,"INSERT INTO users (username,password,firstname,surname,email,number,role,status,button_name,button_icon,button_class,image,fullname) VALUES 
('$username','$password_no','$fullname','$lastname','$email','$number','$role','active','De activate','uil uil-exclamation-octagon me-2','btn btn-soft-danger','user.png','$username ($fullname $lastname)')");
 
 
 echo "succesfull";
}else{
	echo "found";
	
}



?>