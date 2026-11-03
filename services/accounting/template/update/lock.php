<?php
include '../connect/connect.php';

$locked_manifest = $_POST['locked_manifest'];
$bus = $_POST['bus'];
$trip_date = $_POST['trip_date'];

$icons = " typcn-lock-closed";
$update=mysqli_query($db,"UPDATE  manifest  SET 
	lock_icon='$icons',password='$locked_manifest'
	WHERE bus='$bus' and trip_date = '$trip_date'");	
	

?>