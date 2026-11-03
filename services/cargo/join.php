<?php
include 'connect.php';

$select2=mysqli_query($db,"SELECT manifest.amount, expenses.amount FROM manifest,expenses WHERE manifest.trip_date = '05/04/2021'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
echo "$select_num2";
		
}else{
	echo"np";
}
?>