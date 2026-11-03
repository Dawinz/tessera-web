<?php
include '../connect/connect.php';

$id=$_POST['id'];
$route_name=$_POST['route_name'];


$select_currency=mysqli_query($db,"SELECT bus  FROM   vehicle_account   where route_name='$route_name'");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency ==0){
$delete=mysqli_query($db,"DELETE from route WHERE id = '$id'");
}else{
	 echo "found";

}


?>