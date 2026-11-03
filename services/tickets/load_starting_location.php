<?php
include 'connect.php';
if(isset($_POST["starting_point_route"]))
{
$start_location = $_POST['starting_point_route'];
$end_point_location = $_POST['end_point_location'];
	 $start_location = preg_replace('/_/',' ', $start_location);


 
 $select1356=mysqli_query($db,"
 SELECT detail
FROM  sub_route_points Where  point ='start' and start_location='$start_location' and end_location ='$end_point_location'
UNION DISTINCT
SELECT detail
FROM route_points where  point ='starting' and start_location='$start_location' and end_location ='$end_point_location'
 ");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 
 $start_route2 = '<option value="'.$get13356["detail"].'">'.$get13356["detail"].'</option>';

 echo $start_route2;

}}else{
	echo '<option value="">No Location</option>';
}
 
 
 
}else{


$start_location = $_POST['starting_point_route_checked'];
$end_point_location = $_POST['end_point_location'];
	 $start_location = preg_replace('/_/',' ', $start_location);


 
 $select1356=mysqli_query($db,"
 SELECT detail
FROM  sub_route_points Where   start_location='$start_location' and point='end' and end_location ='$end_point_location'
UNION DISTINCT
SELECT detail
FROM route_points where   start_location='$start_location' and point='end' and end_location ='$end_point_location'
 ");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 
 $start_route2 = '<option value="'.$get13356["detail"].'">'.$get13356["detail"].'</option>';

 echo $start_route2;

}}else{
	echo '<option value="">No Location</option>';
}
 
 
	
}



?>