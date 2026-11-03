<?php
include '../connect.php';
$route_id = $_POST['route_name_id'];

for($count = 0; $count < count($_POST["code_name"]); $count++)

	{
	$code_name = $_POST['code_name'] [$count];	
	
$select=mysqli_query($db,"SELECT *  FROM  contact_code WHERE route_id='$route_id' and code='$code_name'");
$select_num=mysqli_num_rows($select);
if($select_num ==0){

$insert=mysqli_query($db,"INSERT INTO contact_code (route_id,code) 
VALUES                                    ('$route_id','$code_name')");

}else{
	echo "found";
}

	
	
	


	}
?>