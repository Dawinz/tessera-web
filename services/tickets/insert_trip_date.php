<?php
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$tenant_id = (int) tessera_current_tenant_id();
if ($tenant_id < 1) {
	echo 'no tenant';
	exit;
}
$route_name = $_POST['route_name'];
$day_of_week = $_POST['day_of_week'];
$vehicle_name = $_POST['vehicle_name'];
$d_ptime = $_POST['d_ptime'];
$a_time = $_POST['a_time'];


$a_time = date('h:i A', strtotime($a_time));
$d_ptime = date('h:i A', strtotime($d_ptime));


$detail = $_POST['detail'];
$detail = base64_encode($detail);
$check = $_POST['check'];

 $report_time = date('H:i A', strtotime('-30 minutes',strtotime($a_time)));
 $route_active_time = date('H:i A', strtotime('+300 minutes',strtotime($d_ptime)));
echo $route_active_time;
$select1=mysqli_query($db,"SELECT *  FROM  routes WHERE route_name='$route_name' AND tenant_id='$tenant_id'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $route_id =$get13['route_id']; 

}}


$select1=mysqli_query($db,"SELECT *  FROM  route_day WHERE bus='$vehicle_name' and day='$day_of_week' and route_id='$route_id ' AND tenant_id='$tenant_id' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
	if($check =="on"){
		$insert=mysqli_query($db,"INSERT INTO route_day (route_id,day,bus,arrival,departure,12hours,reporting_time,detail,status,route_name,route_active_time,button_name,button_icon,button_class,tenant_id) VALUES 
('$route_id','$day_of_week','$vehicle_name','$a_time','$d_ptime','$a_time','$report_time','$detail','active','$route_name','$route_active_time','De activate','uil uil-exclamation-octagon me-2','btn btn-soft-danger','$tenant_id')");
	}else{
		$insert=mysqli_query($db,"INSERT INTO route_day (route_id,day,bus,arrival,departure,12hours,reporting_time,detail,status,route_name,route_active_time,button_name,button_icon,button_class,tenant_id) VALUES 
('$route_id','$day_of_week','$vehicle_name','$a_time','$d_ptime','$a_time','$report_time','$detail','off','$route_name','$route_active_time','De activate','uil uil-exclamation-octagon me-2','btn btn-soft-danger','$tenant_id')");
	}

 
 
 echo "succesfull";
}else{
	echo "found";
	
}



?>