<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
//fetch.php
if(isset($_POST["action"]))
{
 $connect = sr_mysqli('cargo');
 $output = '';
 if($_POST["action"] == "country")
 {
  $query = "SELECT bus FROM Manifest WHERE trip_date = '".$_POST["query"]."' GROUP BY bus ";
  $result = mysqli_query($connect, $query);
  $output .= '<option value="">Select Vehicles</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["bus"].'">'.$row["bus"].'</option>';
  }
 }
 if($_POST["action"] == "state ")
 {
  $query = "SELECT bus FROM Manifest WHERE trip_date = '".$_POST["query"]."' GROUP BY bus ";
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
