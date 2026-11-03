<?php


include '../connect.php';
 $output = '';
 $query = "SELECT route_name  FROM  routes where route_type= 'Main' ";
  $result = mysqli_query($db, $query);
  $output .= '<option value="Choose Main route">Choose Main route</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["route_name"].'">'.$row["route_name"].'</option>';
  }
   echo $output;


?>