<?php
include '../connect/connect.php';

$bus = $_POST['bus'];
$trip_date = $_POST['trip_date'];
$start_point = $_POST['start_point'];
$analysed = "Half Checked";
$select_currency=mysqli_query($db,"SELECT checked,checked_count,route_name FROM   manifest  WHERE trip_date = '$trip_date' and bus='$bus'  ");
$select_num_currency=mysqli_num_rows($select_currency);

   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $checked =$get_currency['checked']; 
    	    $checked_count =$get_currency['checked_count']; 
    	    $route_name =$get_currency['route_name']; 
			
$select_route=mysqli_query($db,"SELECT fullname  FROM    route    where main_route ='$route_name'  ");
$select_num_route=mysqli_num_rows($select_route);
		
$total_route = $select_num_route + 1;
			
if($checked_count=="0"){
	$analysed = "Half Checked";
	$update=mysqli_query($db,"UPDATE  manifest  SET 
	checked_count='1',checked='$start_point',analysed='$analysed'
	WHERE bus='$bus' and trip_date = '$trip_date'");	
	
}else{
	$new_array = explode(",", "$checked");
	  if (($key = array_search($start_point, $new_array)) !== false) {
    unset($new_array[$key]);

$new_checked =  implode(",",$new_array);
 $new_count = $checked_count-1;
if($new_count == 0){
		$analysed = "";
}



$update=mysqli_query($db,"UPDATE  manifest  SET 
	checked_count='$new_count',checked='$new_checked',analysed='$analysed'
	WHERE bus='$bus' and trip_date = '$trip_date'");	

}else{
	  $new_count = $checked_count+1;
	$new_checked = "$checked,$start_point";

if($new_count == $total_route){
		$analysed = "Complete";
}


	$update=mysqli_query($db,"UPDATE  manifest  SET 
	checked_count='$new_count',checked='$new_checked',analysed='$analysed'
	WHERE bus='$bus' and trip_date = '$trip_date'");	 
	
}
	
}		

		
}
$vehicle_name=$bus;
include'../includes/update/constant_manifest.php';
			


?>