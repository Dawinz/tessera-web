<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$date = $_POST['date'];
$emp_id = $_POST['emp_id'];
$id = $_POST['id'];
$status= $_POST['status'];

$arr = explode("/", $date, 2);
$first = $arr[0];
$second = substr($date, strpos($date, "/") + 1);    

echo "$first $second";
   $insert=mysqli_query($db,"UPDATE m_roll SET `$first`='' WHERE date ='$second' and em_id='$emp_id' and `$first`='$status'");
$insert=mysqli_query($db,"DELETE from deploy WHERE id = '$id'");

?>