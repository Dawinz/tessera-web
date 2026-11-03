<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
//fetch.php
if(isset($_POST["action"]))
{
 $connect = sr_mysqli('sr');
 $output = '';
 if($_POST["action"] == "country")
 {
  $query = "SELECT Name FROM vehicle_name WHERE date = '".$_POST["query"]."' GROUP BY Name ";
  $result = mysqli_query($connect, $query);
  $output .= '<option value="">Select Vehicles</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["state"].'">'.$row["Name"].'</option>';
  }
 }
 if($_POST["action"] == "state")
 {
  $query = "SELECT bus FROM vehicle_name WHERE trip_date = '".$_POST["query"]."' GROUP BY bus ";
  $result = mysqli_query($connect, $query);
  $output .= '<option value="">Select City</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["city"].'">'.$row["city"].'</option>';
  }
 }
 echo $output;
}
?>
