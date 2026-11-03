<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$id = $_POST['reserved_id'];
include 'connect.php';


$select=mysqli_query($db,"SELECT *  FROM  booking  WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
   $start_route =$get['start_route'];  	
   $end_route =$get['end_route'];  	

	
}}


$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$start_route' AND end_route='$end_route' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			
	$insert=mysqli_query($db,"UPDATE  booking SET status='complete',book_status='disabled',cost='$fare_price' WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
			
}}else{
	$select14=mysqli_query($db,"SELECT *  FROM  sub_route WHERE start_route='$start_route' AND end_route='$end_route' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			
	$insert=mysqli_query($db,"UPDATE  booking SET status='half_complete',book_status='disabled',cost='$fare_price' WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
			echo "$fare_price";
}}
}


?>