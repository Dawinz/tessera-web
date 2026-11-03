<?php
$part_category = $_POST['part_category_exp'];

include '../connect/connect.php';
 $output = '';
 $query = "SELECT name  FROM particulars WHERE category = '$part_category' ";
  $result = mysqli_query($db, $query);
  $output .= '<option value="">Choose Particular</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
  }
   echo $output;

?>