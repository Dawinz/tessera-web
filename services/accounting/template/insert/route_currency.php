<?php
include '../connect/connect.php';

$route = $_POST['route'];
$route_currency = $_POST['route_currency'];
$status = $_POST['status'];


$select_currency=mysqli_query($db,"SELECT *  FROM route_currency    where  route_name='$route' and Currency='$route_currency' and status ='$status'");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency ==0){

	$sqlQ = "INSERT INTO  route_currency  (Currency,route_name,status) VALUES (?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sss", $route_currency, $route,$status); 
                $insert = $stmt->execute();	
}else{
	echo "found";
}
?>