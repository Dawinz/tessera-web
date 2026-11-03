<?php
require_once __DIR__ . '/../includes/sr_env.php';
$connect = sr_mysqli('account');
$particular_category = '';
$query = "SELECT name FROM particular_category WHERE category='Income'";
$result = mysqli_query($connect, $query);
$select_category=mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
 $particular_category .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}

?>