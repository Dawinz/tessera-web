<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  vehicle_name ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $parcel_route =$get13['Route']; 
    	    $id =$get13['id']; 
    	   //echo "$parcel_route";
$select=mysqli_query($db,"SELECT *  FROM  routes where route_full_name='$parcel_route' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $currency_symbol =$get['currency_symbol']; 
    	   echo "$currency_symbol <br>";
    }}
	   $insert=mysqli_query($db,"UPDATE vehicle_name  SET currency='$currency_symbol' WHERE  id ='$id' ");


    }}