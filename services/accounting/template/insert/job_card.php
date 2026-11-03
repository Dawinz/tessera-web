<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
session_start();

include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');
$insert_date = $_POST['insert_date'] ?? ($_POST['date'] ?? '');
$route_name = $_POST['choose_route'] ?? ($_POST['route_name'] ?? '');
if ($insert_date === '' || $route_name === '') {
	http_response_code(400);
	echo 'invalid';
	exit;
}
$insert_date= date("Y-m-d",strtotime($insert_date));
if ($insert_date === '' || $insert_date === '1970-01-01') {
	http_response_code(400);
	echo 'invalid date';
	exit;
}
echo $insert_date;
$first_date = date("Y-m-01", strtotime($insert_date));
$year = date("Y", strtotime($insert_date));
$month = date("F", strtotime($insert_date));
$select=mysqli_query($db,"SELECT *  FROM   job_card   where date='$insert_date' and route_name='$route_name'");
$select_num=mysqli_num_rows($select);
if($select_num ==0){
	
$select_detail=mysqli_query($db,"SELECT *  FROM   job_card_detail   where first_date='$first_date' and year='$year'");
$select_num_detail=mysqli_num_rows($select_detail);
if($select_num_detail ==0){
	
$sqlQ = "INSERT INTO  job_card_detail  (first_date,month,year) VALUES (?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sss", $first_date, $month,$year); 
                $insert = $stmt->execute();	
}
$bootstrap = "bg-success";
$sqlQ = "INSERT INTO  job_card  (date,username,bootstrap,route_name,month,year) VALUES (?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssss", $insert_date,$_SESSION['username'],$bootstrap,$route_name,$month,$year); 
                $insert = $stmt->execute();	
	
}else{
	echo "found";
}



?>