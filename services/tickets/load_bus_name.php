<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
//index.php

$route_id_modal = $_POST['route_id_modal'];
$route_day_id_modal = $_POST['route_day_id_modal'];

$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT bus FROM route_day WHERE route_id='$route_id_modal' AND id='$route_day_id_modal' ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["bus"].'">'.$row["bus"].'</option>';
}
echo $start_route2;
?>