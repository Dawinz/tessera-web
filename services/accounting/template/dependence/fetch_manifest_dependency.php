<?php
$trip_date = $_POST['query'];
$choose_route = $_POST['choose_route'];

include '../connect/connect_cargo.php';
 $output = '';
 $query = "SELECT Name  FROM vehicle_name WHERE date = '$trip_date' and Route ='$choose_route'  order by id asc";
  $result = mysqli_query($db_up, $query);
  $output .= '<option value="">Select Vehicle Name </option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["Name"].'">'.$row["Name"].'</option>';
  }
   echo $output;

?>