<?php
include '../connect.php';
require_once __DIR__ . '/../includes/tessera_tenant.php';
$tenant_id = (int) tessera_current_tenant_id();
if ($tenant_id < 1) {
	echo 'no tenant';
	exit;
}

for($count = 0; $count < count($_POST["day_of_week"]); $count++)
	{
$day_of_week = $_POST['day_of_week'] [$count];		
$route_id_p = $_POST['route_name'] [$count];		
$vehicle_name = $_POST['vehicle_name'] [$count];		
$detail = base64_encode($_POST['detail'] [$count]);		

if($day_of_week=="Monday"){
	$position = "1";
}else if($day_of_week=="Tuesday"){
		$position = "2";

}else if($day_of_week=="Wednesday"){
		$position = "3";

}else if($day_of_week=="Thursday"){
		$position = "4";

}else if($day_of_week=="Friday"){

	$position = "5";
	
}else if($day_of_week=="Saturday"){

	$position = "6";
	
}else if($day_of_week=="Sunday"){
	
	$position = "7";

	
}

$select_route_name=mysqli_query($db,"SELECT id, fullname, route_name, route_id FROM routes WHERE id = '$route_id_p' AND tenant_id='$tenant_id' ");
$select_num_route_name=mysqli_num_rows($select_route_name);
$route_day_route_id = $route_id_p;
if($select_num_route_name !=0){
   	WHILE($get_route_name=mysqli_fetch_array($select_route_name)){
    	    $route_id_fullname =$get_route_name['fullname']; 
    	    $route_id =$get_route_name['route_id']; 
    	    $route_name =$get_route_name['route_name']; 
    	    $fullname =$get_route_name['fullname']; 
	}
}
$select1=mysqli_query($db,"SELECT * FROM route_day WHERE bus='$vehicle_name' and day='$day_of_week' and route_id='$route_day_route_id' AND tenant_id='$tenant_id' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
$insert=mysqli_query($db,"INSERT INTO route_day (route_id,day,24day,12hours,mints,bus,arrival,departure,detail,status,reporting_time,route_name,route_active_time,button_name,button_icon,button_class,position,fullname,tenant_id) VALUES 
('$route_day_route_id','$day_of_week','','',0,'$vehicle_name','','','$detail','active','','$route_name','','De activate','uil uil-exclamation-octagon me-2','btn btn-soft-danger','$position','$fullname','$tenant_id')");
if ($insert) {
	echo "successfull";
} else {
	echo 'error';
}
	}else{
		echo "found";
	}
$route_id_query= $route_day_route_id;

	include '../update/traveling_time.php';
	}
	?>