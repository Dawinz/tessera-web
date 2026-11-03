<?php
include 'connect.php';
$id =$_GET['code'];
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id' and amount_remain='0'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
echo "yes";
}else{
	echo "no";
}
?>