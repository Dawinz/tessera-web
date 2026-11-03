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
$status = "Departure";

$select_points=mysqli_query($db,"SELECT *  FROM   route_time  WHERE route_id= '$route_id' and point_id='$id_point' ");
$select_num_points=mysqli_num_rows($select_points);
if($select_num_points ==0){
  
$insert=mysqli_query($db,"INSERT INTO  route_time  (route_id,main_id,reporting_time,deputure,route_type,name,point_id,status) 
VALUES                                    ('$route_id','$main_id','$arrival','$departure','$route_type','$name','$id_point','Departure')");

}else{

   $update=mysqli_query($db,"UPDATE   route_time  SET deputure='$departure',reporting_time='$arrival' WHERE  route_id= '$route_id' and point_id='$id_point' ");


}



if($route_type=="Main Route"){
$select_main_point=mysqli_query($db,"SELECT *  FROM   route_day where route_id='$route_id'  ");
$select_num_main_point=mysqli_num_rows($select_main_point);
if($select_num_main_point !=0){
   	WHILE($get_main_point=mysqli_fetch_array($select_main_point)){
    	    $route_day_id =$get_main_point['id']; 
$select_point=mysqli_query($db,"SELECT *  FROM   route_points where id='$id_point'  ");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
   	WHILE($get_point=mysqli_fetch_array($select_point)){
    	    $name =$get_point['name']; 

$select_travel_time=mysqli_query($db,"SELECT *  FROM   traveling_time   WHERE route_id = '$route_id' and route_day_id = '$route_day_id' and status = '$status' and point_id='$id_point'");
$select_num_travel_time=mysqli_num_rows($select_travel_time);
if($select_num_travel_time ==0){
 $insert=mysqli_query($db,"INSERT INTO  traveling_time  (route_id,route_day_id,arrival,point_id,status,route_type,main_id,name) VALUES
 ('$route_id','$route_day_id','$arrival','$id_point','$status','$route_type','$main_id','$name')");	
}else{

}

	}}

      }}
      
}else{
$select_main_point=mysqli_query($db,"SELECT *  FROM   route_day where route_id='$main_id'  ");
$select_num_main_point=mysqli_num_rows($select_main_point);
if($select_num_main_point !=0){
   	WHILE($get_main_point=mysqli_fetch_array($select_main_point)){
    	    $route_day_id =$get_main_point['id']; 
$select_point=mysqli_query($db,"SELECT *  FROM   route_points where id='$id_point'  ");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
   	WHILE($get_point=mysqli_fetch_array($select_point)){
    	    $name =$get_point['name']; 

$select_travel_time=mysqli_query($db,"SELECT *  FROM   traveling_time   WHERE route_id = '$route_id' and route_day_id = '$route_day_id' and status = '$status' and point_id='$id_point'");
$select_num_travel_time=mysqli_num_rows($select_travel_time);
if($select_num_travel_time ==0){
 $insert=mysqli_query($db,"INSERT INTO  traveling_time  (route_id,route_day_id,deputure,reporting_time,point_id,status,route_type,main_id,name) VALUES
 ('$route_id','$route_day_id','$departure','$arrival','$id_point','$status','$route_type','$main_id','$name')");	
}else{

}

	}}

      }}
}
?>