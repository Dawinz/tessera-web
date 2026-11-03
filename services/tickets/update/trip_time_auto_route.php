<?php
include '../connect.php';
for($count = 0; $count < count($_POST["hours"]); $count++)

	{
		$hours = $_POST['hours'][$count];	
		$min = $_POST['min'][$count];	
		$route_id = $_POST['route_id'][$count];	
		$route_day_id = $_POST['route_day_id'];

if(($hours!="") and ($min!="") and ($route_id !="")){
	
	
	$select_main=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
    	 
    	    $departure =$get_main['departure']; 
    	    $arrival =$get_main['arrival']; 
    	   
}}
	$new_arr =  date('H:i',strtotime('+'.$hours.' hour +'.$min.' minutes',strtotime($arrival)));
	$new_dep=  date('H:i',strtotime('+'.$hours.' hour +'.$min.' minutes',strtotime($departure)));
$select_main=mysqli_query($db,"SELECT *  FROM  route_points  WHERE route_id = '$route_id' and initial_location = 'initial'  ");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
    	    $route_point_id =$get_main['id']; 
   
			
}}

$update=mysqli_query($db,"UPDATE  route_points SET time='$new_dep',arrival='$new_arr' WHERE  id='$route_point_id'");
$update=mysqli_query($db,"UPDATE  routes SET departure='$new_dep',arrival='$new_arr' WHERE  route_id='$route_id'");

$select_main=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num, time,arrival,id  FROM   route_points  WHERE time !='' and arrival !='' AND route_id='$route_id' ");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
    	    $row_num =$get_main['row_num']; 
    	    $id =$get_main['id']; 
    	    $departure =$get_main['time']; 
    	    $arrival =$get_main['arrival']; 
		$new_arr =  date('H:i',strtotime('+'.$hours.' hour +'.$min.' minutes',strtotime($arrival)));
		$new_dep=  date('H:i',strtotime('+'.$hours.' hour +'.$min.' minutes',strtotime($departure)));
if($row_num ==1){
	
}else{
	$update=mysqli_query($db,"UPDATE  route_points SET time='$new_dep',arrival='$new_arr' WHERE  id='$id'");
}


		}
}}


}
		
	
?>