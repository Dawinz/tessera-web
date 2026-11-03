<?php

Function my_assigned_routes_query($db,$username_selected){
       $route_query = "";
   $routes_array=array();
$select=mysqli_query($db,"SELECT route_full_name FROM   assigned_routes   where username ='$username_selected'   ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_name_choose =$get['route_full_name']; 
			
			$route_name_choose = "'$route_name_choose' ";
			
array_push($routes_array,$route_name_choose);

			
}}
$selected_route = implode(', ', $routes_array);
if ($select_num !== 0 && $selected_route !== '') {
	$route_query = "account IN ($selected_route) and ";
} else {
	$route_query = '';
}
echo $route_query;
}
Function my_assigned_routes($db,$username){
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'  order by id asc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}

}
	

}
?>