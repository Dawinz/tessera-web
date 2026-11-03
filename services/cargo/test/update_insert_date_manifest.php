<?php

include 'connect.php';

$select_charge=mysqli_query($db,"SELECT code,id,parcel_route,bus,trip_date  FROM  manifest  ");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){

			$code =$get_charge['code'];
			$id =$get_charge['id'];
			$parcel_route  =$get_charge['parcel_route'];
			$bus  =$get_charge['bus'];
			$trip_date  =$get_charge['trip_date'];
	
			
			$select_date=mysqli_query($db,"SELECT date_insert  FROM  parcel_info where code='$code'  ");
$select_num_date=mysqli_num_rows($select_date);
if($select_num_date !=0){
   	WHILE($get_date=mysqli_fetch_array($select_date)){

			$date_insert =$get_date['date_insert'];
			
			
			
			
			
}}
$select_date=mysqli_query($db,"SELECT parcel_date   FROM  manifest_dates   WHERE  bus='$bus' and parcel_date='$date_insert' and route='$parcel_route' ");
$select_num_date=mysqli_num_rows($select_date);
if($select_num_date ==0){

$sqlQ = "INSERT INTO  manifest_dates  (trip_date,bus,parcel_date,route) VALUES (?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssss", $trip_date, $bus,$date_insert,$parcel_route); 
                $insert = $stmt->execute();	

}			
			
			
}}
?>