							 												<?php
											include'connect.php';
											$select1=mysqli_query($db,"SELECT *  FROM situation  ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $name=$get13['ob'];   
		$ob= $name + 1;
		$invID = str_pad($ob, 3, '0', STR_PAD_LEFT);
		echo $invID;
	}
}
	
											?>