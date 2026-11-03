<?php
include '../connect.php';

for($count = 0; $count < count($_POST["point"]); $count++)

	{
		
$route_name = $_POST["route_name"] [$count];		
$point_start = $_POST["point"] [$count];	
$place_name = $_POST["point_name"] [$count];	
$a_time = $_POST["arrival"] [$count];	
$d_time = $_POST["depture"] [$count];	

echo $route_name;
$select1=mysqli_query($db,"SELECT *  FROM  routes WHERE route_name='$route_name'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
 WHILE($get13=mysqli_fetch_array($select1)){
 $route_id =$get13['route_id']; 
 $start_route =$get13['start_route']; 
 $end_route =$get13['end_route']; 
 $departure =$get13['departure']; 

}}

echo "$route_name and $route_id";




$select1=mysqli_query($db,"SELECT *  FROM  route_points WHERE name='$route_name' and route_id='$route_id' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
		$insert=mysqli_query($db,"INSERT INTO route_points (name,route_id,point,time,detail,route_name,start_location,end_location,arrival) VALUES 
('$place_name','$route_id','$point_start','$d_time','$place_name - $d_time','$route_name','$start_route','$end_route','$a_time')");
 echo "succesfull";
}else{
	echo "found";
	
}

if($departure == ""){
	
$insert=mysqli_query($db,"UPDATE  routes SET departure='$d_time',arrival='$a_time' WHERE route_id='$route_id'");
	
	
}
	}
?>