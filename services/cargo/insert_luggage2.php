<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config.php';

$trip = $_POST['trip'];
$trip_assign = $_POST['trip_assign'];
$textareaValue=$_POST['textareaValue'];
$textareaValue = base64_encode($textareaValue);
$amount=$_POST['amount'];

$date =$_POST['date'];
$dateStr = $_POST['dateStr'];


 $insert=mysqli_query($db,"INSERT INTO trip_expens VALUES('','$textareaValue','$trip','$date','$amount','$trip_assign','$dateStr')");

?>