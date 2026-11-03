<?php
include'connect.php';
			$select125=mysqli_query($db,"SELECT *  FROM  check_category LIMIT 8  ");
$select_num12=mysqli_num_rows($select125);
if($select_num12 !=0){
	WHILE($get1322=mysqli_fetch_array($select125)){
		    	    $name =$get1322['name'];

		
	echo '"'.$name.'",';
	
	}
}

?>