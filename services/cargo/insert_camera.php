<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$subject = $_POST['subject'];
$situation = $_POST['situation'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('h:i A', time());
$date = date('Y-m-d ');
	  $insert=mysqli_query($db,"INSERT INTO camera_report VALUES('','$subject','$situation','$detail','$date_insert','$date')");
echo $subject;
?>