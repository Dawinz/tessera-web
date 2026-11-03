<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id_no = $_POST['id_no'];
$conductor_update = $_POST['conductor_update'];
$drivers_name = $_POST['drivers_name'];
$vehicle = $_POST['vehicle'];
$route_day_id = $_POST['route_day_id'];
$trip_date = $_POST['trip_date'];
$bus = $_POST['bus'];


$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$vehicle' and trip_date='$trip_date'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 ==0){
	$update=mysqli_query($db,"UPDATE  booking SET bus='$vehicle' WHERE " . tessera_sql_tenant_eq() . " AND route_day_id='$route_day_id' and trip_date='$trip_date'");
$update=mysqli_query($db,"UPDATE  manifest SET bus='$vehicle',driver='$drivers_name',conductor='$conductor_update' WHERE  id='$id_no'");
$update=mysqli_query($db,"UPDATE  route_day SET bus='$vehicle' WHERE  id='$route_day_id'");
$update=mysqli_query($db,"UPDATE  vehicle SET driver='$drivers_name',conductor='$conductor_update' WHERE  name='$bus'");




echo "succesfull";
}else{

$update=mysqli_query($db,"UPDATE  manifest SET driver='$drivers_name',conductor='$conductor_update' WHERE  id='$id_no'");
$update=mysqli_query($db,"UPDATE  vehicle SET driver='$drivers_name',conductor='$conductor_update' WHERE  name='$bus'");

	
echo "succesfull";

}







?>