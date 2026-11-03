<?php
include 'connect.php';
$route_id = $_POST['route_name'];
$point_start = $_POST['point_start'];
$place_name = $_POST['place_name'];






$select1=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
 WHILE($get13=mysqli_fetch_array($select1)){

 $start_route =$get13['start_route']; 
 $end_route =$get13['end_route']; 
 $departure =$get13['departure']; 
 $route_name =$get13['route_name']; 

}}





$select1=mysqli_query($db,"SELECT *  FROM  route_points WHERE name='$place_name' and point='$point_start' and route_id='$route_id'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
		$insert=mysqli_query($db,"INSERT INTO route_points (name,route_id,point,detail,route_name,start_location,end_location) VALUES 
('$place_name','$route_id','$point_start','$place_name','$route_name','$start_route','$end_route')");
 echo "succesfull";
}else{
	echo "found";
	
}



?>