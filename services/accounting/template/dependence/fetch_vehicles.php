<?php

include '../connect/connect_cargo.php';
 $output = '';
 $query = "SELECT name  FROM vehicles ";
  $result = mysqli_query($db_up, $query);
  $select_route_num=mysqli_num_rows($result);

 $output .= '<option value="Choose Vehicle">Choose Vehicle</option>';

  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
  }
   echo $output;

?>