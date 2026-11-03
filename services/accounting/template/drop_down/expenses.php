<?php
require_once __DIR__ . '/../includes/sr_env.php';
$connect = sr_mysqli('account');
$particular_category_exp = '';
$query_exp = "SELECT name FROM particular_category WHERE category='Expenses'";
$result_exp = mysqli_query($connect, $query_exp);
$select_category_exp=mysqli_num_rows($result_exp);
while($row_exp = mysqli_fetch_array($result_exp))
{
 $particular_category_exp .= '<option value="'.$row_exp["name"].'">'.$row_exp["name"].'</option>';
}

?>