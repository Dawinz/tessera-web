<?php
require_once __DIR__ . '/../includes/sr_env.php';
$connect = sr_mysqli('account');
$particular_Currency = '';
$query_Currency = "SELECT * FROM route_currency WHERE route_name='$route_name'";
$result_Currency = mysqli_query($connect, $query_Currency);
$select_Currency=mysqli_num_rows($result_Currency);

if($select_Currency ==0){
	  //$particular_Currency .= '<option value="">No Currency Assigned</option>';

}else if($select_Currency > 1){
 // $particular_Currency .= '<option value="">Choose Currency</option>';
}
while($row_Currency = mysqli_fetch_array($result_Currency))
{
 $particular_Currency .= '<option value="'.$row_Currency["Currency"].'">'.$row_Currency["Currency"].'</option>';
}

?>