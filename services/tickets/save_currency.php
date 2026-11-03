<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$currecy_name = $_POST['currecy_name'];
$Country = $_POST['Country'];
$fair = $_POST['fair'];
$fair  = preg_replace('/[^0-9.]+/', '', $fair);
$initial = $_POST['initial'];
$currency_status = $_POST['currency_status'];


if($fair == 0){
	$fair = 1;
}


if($currency_status=="Main"){


	echo "not found";
}else{
	$insert=mysqli_query($db,"INSERT INTO currency 
(name,status,rate,initial,country) VALUES 
('$currecy_name','$currency_status','$fair','$initial','($initial)$Country')");

echo "succesfull";

}




 
  ?>
