<?php
include '../connect.php';
for($count = 0; $count < count($_POST["hours"]); $count++)

	{
		$hours = $_POST['hours'][$count];	
		$min = $_POST['min'][$count];	
		$route_id = $_POST['route_id'][$count];	
		$route_day_id = $_POST['route_day_id'];

if(($hours!="") and ($min!="") and ($route_id !="")){
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
//echo "($row_num)$id $arrival to $new_arr  on $route_id<br>";

$reporting = "";
$select_t=mysqli_query($db,"SELECT *  FROM  travaling_time  WHERE route_id = '$route_id' and route_day_id = '$route_day_id'  and route_point_id='$id'");
$select_num_t=mysqli_num_rows($select_t);
if($select_num_t ==0){
$insert=mysqli_query($db,"INSERT INTO travaling_time (route_id,route_day_id,depature,arrival,reporting,route_point_id) VALUES
 ('$route_id','$route_day_id','$new_dep','$new_arr','$reporting','$id')");	

}else{

   $update=mysqli_query($db,"UPDATE  travaling_time SET depature='$new_dep',arrival='$new_arr' WHERE  route_id = '$route_id' and route_day_id = '$route_day_id' and route_point_id='$id'");

}


		}
}}


}
		
	
?>