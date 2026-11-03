<?php
include '../connect.php';
$route_name_cont_prev = $_POST['route_name_cont_prev'];
$route_name_id = $_POST['route_name_id'];

for($count = 0; $count < count($_POST["route_name_cont"]); $count++)

	{
	$route_name_cont = $_POST['route_name_cont'] [$count];	
	$contact_name = $_POST['contact_name'][$count];	
	$select1=mysqli_query($db,"SELECT *  FROM  route_contact WHERE route_id='$route_name_id'  and contact='$contact_name' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){

$insert=mysqli_query($db,"INSERT INTO route_contact (route_name,name,contact,route_id) 
VALUES                                    ('$route_name_cont_prev','$route_name_cont','$contact_name','$route_name_id')");

}else{
echo "found";
}
	}
	?>