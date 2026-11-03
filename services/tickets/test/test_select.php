<?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  assigned_routes Group by route_id ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
echo "$select_num1";
}

?>