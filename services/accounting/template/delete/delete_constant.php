<?php
include '../connect/connect.php';
$id = $_POST['id'];
$insert=mysqli_query($db,"DELETE from constants WHERE id = '$id'");
?>