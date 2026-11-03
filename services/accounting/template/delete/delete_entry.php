<?php
include '../connect/connect.php';
$id = $_POST['id'];
$select_status=mysqli_query($db,"SELECT status,month,trip_date,bus,currency,route_name,affect FROM   vehicle_account   where id ='$id'  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	    $status =$get_status['status']; 
    	    $trip_date =$get_status['trip_date']; 
    	    $bus =$get_status['bus']; 
    	    $currency =$get_status['currency']; 
    	    $route_name =$get_status['route_name']; 
    	    $affect =$get_status['affect']; 
	}
}
//$month = date("m-Y", strtotime($trip_date));
echo $affect;
$insert=mysqli_query($db,"DELETE from vehicle_account WHERE id = '$id'");

include '../update/update_total.php';


?>