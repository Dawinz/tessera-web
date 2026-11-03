<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$ticket_id = $_POST['ticket_id'];


$select1=mysqli_query($db,"SELECT route_id,sub_route_id  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND id='$ticket_id'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $route_id= $get13['route_id']; 
 $sub_route_id= $get13['sub_route_id']; 
 
}}



if($sub_route_id ==""){

	$select135=mysqli_query($db,"SELECT fullname,id  FROM  routes WHERE route_id =' $route_id' and route_type='Main Route' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 

 $fullname= $get1335['fullname'];

 $id_route= $get1335['id'];
 
}}

 echo  '<option value="'.$id_route.'">'.$fullname.'</option>';

$start_route2 = '';
$query = "SELECT fullname,route_type,id,main_id,badge FROM routes WHERE " . (function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1') . " ";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
	if($row['route_type']=="Sub Route"){
		
		$select_main_id=mysqli_query($db,"SELECT fullname,id,route_name  FROM  routes WHERE route_id ='".$row["main_id"]."'  ");
$select_num_main_id=mysqli_num_rows($select_main_id);
if($select_num_main_id !=0){
   	WHILE($get_main_id=mysqli_fetch_array($select_main_id)){
 

 $route_name= $get_main_id['route_name'];

 
}}
		 $start_route2 .= '<option value="'.$row["id"].'">'.$row["fullname"].' ('.$route_name.') </option>';

	}else{
		

		
				$start_route2 .= '<option value="'.$row["id"].'">'.$row["fullname"].'</option>';

	}
	
}

echo  $start_route2;



}else{
	$select_main=mysqli_query($db,"SELECT route_name,id  FROM  routes WHERE route_id='$route_id'");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
 
 $route_name= $get_main['route_name']; 
 
}}


	$select135=mysqli_query($db,"SELECT fullname,id  FROM  routes WHERE route_id =' $sub_route_id' and route_type='Sub Route' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 

 $fullname= $get1335['fullname'];

 $id_route= $get1335['id'];
 
}}


 echo  '<option value="'.$id_route.'">'.$fullname.' ('.$route_name.')</option>';
$start_route2 = '';
$query = "SELECT fullname,route_type,id,main_id,badge FROM routes WHERE " . (function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1') . " ";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
	if($row['route_type']=="Sub Route"){
		
		$select_main_id=mysqli_query($db,"SELECT fullname,id,route_name  FROM  routes WHERE route_id ='".$row["main_id"]."'  ");
$select_num_main_id=mysqli_num_rows($select_main_id);
if($select_num_main_id !=0){
   	WHILE($get_main_id=mysqli_fetch_array($select_main_id)){
 

 $route_name= $get_main_id['route_name'];

 
}}
		 $start_route2 .= '<option value="'.$row["id"].'">'.$row["fullname"].' ('.$route_name.') </option>';

	}else{
		

		
				$start_route2 .= '<option value="'.$row["id"].'">'.$row["fullname"].'</option>';

	}
	
}

echo  $start_route2;

}

?>