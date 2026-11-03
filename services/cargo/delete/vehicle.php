<?php
include '../connect.php';

$id=$_POST['delete_id'];
$insert=mysqli_query($db,"DELETE from  vehicles  WHERE id = '$id'");


?>