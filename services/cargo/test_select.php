<?php
include 'connect.php';
$select233=mysqli_query($db,"SELECT * FROM `manifest` WHERE code='67242'  ");
$select_num233=mysqli_num_rows($select233);
if($select_num233 !=0){
 echo "$select_num233";
}else{
	
}
?>