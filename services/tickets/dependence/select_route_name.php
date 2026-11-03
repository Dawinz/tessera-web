<?php
$route_name = $_POST['route_name'];
include '../connect.php';



$start_route2 = '';
$query = "SELECT fullname,route_type,id,main_id,badge,route_id FROM routes where fullname = '$route_name' or route_id_fullname='$route_name' ";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
				$start_route2 .= '<option value="'.$row["route_id"].'">'.$row["fullname"].'</option>';

	
}
echo $start_route2;
?>