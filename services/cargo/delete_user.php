<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id = $_POST['id'];
$username = $_POST['username'];


			session_start();
$username_session=$_SESSION['username'];

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE username='$username'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	$insert=mysqli_query($db,"DELETE from users WHERE id = '$id'");
	$insert=mysqli_query($db,"DELETE from roles_assigned WHERE username = '$username'");
	$insert=mysqli_query($db,"DELETE from assigned_routes WHERE username = '$username'");
	if($username_session==$username){
		echo "redirect";
	}else{
		
	}
}else{
	echo "cant";
}
?>


