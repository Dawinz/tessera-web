<?php

include 'connect/connect.php';
 $route_name = '';
 $query = "SELECT fullname  FROM route WHERE status = 'Main' ";
  $result = mysqli_query($db, $query);
  $route_name .= '<option value="">All Routes</option>';
  while($row = mysqli_fetch_array($result))
  {
   $route_name .= '<option value="'.$row["fullname"].'">'.$row["fullname"].'</option>';
  }
 

?>