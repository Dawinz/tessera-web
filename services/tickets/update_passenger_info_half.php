<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$username=$_SESSION['username'];

$firstname = $_POST['firstname_half'];
$lastname = $_POST['lastname_half'];
$phone_no = $_POST['phone_no_half'];
$reason = $_POST['reason_half'];
$gender = $_POST['gender_half'];
$age_group = $_POST['age_group_half'];
$focus_half = $_POST['focus_half'];

$validation = "";
$starting_point = $_POST['starting_point_half'];
$end_point = $_POST['end_point_half'];

$seat_id = $_POST['seat_id'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());

include 'connect.php';
if($focus_half=="checked"){
	$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',start_location='$starting_point',ending_location='$end_point',cost='',reason='$reason',updater='".$_SESSION['username']."',gender='$gender',age_group='$age_group' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");

}else{
	$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',start_location='$starting_point',ending_location='$end_point'reason='$reason',updater='".$_SESSION['username']."',gender='$gender',age_group='$age_group' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");

}

?>