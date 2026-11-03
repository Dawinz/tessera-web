<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$code =$_POST['code'];
   $insert=mysqli_query($db,"UPDATE parcel_info SET rec_status='yes',status ='paid',due='0' WHERE code ='$code'");

?>