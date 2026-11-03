<?php
include '../connect.php';
$id = $_POST['id_no'];
$vehicle = $_POST['vehicle'];
$driver = $_POST['driver'];
$conductor = $_POST['conductor'];

$insert=mysqli_query($db,"UPDATE vehicles SET 
name='$vehicle',
driver='$driver',
conductor='$conductor'
 WHERE  id='$id' ");


?>