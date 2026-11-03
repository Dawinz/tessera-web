<?php
session_start();
$username_session=$_SESSION['username'];
include 'connect.php';
$id = $_POST['id'];



$select1=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' and status='active' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	
$update=mysqli_query($db,"UPDATE  route_day SET status='Not Active',button_name='Activate',button_icon='uil uil-check me-2',button_class='btn btn-soft-success' WHERE id='$id'");

}else{
$update=mysqli_query($db,"UPDATE  route_day SET status='active',button_name='De activate',button_icon='uil uil-exclamation-octagon me-2',button_class='btn btn-soft-danger' WHERE id='$id'");

}
?>


