<?php
$id = $_POST['id'];
include '../connect.php';


$insert=mysqli_query($db,"DELETE from contact_code  WHERE id = '$id'");

?>