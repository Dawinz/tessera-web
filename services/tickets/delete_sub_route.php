<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id = $_POST['id'];

$insert=mysqli_query($db,"DELETE from sub_route WHERE id = '$id'");

?>