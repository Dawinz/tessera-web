<?php
include 'connect.php';
$trip_date=$_POST['choosen'];
$select2=mysqli_query($db,"SELECT *  FROM  manifest WHERE trip_date='$trip_date'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   $bus=$get12['bus'];
		
			



		
	}
		echo "$bus";
}
?>