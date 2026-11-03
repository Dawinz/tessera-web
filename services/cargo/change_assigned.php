<?php

?>
<?php


session_start();

include 'connect.php';
$change = $_POST['change'];
$route_id_assigned = $_POST['route_id_assigned'];
$username_names = $_POST['username_names'];


if($change == "on"){
	

$select17=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id_assigned'");
$select_num17=mysqli_num_rows($select17);
if($select_num17 !=0){
   	WHILE($get137=mysqli_fetch_array($select17)){
 $id=$get137['id']; 
 $start_route= $get137['start_route']; 
 $end_route= $get137['end_route']; 
 $fare_price= $get137['fare_price']; 
 $route_id= $get137['route_id'];  
 $detail= $get137['detail']; 
 $route_name= $get137['route_name']; 
 $route_full_name= $get137['route_full_name']; 

 


}}	


$insert=mysqli_query($db,"INSERT INTO assigned_routes (route_id,start_route,end_route,username,selected,active,active_tab,detail,route_name,route_full_name,status) VALUES 
('$route_id','$start_route','$end_route','$username_names','true','active','show active','$detail','$route_name','$route_full_name','sender')");	



}else{
	$username=$_SESSION['username'];

	$insert=mysqli_query($db,"DELETE from assigned_routes WHERE route_id ='$route_id_assigned' and username='$username_names'");


}
 echo "succesfull";



?>