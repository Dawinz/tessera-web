<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$vehicle_new=$_POST['vehicle'];
$bus_route_prev=$_POST['bus_route'];
$trip_date_change=$_POST['trip_date_change'];
$idRoute=$_POST['idRoute'];

$update=mysqli_query($db,"UPDATE  route_day SET bus='$vehicle_new' WHERE id='$idRoute'");
$update=mysqli_query($db,"UPDATE  manifest SET bus='$vehicle_new' WHERE  bus='$bus_route_prev' and trip_date='$trip_date_change'");



?>