<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id = $_POST['id'];
$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name WHERE luggage_no='0' and id='$id'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 ==0){
	echo "yes";
}else{

	$insert=mysqli_query($db,"DELETE from vehicle_name WHERE id = '$id'");
echo "deleted";
}

?>