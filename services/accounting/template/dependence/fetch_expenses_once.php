<?php
$acc_name = $_POST['acc_name'];

include '../connect/connect.php';


 $output = '';
 $query = "SELECT name  FROM particular_category WHERE category = '$acc_name' ";
  $result = mysqli_query($db, $query);
  $output .= '<option value="Choose Category">Choose Category</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
  }
   echo $output;

?>