<?php


include '../connect.php';
$transit_routes = $_POST['transit_routes'];

$start_route2 = '';
$query = "SELECT name FROM  route_points WHERE route_id='$transit_routes' and point='ending'";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
				$start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';

	
}

echo  $start_route2;

?>