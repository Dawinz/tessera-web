<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
//fetch.php
if(isset($_POST["action"]))
{
 $connect = sr_mysqli('ticket');
 $output = '';
 if($_POST["action"] == "country")
 {
  $query = "SELECT end_route FROM assigned_routes WHERE start_route = '".$_POST["query"]."' GROUP BY end_route";
  $result = mysqli_query($connect, $query);

  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["end_route"].'">'.$row["end_route"].'</option>';
  }
 }

 echo $output;
}
?>
