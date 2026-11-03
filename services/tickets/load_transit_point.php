<?php
require_once __DIR__ . '/includes/sr_env.php';
$connect = sr_mysqli('ticket');
if(isset($_POST["start_location"]))
{
$start_location = $_POST['start_location'];
	 $start_location = preg_replace('/_/',' ', $start_location);
$route_id = $_POST['route_id'];
$start_route2 = '';
$query = "
SELECT name
FROM route_points where route_id='$route_id' and point ='starting' and start_location='$start_location'

UNION DISTINCT
SELECT name
FROM  sub_route_points Where route_id='$route_id' and point ='starting' and start_location='$start_location'
";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
 echo $start_route2;
}
if(isset($_POST["ending_location"]))
{
	$route_id = $_POST['route_id'];

	$ending_location = $_POST['ending_location'];
	 $ending_location = preg_replace('/_/',' ', $ending_location);
	 

$end_route = '';
$query = "
SELECT name
FROM route_points where route_id='$route_id' and point ='end' and end_location='$ending_location'
UNION DISTINCT
SELECT name
FROM  sub_route_points Where route_id='$route_id' and point ='end' and end_location='$ending_location'

";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $end_route .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
	 echo $end_route;
	
}

?>