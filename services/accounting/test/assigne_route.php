<?php
$location = $_POST['my_id'];
$route_name = $_POST['route_name'];
include '../connect/connect.php';
$username = "admin";


if($location =="check_all"){

	$select_sub=mysqli_query($db,"SELECT *  FROM   route   where status='Main' and fullname='$route_name'");
$select_num_sub=mysqli_num_rows($select_sub);
if($select_num_sub !=0){
	
 	$select=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    fullname,id,start_point,end_point,name
FROM 
	route where status='Main' and fullname='$route_name'
ORDER BY 
	id ASC 
		
		");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $start_point =$get['start_point'];
    	    $end_point =$get['end_point'];
    	    $fullname =$get['fullname'];
    	    $id =$get['id'];
			$route_initial =$get['name']; 
    	    $row_num =$get['row_num'];
			
			
}}

 $select_route=mysqli_query($db,"SELECT route_name   FROM   assign_route   WHERE route_name ='$route_name'  and   username ='admin' ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route ==0){
 

 $sqlQ = "INSERT INTO  assign_route  (route,route_name,username,point,main_route) VALUES (?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssss", $route_initial, $route_name,$username,$start_point,$route_name); 
                $insert = $stmt->execute();	
 
 $select_sub=mysqli_query($db,"SELECT *  FROM   route   where main_route='$fullname' and status = 'Sub route'");
$select_num_sub=mysqli_num_rows($select_sub);
if($select_num_sub !=0){
   	WHILE($get_sub=mysqli_fetch_array($select_sub)){
    	    //$name =$get['name']; 
    	    $start_pointz_sub =$get_sub['start_point']; 
    	    $sub_route_name =$get_sub['fullname']; 
			$sqlQ = "INSERT INTO  assign_route  (route,route_name,username,point,main_route) VALUES (?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssss", $route_initial, $route_name,$username,$start_pointz_sub,$sub_route_name); 
                $insert = $stmt->execute();	
			
}}	
	
 
 
 
  $sqlQ = "INSERT INTO  assign_route  (route,route_name,username,point,main_route) VALUES (?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssss", $route_initial, $route_name,$username,$end_point,$route_name); 
                $insert = $stmt->execute();	
}else{
	
	
			$delete=mysqli_query($db,"DELETE FROM   assign_route   WHERE route_name ='$route_name' and  username ='admin' ");

}

}else{
		$delete=mysqli_query($db,"DELETE FROM   assign_route   WHERE route_name ='$route_name' and  username ='admin' ");

	
}
}else{
$select=mysqli_query($db,"SELECT route_name   FROM   assign_route   WHERE route_name 	='$route_name' and point='$location' and   username ='admin' ");
$select_num=mysqli_num_rows($select);
if($select_num ==0){
	$select=mysqli_query($db,"SELECT name FROM   route   where fullname='$route_name'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_initial =$get['name']; 
    	   

	}
}
$sqlQ = "INSERT INTO  assign_route  (route,route_name,username,point) VALUES (?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssss", $route_initial, $route_name,$username,$location); 
                $insert = $stmt->execute();	
}else{
	
	
	$delete=mysqli_query($db,"DELETE FROM   assign_route   WHERE route_name ='$route_name' and point='$location' and  username ='admin' ");


}
}

?>