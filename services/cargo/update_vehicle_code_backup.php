<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$Name = $_POST['Name'];
$date = $_POST['date'];
include 'connect.php';
      $insert=mysqli_query($db,"UPDATE vehicle_name SET updated='updated'WHERE Name ='$Name' and date='$date' ");


?>