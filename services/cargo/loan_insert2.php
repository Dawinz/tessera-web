<?php
include 'connect.php';
$type= $_POST['type'];
   $insert=mysqli_query($db,"UPDATE loan SET status='not paid'  WHERE id ='$type'");

?>