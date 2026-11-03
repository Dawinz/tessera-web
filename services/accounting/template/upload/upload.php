<?php 
require_once dirname(__DIR__) . '/includes/tessera_staff_boot.php';
require_once dirname(__DIR__) . '/includes/sr_env.php';
$cfg = sr_db_config('account');
$servername = $cfg['host'];
$username = $cfg['user'];
$password = $cfg['pass'];
$dbname = $cfg['name'];
$trip_date =$_POST['trip_date_ext'];
$bus =$_POST['bus_ext'];
$point =$_POST['route_start_ext'];
$current_route =$_POST['current_route'];
$route_name =$_POST['main_route_ext'];
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