<?php
include 'connect.php';
$vehicle = $_POST['vehicle'];
$date = $_POST['date'];
$password = $_POST['password'];

$select2=mysqli_query($db,"SELECT * FROM  vehicle_name WHERE Name='$vehicle' and date='$date' and passcode='$password'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
echo "found";
}else{
	echo "$vehicle . $date . $password";
}
?>