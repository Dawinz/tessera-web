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

$id = $_POST['id'];

  


   $insert=mysqli_query($db,"UPDATE trip_expens SET detail='$textareaValue',trips='$trip',date='$date',amount='$amount',assign_trip='$trip_assign'  WHERE id ='$id'");

?>