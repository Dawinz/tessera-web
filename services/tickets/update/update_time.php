<?php
include '../connect.php';
$departure = $_POST['departure'];
$arrival = $_POST['arrival'];
$fullname = $_POST['fullname'];
$value = $_POST['value'];
$id_point = $_POST['id_point'];
$route_id = $_POST['route_id'];
$main_id = $_POST['main_id'];
$route_type = $_POST['route_type'];
$name = $_POST['name'];
$route_day_id = $_POST['id'];
$route_day_id = $_POST['id'];

$select_points=mysqli_query($db,"SELECT *  FROM  traveling_time WHERE route_day_id= '$route_day_id' and point_id='$id_point' ");
$select_num_points=mysqli_num_rows($select_points);
if($select_num_points ==0){
  
$insert=mysqli_query($db,"INSERT INTO traveling_time (route_id,main_id,reporting_time,deputure,route_day_id,route_type,name,point_id,status) 
VALUES                                    ('$route_id','$main_id','$arrival','$departure','$route_day_id','$route_type','$name','$id_point','Departure')");

}else{

   $update=mysqli_query($db,"UPDATE  traveling_time SET deputure='$departure',reporting_time='$arrival' WHERE  route_day_id= '$route_day_id' and point_id='$id_point' ");


}




?>