<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id = $_POST['id'];
$route_id = $_POST['route_id'];
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND route_id='$route_id'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
$insert=mysqli_query($db,"DELETE from routes WHERE id = '$id'");
$insert=mysqli_query($db,"DELETE from assigned_routes WHERE route_id = '$route_id'");
}else{
		echo "cant";

}
?>