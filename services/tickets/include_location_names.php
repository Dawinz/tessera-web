<?php
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
//index.php
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT name FROM locations_names ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}

?>          <?php
//index.php
$bus = '';
$query = "SELECT name FROM vehicle WHERE " . tessera_sql_tenant_eq() . " ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $bus .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}

?>   

    <?php
//index.php
$username_users = '';
$query = "SELECT username,firstname,surname FROM users ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $username_users .= '<option value="'.$row["username"].'">('.$row["firstname"].' '.$row["surname"].')'.$row["username"].'</option>';
}



include 'connect.php';
session_start();


$username=$_SESSION['username'];
	$select14=mysqli_query($db,"SELECT *  FROM  assigned_routes WHERE username ='".$_SESSION['username']."' order by id asc limit 1");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	   
 $start_route_first = '<option value="'.$get134['start_route'].'">'.$get134['start_route'].'</option>';
}
}
?>