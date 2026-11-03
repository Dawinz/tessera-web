<?php
include '../connect.php';
$id_route = $_POST['trip_start_modal'];
$trip_date = $_POST['starting_date_select'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$trip_date_tomorrow = date('l', strtotime($trip_date));
$select_main=mysqli_query($db,"SELECT route_name,id,main_id,sub_route_id,route_id,fullname,route_name,route_type  FROM  routes WHERE id='$id_route'");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
 
 $route_id= $get_main['route_id']; 
 $sub_route_id= $get_main['main_id']; 
 $route_name= $get_main['route_name']; 
 $fullname= $get_main['fullname']; 
 $route_type= $get_main['route_type']; 
 
}}

if($route_type !="Main Route"){
	 $route_id = $sub_route_id;
}else{

}


$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow' and route_id ='$route_id' and status='active' ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
WHILE($get=mysqli_fetch_array($select)){    	   
			$route_id =$get['route_id'];
            $id2 =$get['id'];
			$arrival =$get['arrival'];
			$departure =$get['departure'];
			$time =$get['12hours'];
			$trip_day =$get['day']; 
			$bus =$get['bus']; 
			$now_time = date('h:i A', time());
			
	echo "<option value='$bus'>$bus</option>";
}}else{
	
echo "<option value='no bus'>no bus</option>";	
}

?>