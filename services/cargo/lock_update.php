<?php
session_start();
?>
<?php
include 'connect.php';
$id = $_POST['id'];
$password = $_POST['password'];


$insert2=mysqli_query($db,"UPDATE vehicle_name SET status='closed' ,locked_by='".$_SESSION['username']."',passcode='$password' WHERE id = '$id'");
echo "deleted";


?>