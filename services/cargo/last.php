<?php
include'connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['code']; 
		$code_plus = $code_plus + 1;

		echo "$code_plus";
	}
}
?>