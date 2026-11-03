<?php
$id = $_POST['id'];
include '../connect.php';


$select=mysqli_query($db,"SELECT *  FROM  route_transit WHERE id='$id'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_name =$get['route_name'];	
		    	    $route_prev =$get['route_prev'];	

	

	}
}


$select_route=mysqli_query($db,"SELECT *  FROM  routes WHERE fullname='$route_name'");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $transit =$get_route['transit'];	
		
	

	}
}

$new_array = explode(",", "$transit");



 if (($key = array_search($route_prev, $new_array)) !== false) {

	  unset($new_array[$key]);
//	  print_r ($new_array);
	 $new_checked =  implode(",",$new_array);
	 

$update=mysqli_query($db,"UPDATE  routes SET transit='$new_checked' WHERE  fullname='$route_name'");
		  }

$insert=mysqli_query($db,"DELETE from route_transit  WHERE id = '$id'");

?>