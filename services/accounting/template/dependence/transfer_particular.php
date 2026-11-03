<?php
$route_checked = $_POST['route_checked'];

include '../connect/connect_cargo.php';
 $output = '';
 $query = "SELECT date  FROM vehicle_name WHERE Route = '$route_checked' order by id desc limit 20 ";
  $result = mysqli_query($db_up, $query);
  $output .= '<option value="Choose Particular">Choose Date</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["date"].'">'.$row["date"].'</option>';
  }
   echo $output;

?>