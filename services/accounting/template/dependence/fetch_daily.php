<?php
include '../connect/connect.php';
$query = $_POST['query'];
echo $query;
$output="";
  $query = "SELECT name FROM particulars  WHERE category = '$query' ";
  $result = mysqli_query($db, $query);
  $output .= '<option value="">Select Particular</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
  }
   echo $output;
?>