<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';



include 'connect.php';

$id = $_POST['id2'];
$check2 = $_POST['check2'];


if($check2=="on"){
	$update=mysqli_query($db,"UPDATE  assigned_routes SET status='receiver' WHERE id='$id'");

}else{
	$update=mysqli_query($db,"UPDATE  assigned_routes SET status='sender' WHERE id='$id'");

}
echo $check2;



?>