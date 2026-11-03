<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';

?>
<?php


session_start();

include 'connect.php';
$check = $_POST['check'];
$id = $_POST['id'];
$route_id_assigned = $_POST['route_id_assigned'];
$username_names = $_POST['username_names'];

if($check =="checked"){
	$update=mysqli_query($db,"UPDATE  assigned_routes SET preference=''  WHERE id='$id'");
	
echo "$route_id_assigned $username_names $id $check";
	
}else{
$update=mysqli_query($db,"UPDATE  assigned_routes SET preference='1' WHERE id='$id'");

echo "$route_id_assigned $username_names $id $check";
}





?>