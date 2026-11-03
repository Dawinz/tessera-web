<?php 
require_once dirname(__DIR__) . '/includes/sr_env.php';
$cfg = sr_db_config('account');
$servername = $cfg['host'];
$username = $cfg['user'];
$password = $cfg['pass'];
$dbname = $cfg['name'];
$route_name = $_GET['route_name'];
$date = $_GET['date'];
$conn = sr_mysqli($dbname) or die('Connection failed');

if(!empty($_FILES)){     
    $uploadDir = "uploads/jobcard/";
    $temp = explode(".", $_FILES["file"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
    if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir . $newfilename)) {
        $mysqlInsert = "INSERT INTO attachment (file_name,trip_date,route_name,ogname)VALUES('".$newfilename."','$date','$route_name','".$_FILES["file"]["name"]."')";
        mysqli_query($conn, $mysqlInsert);
    }   
}
?>