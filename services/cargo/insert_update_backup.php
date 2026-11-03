<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id = $_POST['id'];

$select=mysqli_query($db,"SELECT *  FROM manifest WHERE id2 ='$id'");
$select_num=mysqli_num_rows($select);

if($select_num ==0){
$insert=mysqli_query($db,"INSERT INTO manifest (code,username,amount,trip_date,bus,paid,to_be,status,uploader,q_left,quantity,detail,last_left,parcel_uploader,checked,timetaken,username_taken,updated,id2) 
VALUES ('".$_POST['code']."','".$_POST['username']."','".$_POST['amount']."','".$_POST['trip_date']."','".$_POST['bus']."','".$_POST['paid']."',
'".$_POST['to_be']."','".$_POST['status']."','".$_POST['uploader']."','".$_POST['q_left']."','".$_POST['quantity']."','".$_POST['detail']."','".$_POST['last_left']."',
'".$_POST['parcel_uploader']."','".$_POST['checked']."','".$_POST['timetaken']."',
'".$_POST['username_taken']."','updated','".$_POST['id']."')");
echo $_POST['detail'];	
}else{
	
}


?>