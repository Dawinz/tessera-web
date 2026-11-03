<?php
include '../connect/connect.php';
 $output = '';
  $query = "SELECT fullname FROM route WHERE status = 'Main' ";
  $result = mysqli_query($db, $query);
  $output .= '<option value="">Select Main Route</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["fullname"].'">'.$row["fullname"].'</option>';
  }
   echo $output;
?>