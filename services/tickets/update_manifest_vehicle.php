<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$trip_date=$_POST['trip_date'];
$bus=$_POST['bus'];
$bus_prev=$_POST['bus_prev'];

$update=mysqli_query($db,"UPDATE  manifest SET bus='$bus' WHERE  trip_date='$trip_date' and bus='$bus_prev'");
$update=mysqli_query($db,"UPDATE  booking SET bus='$bus' WHERE " . tessera_sql_tenant_eq() . " AND trip_date='$trip_date' and bus='$bus_prev'");
echo "succesfull";
?>