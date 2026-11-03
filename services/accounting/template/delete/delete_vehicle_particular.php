<?php
include '../connect/connect.php';
$id = $_POST['id'];
$insert=mysqli_query($db,"DELETE from  vehicle_category  WHERE id = '$id'");
?>