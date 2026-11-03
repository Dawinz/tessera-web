<?php
include 'connect.php';

$select_charge=mysqli_query($db,"SELECT *  FROM  parcel_info where date_insert='2025-10-04' ");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){
			$code =$get_charge['code'];
			$question =$get_charge['question'];
			echo "$code $question <br>";
			
	$insert=mysqli_query($db,"UPDATE parcel_info  SET scanned ='$question' WHERE  code ='$code' "); 

			}}
?>