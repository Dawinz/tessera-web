<?php
include 'connect.php';
$code = $_POST['code'];
$insert=mysqli_query($db,"DELETE from parcel_info WHERE code = '$code'");
$insert=mysqli_query($db,"INSERT INTO parcel_info (date,question,account,s_no,s_name,s_surname,r_name,r_no,amount,due,date_insert,detail,code,status,time,uploader,username,bus,trip_date,paid,to_be,month,payment_status,rec_status,answer,store,edited,scanned,amount_remain) 
VALUES ('".$_POST['date']."','".$_POST['quantity']."','".$_POST['trip']."','".$_POST['s_no']."','".$_POST['s_name']."','".$_POST['s_surname']."',
'".$_POST['r_name']."','".$_POST['r_no']."','".$_POST['cost']."','".$_POST['due']."','".$_POST['date_insert']."','".$_POST['detail']."','".$_POST['code']."',
'".$_POST['status']."','".$_POST['time']."','".$_POST['uploader']."',
'".$_POST['username']."','".$_POST['bus']."','".$_POST['trip_date']."','".$_POST['paid']."','".$_POST['to_be']."','".$_POST['month']."',
'".$_POST['payment_status']."','".$_POST['rec_status']."','".$_POST['answer']."','".$_POST['store']."','".$_POST['edited']."','".$_POST['scanned']."','".$_POST['amount_remain']."')");
echo $_POST['detail'];
?>