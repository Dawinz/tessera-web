<?php
include '../connect/connect.php';

$id=$_POST['id'];



	$delete=mysqli_query($db,"DELETE from route_currency WHERE id = '$id'");


?>