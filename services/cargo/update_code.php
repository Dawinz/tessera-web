<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$code = $_POST['code'];
include 'connect.php';
      $insert=mysqli_query($db,"UPDATE parcel_info SET updated='updated'WHERE code ='$code' ");


?>