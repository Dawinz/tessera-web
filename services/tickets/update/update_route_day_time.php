<?php
include '../connect.php';
$route_day_id = $_POST['id'];
$departure = $_POST['departure'];
$arrival = $_POST['arrival'];
$fullname = $_POST['fullname'];
$value = $_POST['value'];
$route_id = $_POST['route_id'];
$reporting = "";


$select_main=mysqli_query($db,"SELECT *  FROM  route_points  WHERE route_id = '$route_id' and initial_location = 'initial'  ");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
    	    $id =$get_main['id']; 
   
}}


$value = "$value";
switch ($value) {
  case "main":
//////main start//////


$select_main=mysqli_query($db,"SELECT *  FROM  travaling_time  WHERE route_id = '$route_id' and route_day_id = '$route_day_id' and route_point_id=''  ");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main ==0){

$insert=mysqli_query($db,"INSERT INTO travaling_time (route_id,route_day_id,depature,arrival,reporting) VALUES
 ('$route_id','$route_day_id','$departure','$arrival','$reporting')");

 $insert=mysqli_query($db,"INSERT INTO travaling_time (route_id,route_day_id,depature,arrival,reporting,route_point_id) VALUES
 ('$route_id','$route_day_id','$departure','$arrival','$reporting','$id')");	

//////maiin end/////
}else{
	
	
	
   $update=mysqli_query($db,"UPDATE  travaling_time SET depature='$departure',arrival='$arrival' WHERE  route_id = '$route_id' and route_day_id = '$route_day_id' and route_point_id='' ");
   $update=mysqli_query($db,"UPDATE  travaling_time SET depature='$departure',arrival='$arrival' WHERE  route_id = '$route_id' and route_day_id = '$route_day_id' and route_point_id='$id'");
	
}
   break;
  case "points":
   //// start point /////
$id = $_POST['id_point'];

$select_main=mysqli_query($db,"SELECT *  FROM  travaling_time  WHERE route_id = '$route_id' and route_day_id = '$route_day_id'  and route_point_id='$id'");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main ==0){



 $insert=mysqli_query($db,"INSERT INTO travaling_time (route_id,route_day_id,depature,arrival,reporting,route_point_id) VALUES
 ('$route_id','$route_day_id','$departure','$arrival','$reporting','$id')");	

//////maiin end/////
}else{
	
	
	
   $update=mysqli_query($db,"UPDATE  travaling_time SET depature='$departure',arrival='$arrival' WHERE  route_id = '$route_id' and route_day_id = '$route_day_id' and route_point_id='$id'");
	
} 
   ///// end point //////
}
?>