<?php
include '../connect/connect.php';
$query = $_POST['current_route'];
$output="";
  $query = "SELECT open_date,id FROM cash  WHERE route_name = '$query' ORDER BY ID DESC";
  $result = mysqli_query($db, $query);
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["id"].'">'.$row["open_date"].'</option>';
  }
   echo $output;
?>