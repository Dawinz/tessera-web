<?php

$current_route = $_POST['current_route'];
$bus = $_POST['bus'];

include '../connect/connect_cargo.php';
//include '../connect/connect_ticket.php';


 $output = '';
 $query = "SELECT date  FROM vehicle_name WHERE Name = '$bus' and Route='$current_route' order by date desc";
  $result = mysqli_query($db_up, $query);
  $output .= '<option value="Choose Date">Choose Date</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["date"].'">'.$row["date"].'</option>';
  }
   echo $output;
   
?>