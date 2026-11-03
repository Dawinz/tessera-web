<?php
include '../connect/connect.php';
$id = $_POST['id'];
$file_name = $_POST['file_name'];
$insert=mysqli_query($db,"DELETE from attachment WHERE id = '$id'");

unlink("../upload/uploads/$file_name");

?>