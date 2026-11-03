
<?php
include '../connect.php';
require_once __DIR__ . '/../includes/tessera_tenant.php';
$tenant_id = (int) tessera_current_tenant_id();
if ($tenant_id < 1) {
	echo 'no tenant';
	exit;
}

for($count = 0; $count < count($_POST["starting_point"]); $count++)

	{
		
$starting_point = $_POST["starting_point"] [$count];		
$end_point = $_POST["end_point"] [$count];		
$Currnecy = $_POST["Currnecy"] [$count];		
$price = $_POST["price"] [$count];		
$Initial = $_POST["Initial"] [$count];		
$days = $_POST["days"] [$count];		
$detail = $_POST["detail"] [$count];		
$route_type = $_POST["route_type"] [$count];		
$route_name_change = $_POST["route_name"] [$count];		
$price = preg_replace('/[^0-9.]+/', '', $price);			
$detail = str_replace("'" , "^", $detail);
$Initial = str_replace("'" , "^", $Initial);
$detail = base64_encode($detail);

if($route_name_change !="Route name"){

$select_route_name=mysqli_query($db,"SELECT *  FROM  routes Where route_name = '$route_name_change' AND tenant_id='$tenant_id' ");
$select_num_route_name=mysqli_num_rows($select_route_name);
if($select_num_route_name !=0){
   	WHILE($get_route_name=mysqli_fetch_array($select_route_name)){
    	    $route_id_fullname =$get_route_name['fullname']; 
    	    $route_id =$get_route_name['route_id']; 
	}
}
}else{
	$route_name_change="";
}


if($starting_point != $end_point ){

$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$Currnecy'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial_cur= $get1373['initial']; 


	}
	}
	
$select1=mysqli_query($db,"SELECT *  FROM  routes ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus2 =$get13['route_id']; 
		$code_plus = $code_plus2 + 1;

	}
}else{
	
	$code_plus=0;
}


$select1=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$starting_point' and end_route='$end_point' and route_type='$route_type' AND tenant_id='$tenant_id' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	

	if($route_type == "Main Route"){
$badge= '<span class="badge bg-primary">Main Route</span>';
	$fullname = "$starting_point to $end_point";
$insert=mysqli_query($db,"INSERT INTO routes (start_route,end_route,fare_price,fare_price_international,sub_route_id,route_id,detail,route_name,currency,currency_symbol,active_date,arrival,departure,contact_no1,contact_no2,fullname,transit,terms,route_type,badge,main_id,route_id_fullname,vip_price,id_type,tenant_id) 
VALUES
('$starting_point','$end_point','$price','0','','$code_plus','$detail','$Initial','$Currnecy','$initial_cur','$days','','','','','$fullname','','','$route_type','$badge','0','','0','0','$tenant_id')");

}else{
$badge= '<span class="badge bg-warning">Sub Route</span>';
	$fullname = "$starting_point to $end_point";
$insert=mysqli_query($db,"INSERT INTO routes (start_route,end_route,fare_price,fare_price_international,sub_route_id,route_id,detail,route_name,currency,currency_symbol,active_date,arrival,departure,contact_no1,contact_no2,fullname,transit,terms,route_type,badge,main_id,route_id_fullname,vip_price,id_type,tenant_id) 
VALUES
('$starting_point','$end_point','$price','0','','$code_plus','$detail','$Initial','$Currnecy','$initial_cur','$days','','','','','$fullname','','','$route_type','$badge','$route_id','$route_id_fullname','0','0','$tenant_id')");

}


	if ($insert) {
	echo "succesfull";
	} else {
		echo 'error';
	}
   	}	else{

	}
	

$select1=mysqli_query($db,"SELECT *  FROM  assigned_routes Group by route_id ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==1){
$update=mysqli_query($db,"UPDATE  assigned_routes SET active='active',active_tab='show active',selected='true'");
}	
	
}else{
	echo "wrong";
}
	
	}
?>