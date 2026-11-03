<?php
include '../connect.php';

if( isset ($route_id_query)){
 $route_query = "WHERE route_id='".$route_id_query."' or main_id='".$route_id_query."'";
}else{
$route_query="";
}

$select_main=mysqli_query($db,"SELECT *  FROM   route_time  $route_query");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
    	    $route_id =$get_main['route_id']; 
    	    $route_type =$get_main['route_type']; 
    	    $main_id =$get_main['main_id']; 
    	    $point_id =$get_main['point_id']; 
    	    $arrival =$get_main['arrival']; 
    	    $departure =$get_main['deputure']; 
    	    $reporting =$get_main['reporting_time']; 
    	    $status =$get_main['status']; 



    $select_main_point=mysqli_query($db,"SELECT *  FROM   route_day where route_id='$route_id'  ");
$select_num_main_point=mysqli_num_rows($select_main_point);
if($select_num_main_point !=0){
   	WHILE($get_main_point=mysqli_fetch_array($select_main_point)){
    	    $route_day_id =$get_main_point['id']; 

$select_point=mysqli_query($db,"SELECT *  FROM   route_points where id='$point_id'  ");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
   	WHILE($get_point=mysqli_fetch_array($select_point)){
    	    $name =$get_point['name']; 


	}}
$select_travel_time=mysqli_query($db,"SELECT *  FROM   traveling_time   WHERE route_id = '$route_id' and route_day_id = '$route_day_id' and status = '$status' and point_id='$point_id'");
$select_num_travel_time=mysqli_num_rows($select_travel_time);
if($select_num_travel_time ==0){


 $insert=mysqli_query($db,"INSERT INTO  traveling_time  (route_id,route_day_id,deputure,arrival,reporting_time,point_id,status,route_type,main_id,name) VALUES
 ('$route_id','$route_day_id','$departure','$arrival','$reporting','$point_id','$status','$route_type','$main_id','$name')");	
}else{
	   $update=mysqli_query($db,"UPDATE   traveling_time  SET deputure='$departure',arrival='$arrival',reporting_time='$reporting' WHERE  route_id = '$route_id' and route_day_id = '$route_day_id'and status = '$status' and point_id='$point_id'");

}
//////maiin end/////


        }}else{

  $select_main_point=mysqli_query($db,"SELECT *  FROM   route_day where route_id='$main_id'  ");
$select_num_main_point=mysqli_num_rows($select_main_point);
if($select_num_main_point !=0){
   	WHILE($get_main_point=mysqli_fetch_array($select_main_point)){
    	    $route_day_id =$get_main_point['id']; 
    	
$select_point=mysqli_query($db,"SELECT *  FROM   route_points where id='$point_id'  ");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
   	WHILE($get_point=mysqli_fetch_array($select_point)){
    	    $name =$get_point['name']; 


	}}

$select_travel_time=mysqli_query($db,"SELECT *  FROM   traveling_time   WHERE route_id = '$route_id' and route_day_id = '$route_day_id' and status = '$status' and point_id='$point_id' and status = '$status' and point_id='$point_id'");
$select_num_travel_time=mysqli_num_rows($select_travel_time);
if($select_num_travel_time ==0){
$insert=mysqli_query($db,"INSERT INTO  traveling_time  (route_id,route_day_id,deputure,arrival,reporting_time,point_id,status,route_type,main_id,name) VALUES
 ('$route_id','$route_day_id','$departure','$arrival','$reporting','$point_id','$status','$route_type','$main_id','$name')");	
}else{
	   $update=mysqli_query($db,"UPDATE   traveling_time  SET deputure='$departure',arrival='$arrival',reporting_time='$reporting' WHERE  route_id = '$route_id' and route_day_id = '$route_day_id'and status = '$status' and point_id='$point_id'");

}


 

//////maiin end/////


        }}

            
        }

        }}

?>