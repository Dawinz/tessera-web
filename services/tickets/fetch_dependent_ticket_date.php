<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$starting_date_select = $_POST['query'];
$ending_location = $_POST['trip_end_modal'];
$start_location = $_POST['trip_start_modal'];
$action = $_POST['action'];
$trip_date = date('l', strtotime($starting_date_select));

 include 'connect.php';



	$select14=mysqli_query($db,"SELECT route_id,main_id,route_type  FROM  routes WHERE id='$start_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    
			$route_id =$get134['route_id']; 
			$main_id =$get134['main_id']; 
            $route_type= $get134['route_type']; 
}
	
	
}
if($route_type !="Main Route"){
	 $route_id = $main_id;
}else{

}


	$select=mysqli_query($db,"SELECT  bus  FROM  route_day where   day='$trip_date'  and route_id ='$route_id' and status='active'  ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
 
			$bus =$get['bus']; 

$output = '<option value="'.$get["bus"].'">'.$get["bus"].'</option>';
echo $output;
}}else{
	$output = '<option value="no">No Bus</option>';
echo $output;
}	




?>
