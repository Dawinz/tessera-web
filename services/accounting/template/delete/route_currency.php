<?php
include '../connect/connect.php';

$id=$_POST['id'];
$Currency=$_POST['Currency'];


$select=mysqli_query($db,"SELECT status,route_name FROM   route_currency   where id='$id'  ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_name =$get['route_name']; 
    	    $status =$get['status']; 
			
}}

$select_route=mysqli_query($db,"SELECT currency,id FROM   route   where fullname='$route_name' and status='$status' ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $route_currency =$get_route['currency']; 
    	    $route_id =$get_route['id']; 
			
}}
$new_array = explode(",", "$route_currency");
 if (($key = array_search($Currency, $new_array)) !== false) {
	  unset($new_array[$key]);
	  $new_checked =  implode(",",$new_array);
	 
 }
 
 $update=mysqli_query($db,"UPDATE  route  SET 
	currency='$new_checked'
	where id='$route_id'");	
	
$insert=mysqli_query($db,"DELETE from route_currency WHERE id = '$id'");
?>