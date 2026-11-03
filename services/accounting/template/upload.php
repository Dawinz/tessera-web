<?php 
require_once __DIR__ . '/includes/sr_env.php';
$cfg = sr_db_config('account');
$servername = $cfg['host'];
$username = $cfg['user'];
$password = $cfg['pass'];
$dbname = $cfg['name'];
$trip_date =$_GET['trip_date'];
$bus =$_GET['bus'];
$point =$_GET['point'];
$current_route =$_GET['current_route'];
$route_name =$_GET['route_name'];
$conn = sr_mysqli($dbname) or die('Connection failed');

if(!empty($_FILES)){     
    $uploadDir = "uploads/";
    $temp = explode(".", $_FILES["file"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
    if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir . $newfilename)) {
        $mysqlInsert = "INSERT INTO attachment (file_name,trip_date,bus,route_name,main_route,ogname,point)VALUES('".$newfilename."','$trip_date','$bus','$current_route','$route_name','".$_FILES["file"]["name"]."','$point')";
        mysqli_query($conn, $mysqlInsert);
    }   
}
?>