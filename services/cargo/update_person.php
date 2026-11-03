<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$name = $_POST['name'];
$password = $_POST['passowrd'];
$status_em = $_POST['status_em'];
$fullname = $_POST['fullname'];
$id = $_POST['id'];

 if($password==""){
	   $insert=mysqli_query($db,"UPDATE users SET username='$name',level ='$status_em',fullname='$name',level='$status_em',fullname='$fullname' WHERE id ='$id'");
 
 }else{
	   $insert=mysqli_query($db,"UPDATE users SET username='$name',level ='$status_em',level='$status_em',fullname='$name',password='$password',fullname='$fullname' WHERE id ='$id'");
 
 }
?>