<?php
include 'connect.php';
$account = $_POST['account'];
$amount = $_POST['amount'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date("m/d/Y");
$detail = $_POST['detail'];
$detail = base64_encode($detail);

   $insert=mysqli_query($db,"INSERT INTO expenses VALUES('','$account','$amount','$date_insert','$detail')");

?>