<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$code = $_POST['code'];
$trip_date = $_POST['trip_date'];
$bus = $_POST['bus'];
$insert=mysqli_query($db,"DELETE from parcel_info WHERE code = '$code' and trip_date='$trip_date' and bus='$bus'");

?>