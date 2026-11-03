<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$route_name = $_POST['route_name'];
$day_of_week = $_POST['day_of_week'];
$vehicle_name = $_POST['vehicle_name'];
$d_ptime = $_POST['d_ptime'];
$a_time = $_POST['a_time'];
$id_no = $_POST['id_no'];
$detail = $_POST['detail'];


$a_time = date('h:i A', strtotime($a_time));
$d_ptime = date('h:i A', strtotime($d_ptime));
 $report_time = date('H:i A', strtotime('-30 minutes',strtotime($d_ptime)));


$select15=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id_no'  ");
$select_num15=mysqli_num_rows($select15);
if($select_num15 !=0){
   	WHILE($get135=mysqli_fetch_array($select15)){
 $bus =$get135['bus']; 

}}



$select1=mysqli_query($db,"SELECT *  FROM  routes WHERE route_name='$route_name'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $route_id =$get13['route_id']; 

}}

$select_point=mysqli_query($db,"SELECT *  FROM  route_points WHERE route_name='$route_name'  ");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
   	WHILE($get_point=mysqli_fetch_array($select_point)){
 $old_time =$get_point['time']; 
 $name =$get_point['name']; 

}}



$detail = $_POST['detail'];
$detail = base64_encode($detail);

$new_detail = "$name - $d_ptime";

 if($vehicle_name != $bus){
$update=mysqli_query($db,"UPDATE  route_day SET 
day='$day_of_week',
bus='$vehicle_name',arrival='$a_time',
departure='$d_ptime',status='active',
route_id='$route_id',
route_name='$route_name',
detail='$detail',
reporting_time='$report_time'

 WHERE id='$id_no'");
	echo "update";
}else{
$update=mysqli_query($db,"UPDATE  route_day SET day='$day_of_week',
bus='$vehicle_name',
arrival='$a_time',
departure='$d_ptime',
status='active',
route_id='$route_id',
route_name='$route_name',
detail='$detail',
reporting_time='$report_time'
 WHERE id='$id_no'");
	 echo "succesfull";
}

$update=mysqli_query($db,"UPDATE  route_points SET detail='$new_detail' WHERE route_name='$route_name' and time='$d_ptime'");

 






?>