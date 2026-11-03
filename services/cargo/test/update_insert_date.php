<?php

include 'connect.php';

$select_charge=mysqli_query($db,"SELECT code,id  FROM  manifest  ");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){

			$code =$get_charge['code'];
			$id =$get_charge['id'];
			
			$select_date=mysqli_query($db,"SELECT date_insert  FROM  parcel_info where code='$code'  ");
$select_num_date=mysqli_num_rows($select_date);
if($select_num_date !=0){
   	WHILE($get_date=mysqli_fetch_array($select_date)){

			$date_insert =$get_date['date_insert'];
			
			
			
			
			
}}
    $insert=mysqli_query($db,"UPDATE manifest  SET parcel_date='$date_insert' WHERE   id='$id' "); 
			
			
			
}}
?>