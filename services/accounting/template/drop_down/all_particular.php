<?php
require_once __DIR__ . '/../includes/sr_env.php';
$connect = sr_mysqli('account');
$particular_category_all = '';
$query_all = "SELECT name FROM particular_category ";
$result_all = mysqli_query($connect, $query_all);
$select_category_all=mysqli_num_rows($result_all);
while($row_all = mysqli_fetch_array($result_all))
{
 $particular_category_all .= '<option value="'.$row_all["name"].'">'.$row_all["name"].'</option>';
}

?>