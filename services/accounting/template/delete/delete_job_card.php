<?php
include '../connect/connect.php';
$id = $_POST['id'];
$date = $_POST['date'];
$route_name = $_POST['route_name'];
$select_status=mysqli_query($db,"SELECT * FROM   job_card_entries   where date='$date' and route_name='$route_name'  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status ==0){
$insert=mysqli_query($db,"DELETE from job_card WHERE id = '$id'");

}else{
	echo "found";
}

?>