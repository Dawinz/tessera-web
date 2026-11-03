<?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT count(*)
FROM information_schema.columns
WHERE where `10` ='present' or `1` = 'present' and date='5/2020'");
$select_num1=mysqli_num_fields($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $firstname=$get13['name']; 
		   
echo "$select_num1";
		
	}
}
	
?>