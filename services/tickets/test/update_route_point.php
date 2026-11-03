				<?php
				  include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  sub_route  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $route_name =$get13['route_name']; 
 $name =$get13['name']; 
 $main_route_no =$get13['main_route_no']; 
 $start_route =$get13['start_route']; 
 $end_route =$get13['end_route']; 
 $a_time =$get13['a_time']; 
 $d_time =$get13['d_time']; 
 $sub_route_id =$get13['sub_route_id']; 


	$insert=mysqli_query($db,"INSERT INTO sub_route_points (detail,point,time,route_id,sub_route_id,route_name,start_location,name,end_location) VALUES 
('$end_route - $a_time','end','$a_time','$main_route_no','$sub_route_id','$name','$start_route','$start_route - office','$end_route')");


}}
 ?>