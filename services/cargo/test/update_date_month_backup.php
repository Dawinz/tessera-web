<?php
include '../connect.php';



$select1=mysqli_query($db,"SELECT *  FROM  routes ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $route_full_name =$get13['route_full_name'];
 echo $route_full_name;

$select12=mysqli_query($db,"SELECT *  FROM  daily_charges WHERE location='$route_full_name'  ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
			$id =$get132['id']; 
			$date =$get132['date_insert']; 
			 $newDate = date("m-Y", strtotime($date));  
	echo "$route_full_name $id $date ... $newDate<br>";
$update=mysqli_query($db,"UPDATE  daily_charges SET month='$newDate' WHERE id='$id'");

		
	}
}



}}

?>