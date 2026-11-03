<?php
$trip_dates = $_POST['trip_dates'];

include '../connect/connect_cargo.php';
 $output = '';
 $query = "SELECT Name  FROM vehicle_name WHERE date = '$trip_dates' order by id desc limit 20 ";
  $result = mysqli_query($db_up, $query);
  $output .= '<option value="Choose Particular">Choose Bus Name</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["Name"].'">'.$row["Name"].'</option>';
  }
   echo $output;

?>