<?php
session_start();
$username_session=$_SESSION['username'];
include 'connect.php';
$id = $_POST['id'];
$username = $_POST['username'];


$select1=mysqli_query($db,"SELECT *  FROM  users WHERE id='$id' and status='active' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	
$update=mysqli_query($db,"UPDATE  users SET status='Not Active',button_name='Activate',button_icon='uil uil-check me-2',button_class='btn btn-soft-success' WHERE id='$id'");
	if($username_session==$username){
		echo "redirect";
	}else{
		
	}
}else{
$update=mysqli_query($db,"UPDATE  users SET status='active',button_name='De activate',button_icon='uil uil-exclamation-octagon me-2',button_class='btn btn-soft-danger' WHERE id='$id'");

}
?>


