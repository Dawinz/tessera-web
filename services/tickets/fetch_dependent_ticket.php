<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$start_location = $_POST['query'];
$ending_location = $_POST['trip_end_modal'];
$starting_date_select = $_POST['starting_date_select'];
$action = $_POST['action'];
$trip_date = date('l', strtotime($starting_date_select));



 include 'connect.php';


	$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$start_location' AND end_route='$ending_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    
			$route_id =$get134['route_id']; 

}
	
	$result = "true";
}else{
	
	
			$select145=mysqli_query($db,"SELECT main_route_no  FROM  sub_route  WHERE start_route='$start_location' AND end_route='$ending_location' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	  
			$route_id = $get1345['main_route_no']; 
	
}
$result = "true";
}else{
	
	$result = "false";
}


	
}

if($result =="true"){
	$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date'  and route_id ='$route_id' and status='active'  ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
 
			$bus =$get['bus']; 

$output = '<option value="'.$get["bus"].'">'.$get["bus"].'</option>';
echo $output;
}}else{
	echo "<option>No bus</option>";
}	
}else{
	echo "<option>No bus</option>";
}




?>
