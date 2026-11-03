<?php
include '../connect/connect.php';
$month = date("m-Y", strtotime($_POST['checd_trip_date']));  
$trip_date = $_POST['checd_trip_date'];
$bus = $_POST['checked_bus'];
$rpoint = $_POST['rpoint'];
$first_bus = $_POST['first_bus'];
$first_trip_date = $_POST['first_trip_date'];


$select_manifest=mysqli_query($db,"SELECT bus FROM   manifest   where bus ='$bus' and trip_date='$trip_date'  ");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
  	$array_id = explode(",", $_POST['selected_checked']);
foreach ($array_id as $x) {

$insert=mysqli_query($db,"UPDATE  vehicle_account  SET 
	bus='$bus',
	trip_date='$trip_date'
	WHERE id='$x'");
}

}else{
	echo "not found";
}
$location = "point=".str_replace(" " , "_",$rpoint)."&route_name=".str_replace(" " , "_",$_POST['checd_route'])."&trip_date=".$_POST['first_trip_date']."&bus=".$_POST['first_bus']."&main_route=".str_replace(" " , "_",$_POST['checd_route'])."&current_route=".str_replace(" " , "_",$_POST['current_route_c'])."&row_num=".$_POST['row_num_route']."&end_point=".$_POST['end_point_c']."";
echo $location;
?>