<?php
session_start();
?>
<?php
include 'connect.php';
$id = $_POST['id'];

$insert2=mysqli_query($db,"UPDATE parcel_info SET open=''  WHERE id = '$id'");
 $insert=mysqli_query($db,"DELETE from id WHERE id = '$id'");
 echo $id;
?>