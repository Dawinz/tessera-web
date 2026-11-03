<?php
include '../connect.php';
$code="";
$route_id = $_POST['route_id'];
$select14=mysqli_query($db,"SELECT DISTINCT code FROM contact_code ORDER BY code ASC ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
			$code .= "<option value=".$get134['code'].">".$get134['code']."</option>";
}
}
echo "$code";

?>