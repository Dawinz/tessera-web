<?php
$user_name = $_POST["name"];
$user_pass = $_POST["email"];
$host = 'localhost';
$user_name = 'root';
$password = 'Teccross@360';
$db_name = 'sr';
$con = mysqli_connect($host,$user_name,$password,$db_name);
$sql = "insert into user_db value('','$user_name','$user_pass')";

if(mysqli_query($con,$sql)){
	echo "inserted";
}else{
	echo "error";
}
mysqli_close($con);




?>