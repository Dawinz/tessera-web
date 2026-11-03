<?php
include '../connect.php';
$select_first=mysqli_query($db,"SELECT *  FROM  users ");
$select_num_first=mysqli_num_rows($select_first);
if($select_num_first !=0){
   	WHILE($get_first=mysqli_fetch_array($select_first)){
 
$username= $get_first['username']; 
$role= $get_first['role']; 

$insert=mysqli_query($db,"DELETE from roles_assigned WHERE username = '$username'");



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



}}




?>