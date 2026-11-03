<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$username=$_SESSION['username'];
$l_name = $_POST['l_name'];



include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  locations_names WHERE name='$l_name'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
		$insert=mysqli_query($db,"INSERT INTO locations_names (name) VALUES
 ('$l_name')");
	echo "succesfull";
   	}	else{

	}



 
  ?>
