<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
//fetch.php
if(isset($_POST["action"]))
{
 $connect = sr_mysqli('ticket');
 $output = '';

  $query = "SELECT end_route FROM sub_route WHERE start_route = '".$_POST["query"]."' and main_route_no='".$_POST["route_id"]."' GROUP BY end_route";
  $result = mysqli_query($connect, $query);
  $output .= '<option value="">Choose Ending Point</option>';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["end_route"].'">'.$row["end_route"].'</option>';
  }
 

 echo $output;
}
?>
