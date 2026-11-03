<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';


date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d h:i:s A', time());

include 'connect.php';
$select1=mysqli_query($db,"SELECT timer,id  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (status='booked')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $timer= $get13['timer']; 
 $id= $get13['id']; 

$datetime1 = date_create($date_insert);
  $datetime2 = date_create($timer);
 
  // Calculates the difference between DateTime objects
  $interval = date_diff($datetime1, $datetime2);
 
  // Printing result in years & months format
$delete_interal =  $interval->format("%r%H");

echo "$delete_interal";

if($delete_interal<="00"){
	echo "delete";
	echo $delete_interal;
	$insert=mysqli_query($db,"DELETE from booking WHERE " . tessera_sql_tenant_eq() . " AND (id = '$id')");
}else{
	
}
}}	



?>