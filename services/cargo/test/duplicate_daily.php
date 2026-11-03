<?php
include '../connect.php';


$select1=mysqli_query($db,"SELECT *  FROM  daily_charges     ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $username =$get13['username']; 
			$date_insert =$get13['date_insert'];
			$location  =$get13['location'];
			
	
			
			$select12=mysqli_query($db,"SELECT *  FROM  daily_charges  WHERE username='$username' and date_insert='$date_insert' and location='$location'  ");
$select_num12=mysqli_num_rows($select12);
	
	if($select_num12>=2){
		
		$select13=mysqli_query($db,"SELECT *  FROM  daily_charges  WHERE username='$username' and date_insert='$date_insert' and location='$location'    ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	    $id =$get133['id']; 
    	    $username =$get133['username']; 
			$date_insert =$get133['date_insert'];
			$location  =$get133['location'];
			
				echo "$id $username $date_insert $location $select_num12<br>";

		
		
	}
			echo "$id $username $date_insert $location $select_num12<br>";
				$insert=mysqli_query($db,"DELETE from daily_charges WHERE id = '$id'");


			
			
}}}}
?>