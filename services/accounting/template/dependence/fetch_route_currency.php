<?php
include '../connect/connect.php';
$query = $_POST['route_name'];
$output="";
$query = "SELECT Currency FROM route_currency  WHERE route_name = '$query' order by id desc";
$result = mysqli_query($db, $query);
$select_route_num=mysqli_num_rows($result);
if($select_route_num!=1){
  $output .= '<option value="Choose Currency">Choose Currency</option>';
}
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["Currency"].'">'.$row["Currency"].'</option>';
  }
   echo $output;
?>