<?php
include '../connect.php';
require_once __DIR__ . '/../includes/tessera_tenant.php';
$tenantSql = tessera_sql_tenant_eq();
$route_id_start = $_POST['route_id_start'];
$select_main=mysqli_query($db,"SELECT route_name,id,main_id,sub_route_id,route_id,fullname,route_name  FROM  routes WHERE id='$route_id_start' AND $tenantSql");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
 
 $route_id= $get_main['route_id']; 
 $sub_route_id= $get_main['main_id']; 
 $route_name= $get_main['route_name']; 
 $fullname= $get_main['fullname']; 
 
}}

if($sub_route_id ==""){
	
}else{
 echo  '<option value="'.$route_id_start.'">'.$fullname.' ('.$route_name.')</option>';
}


$start_route2 = '';
$query = "SELECT fullname,route_type,id,main_id,badge FROM routes WHERE $tenantSql ";
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
?>