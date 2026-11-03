<?php
include 'connect.php';
$route_id = $_POST['route_id'];

$output = "";
$query = "SELECT name,id  FROM  route_points where route_id= '$route_id' and point='end' ";
  $result = mysqli_query($db, $query);
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["id"].'">'.$row["name"].'</option>';
  }
   echo $output;


?>