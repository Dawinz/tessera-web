<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id = $_POST['id'];
$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE id='$id' and seats='0' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	echo "cant";
}else{
$insert=mysqli_query($db,"DELETE from manifest WHERE id = '$id'");}

?>