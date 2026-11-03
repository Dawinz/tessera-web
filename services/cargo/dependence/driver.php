<?php
$vehicle = $_POST['vehicle_value'];

include '../connect.php';
 $output = '';
 $query = "SELECT driver  FROM vehicles WHERE name = '$vehicle' ";
  $result = mysqli_query($db, $query);
  
  while($row = mysqli_fetch_array($result))
  {
   $output .= ''.$row["driver"].'';
  }
   echo $output;

?>