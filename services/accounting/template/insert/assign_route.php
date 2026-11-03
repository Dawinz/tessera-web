<?php
include '../connect/connect.php';
$location = $_POST['my_id'];
$route_name = $_POST['route_name'];
$main_route = $_POST['main_route'];
$username = $_POST['username'];
$status = "";

if($route_name == $main_route){
	$status ="main route";
}

if($location =="check_all"){

 
 $select_ass=mysqli_query($db,"SELECT route FROM   assign_route   WHERE main_route 	='$route_name' and   username ='admin'  ");
$select_num_ass=mysqli_num_rows($select_ass);
if($select_num_ass ==0){
$select_route=mysqli_query($db,"SELECT *  FROM   route   where main_route='$route_name'");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
	
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $start_point =$get_route['start_point']; 
    	    $end_point =$get_route['end_point']; 
    	    $route_full_name =$get_route['fullname']; 
    	    $route_initial =$get_route['name']; 
			
			
$sqlQ = "INSERT INTO  assign_route  (route,route_name,username,point,main_route,end_point,status) VALUES (?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssss", $route_initial, $route_full_name,$username,$start_point,$route_name,$end_point,$status); 
                $insert = $stmt->execute();	

	}
}


}else{

	$select_route=mysqli_query($db,"SELECT *  FROM   route   where main_route='$route_name'");
$select_num_route=mysqli_num_rows($select_route);

	if($select_num_ass == $select_num_route){
	$delete=mysqli_query($db,"DELETE FROM   assign_route   WHERE main_route ='$route_name'  and  username ='admin' ");

		}else{
				$delete=mysqli_query($db,"DELETE FROM   assign_route   WHERE main_route ='$route_name'  and  username ='admin' ");

		$select_route=mysqli_query($db,"SELECT *  FROM   route   where main_route='$route_name'");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
	
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $start_point =$get_route['start_point']; 
    	    $end_point =$get_route['end_point']; 
    	    $route_full_name =$get_route['fullname']; 
    	    $route_initial =$get_route['name']; 
			
			
$sqlQ = "INSERT INTO  assign_route  (route,route_name,username,point,main_route,end_point,status) VALUES (?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssss", $route_initial, $route_full_name,$username,$start_point,$route_name,$end_point,$status); 
                $insert = $stmt->execute();	

	}
}
	
		}
	

}
 
 
 
}else{

 $select_ass=mysqli_query($db,"SELECT route FROM   assign_route   WHERE route_name 	='$route_name' and   username ='$username'  ");
$select_num_ass=mysqli_num_rows($select_ass);
if($select_num_ass ==0){


$select_route=mysqli_query($db,"SELECT *  FROM   route   where fullname='$route_name'");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $start_point =$get_route['start_point']; 
    	    $end_point =$get_route['end_point']; 
    	    $route_full_name =$get_route['fullname']; 
    	    $route_initial =$get_route['name']; 
    	  //  $main_route =$get_route['main_route']; 
			
			
$sqlQ = "INSERT INTO  assign_route  (route,route_name,username,point,main_route,end_point,status) VALUES (?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssss", $route_initial, $route_full_name,$username,$start_point,$main_route,$end_point,$status); 
                $insert = $stmt->execute();	

	}
}


}else{
	$delete=mysqli_query($db,"DELETE FROM   assign_route   WHERE route_name ='$route_name'  and  username ='$username' ");

}
 

}

?>