<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$code = $_POST['code'];
$insert=mysqli_query($db,"DELETE from parcel_info WHERE code = '$code'");

?>