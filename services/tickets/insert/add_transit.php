<?php
include '../connect.php';
$route_name_cont_this = $_POST['route_name_cont_this'];
$route_name_id = $_POST['route_name_id_this'];
$route_transit = $_POST['route_transit'];
$badge = '<span class="badge bg-danger">Transit</span>';

$select1=mysqli_query($db,"SELECT route_prev   FROM  route_transit  WHERE route_name='$route_transit'  and route_prev ='$route_name_cont_this'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
$insert=mysqli_query($db,"INSERT INTO route_transit (route_prev,route_id,route_name) 
VALUES                                    ('$route_transit','$route_name_id','$route_name_cont_this')");


$update=mysqli_query($db,"UPDATE  routes SET transit='$badge' WHERE  fullname='$route_name_cont_this'");
echo $route_name_cont_this;
}else{
echo "found";	
}


?>