<?php
include '../connect.php';
$trip="Tunduma to Lubumbashi";
$select1=mysqli_query($db,"SELECT *  FROM  manifest   ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	   
			$id =$get13['id']; 
			$code =$get13['code']; 
	$new_code = "TL$code";
$update=mysqli_query($db,"UPDATE  manifest SET code='$new_code' WHERE id='$id'");
		
	}
}

?>