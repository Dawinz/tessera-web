<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$role_update = $_POST['role_update'];
$fullname_update = $_POST['fullname_update'];
$lastname_update = $_POST['lastname_update'];
$username_update = $_POST['username_update'];
$password_update = $_POST['password_update'];
$phone_no_update = $_POST['phone_no_update'];
$email_update = $_POST['email_update'];
$id_no = $_POST['id_no'];

$select1=mysqli_query($db,"SELECT *  FROM  users WHERE id='$id_no'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $username= $get13['username']; 
 $role= $get13['role']; 



}}	

if($username!=$username_update){
$update=mysqli_query($db,"UPDATE  booking SET issuer='$username_update' WHERE " . tessera_sql_tenant_eq() . " AND issuer='$username' ");
$update=mysqli_query($db,"UPDATE  booking SET booker='$username_update' WHERE " . tessera_sql_tenant_eq() . " AND booker='$username'");

$update=mysqli_query($db,"UPDATE  roles_assigned SET username='$username_update' WHERE username='$username' ");
$update=mysqli_query($db,"UPDATE  assigned_routes SET username='$username_update' WHERE username='$username' ");
}
if ($role_update!=$role){
	$insert=mysqli_query($db,"DELETE from roles_assigned WHERE username = '$username'");

	$select17=mysqli_query($db,"SELECT *  FROM  roles WHERE level='$role_update'");
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



	}
$update=mysqli_query($db,"UPDATE  users SET role='$role_update',username='$username_update',password='$password_update',firstname='$fullname_update',surname='$lastname_update',number='$phone_no_update',email='$email_update' WHERE id='$id_no'");


?>