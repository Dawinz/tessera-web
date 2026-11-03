<?php
include 'connect.php';
	$select25_update=mysqli_query($db,"SELECT * FROM  date_total  ");
$select_num25_update=mysqli_num_rows($select25_update);
if($select_num25_update !=0){
   	WHILE($get125_update=mysqli_fetch_array($select25_update)){
    	    $date_choose =$get125_update['date']; 
			echo "$date_choose";
}}
			
	?>
