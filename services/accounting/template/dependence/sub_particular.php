<?php
include '../connect/connect.php';
$query = $_POST['query'];
 $output = '';
 $query = "SELECT name  FROM particular_sub where particular_name='$query' ";
  $result = mysqli_query($db, $query);
  $select_route_num=mysqli_num_rows($result);

 $output .= '<option value=" ">Choose Item</option>';

  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
  }
   echo $output;
?>