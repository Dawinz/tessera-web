<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$username_checked=$_SESSION['username'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i A', time());

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'connect.php';
include 'connect_account.php';
$name = $_POST['name'];
$name = preg_replace('/\s+/', '_',$name);
$driver = $_POST['driver'];
$conductor= $_POST['conductor'];
$route_update= $_POST['route_update'];
$date= $_POST['time'];
$date = date("Y-m-d", strtotime($date));  
$id=$_POST['id'];
$changed="";
$date_account="";
$select222=mysqli_query($db,"SELECT *  FROM  vehicle_name  where id='$id'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $bus_name =$get122['Name']; 
			$date2 =$get122['date']; 
			$Driver_og =$get122['Driver']; 
			$Conductor_og =$get122['Conductor']; 
			$Route =$get122['Route']; 
			$disable2 =$get122['disable2']; 

		
	}
}
if($disable2!="disabled"){


if($name !=$bus_name){
	$changed .=" Vehicle name Changed  from $bus_name to $name <br>";
}
if($date !=$date2){
		$changed .="Trip date  Changed from $date2 to $date <br>";

}
if($driver !=$Driver_og){
		$changed .="Drivers name Changed from $Driver_og to $driver <br>";

}
if($conductor !=$Conductor_og){
		$changed .="Drivers name Changed from $Conductor_og to $Conductor_og <br>";

}
if($route_update !=$Route){
		$changed .="Route name Changed from $Route to $route_update <br>";
	

}

if($changed!=""){
update_manifests($db_account,$db,$bus_name,$date2,$name,$driver,$conductor,$date,$Route,$route_update,$id,$username_checked,$date_insert,$date_time_insert,$changed);
}else{

}





}else{
echo "closed";
}
function update_manifests($db_account,$db,$bus_name,$date2,$name,$driver,$conductor,$date,$Route,$route_update,$id,$username_checked,$date_insert,$date_time_insert,$changed){

$date_time ="$date_insert @ $date_time_insert";
$account_icon = '<i class="typcn typcn-pen"></i>';
mysqli_query($db,"UPDATE vehicle_name SET Name='$name',Driver ='$driver',conductor='$conductor',date='$date',Route='$route_update',changed='$changed',changed_user='$username_checked',change_time='$date_time' WHERE id ='$id'");
mysqli_query($db,"UPDATE manifest SET trip_date='$date',bus ='$name' WHERE trip_date ='$date2' and bus ='$bus_name'");


mysqli_query($db_account,"UPDATE  vehicle_account   SET 
	bus='$name',trip_date='$date'
	WHERE  route_name='$Route' and bus='$bus_name' and trip_date='$date2' ");


mysqli_query($db_account,"UPDATE  manifest   SET 
	bus='$name',trip_date='$date',changed='$changed',changed_user='$username_checked',change_time='$date_time',icon='$account_icon'
	WHERE  route_name='$Route' and bus='$bus_name' and trip_date='$date2' ");


}
?>