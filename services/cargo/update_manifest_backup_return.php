<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$id = $_POST['id'];
include 'connect.php';
      $insert=mysqli_query($db,"UPDATE manifest_deleted SET updated='updated'WHERE id2 ='$id' ");


?>