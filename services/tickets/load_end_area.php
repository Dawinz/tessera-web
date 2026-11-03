<?php
require_once __DIR__ . '/includes/sr_env.php';
$end_route_modal = $_POST['end_route_modal'];
//index.php
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT name FROM locations_names ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
echo "<option>$end_route_modal</option>";
echo $start_route2;
?>