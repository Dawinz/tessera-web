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

echo $main_route_no;
$select13=mysqli_query($db,"SELECT *  FROM  routes where route_id ='$main_route_no'  ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
$start_route2 =$get133['start_route']; 
$end_route2 =$get133['end_route']; 

}}

if($end_route == $end_route2 ){
	
}else{
	$insert=mysqli_query($db,"INSERT INTO sub_route_list (start_route,end_route,location) VALUES 
('$start_route2','$end_route2','$end_route')");	
}




}}
 ?>