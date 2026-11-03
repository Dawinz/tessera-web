<?php
$id = $_POST['id'];
include '../connect.php';


$insert=mysqli_query($db,"DELETE from route_contact  WHERE id = '$id'");

?>