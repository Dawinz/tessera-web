<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$id = $_POST['id2'];
$code= $_POST['no'];
$date= $_POST['date2'];
	   $insert=mysqli_query($db,"UPDATE parcel_info SET code='$code',date_insert='$date' WHERE id ='$id'");
echo"$id $code";
?>