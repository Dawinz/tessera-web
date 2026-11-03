<?php
include '../connect.php';

$sub_route_id_end = $_POST['sub_route_id_end'];
if($sub_route_id_end == ""){
  
	$route_id = $_POST['route_id_start'];
  
$output = "";
$query = "SELECT name  FROM  route_points where route_id= '$route_id' and point='end' ";
  $result = mysqli_query($db, $query);
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
  }
   echo $output;
}else{
  $route_id_start = $_POST['sub_route_id_end'];

$select14=mysqli_query($db,"SELECT sub_route_id,route_id FROM  routes WHERE id='$route_id_start'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];		
    	    $sub_route_id_end =$get134['sub_route_id'];		
}
}

if ($sub_route_id_end != ""){
	$route_id =$sub_route_id_end;
}	
$output = "";
$query = "SELECT name  FROM  route_points where route_id= '$route_id' and point='end' ";
  $result = mysqli_query($db, $query);
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
  }
   echo $output;	
}

?>