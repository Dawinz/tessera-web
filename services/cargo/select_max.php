<?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT MAX(CAST(code AS UNSIGNED)) FROM parcel_info  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['MAX(CAST(code AS UNSIGNED))']; 
		$code_plus = $code_plus + 1;
echo $code_plus;
		
	}
}
?>