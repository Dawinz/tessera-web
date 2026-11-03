<?php
include '../connect/connect.php';

$month = date("m-Y", strtotime($_POST['checd_trip_date']));  
$trip_date = $_POST['checd_trip_date'];
$bus = $_POST['checked_bus'];
$rpoint = $_POST['rpoint'];

$array_id = explode(",", $_POST['selected_checked']);
foreach ($array_id as $x) {

$insert=mysqli_query($db,"DELETE from vehicle_account WHERE id = '$x'");
include '../includes/update/add_expenses.php';
include '../includes/update/add_income.php';
}

$busname  = $bus;
include '../update/update_total.php';

$location = "point=".str_replace(" " , "_",$rpoint)."&route_name=".str_replace(" " , "_",$_POST['checd_route'])."&trip_date=".$_POST['checd_trip_date']."&bus=".$_POST['checked_bus']."&main_route=".str_replace(" " , "_",$_POST['checd_route'])."&current_route=".str_replace(" " , "_",$_POST['current_route_c'])."&row_num=".$_POST['row_num_route']."&end_point=".$_POST['end_point_c']."";
echo $location;
?>