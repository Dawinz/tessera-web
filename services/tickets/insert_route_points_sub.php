<?php
include 'connect.php';
$route_name = $_POST['route_name'];
$point_start = $_POST['point_start'];
$place_name = $_POST['place_name'];
$a_time = $_POST['a_time'];


$a_time = date('h:i A', strtotime($a_time));



$select1=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE name='$route_name'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $main_route_no =$get13['main_route_no'];
 $sub_route_id =$get13['sub_route_id']; 
 $start_route =$get13['start_route']; 
 $end_route =$get13['end_route']; 

}}




$select1=mysqli_query($db,"SELECT *  FROM  sub_route_points WHERE point='$route_name' and route_id='$main_route_no' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
		$insert=mysqli_query($db,"INSERT INTO sub_route_points  (route_id,time,detail,point,route_name,sub_route_id,name,start_location,end_location) VALUES 
('$main_route_no','$a_time','$place_name - $a_time','$point_start','$route_name','$sub_route_id','$place_name','$start_route','$end_route')");
 echo "succesfull";
}else{
	echo "found";
	
}



?>