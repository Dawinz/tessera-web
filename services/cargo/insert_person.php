<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$name = $_POST['name'];
$passowrd = $_POST['passowrd'];
$fullname = $_POST['fullname'];
$status_em = $_POST['status_em'];
$location = $_POST['location'];

 $insert=mysqli_query($db,"INSERT INTO users VALUES('','$name','$passowrd','$fullname','$status_em','$location')");
?>