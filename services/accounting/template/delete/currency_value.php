<?php
include '../connect/connect.php';

$id=$_POST['id'];



	$delete=mysqli_query($db,"DELETE from currency_value  WHERE id = '$id'");


?>