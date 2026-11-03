<?php
include 'connect.php';

$select2=mysqli_query($db,"SELECT *  FROM  manifest ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $code =$get12['code']; 
		
		
$select22=mysqli_query($db,"SELECT *  FROM  parcel_info where code='$code' ");
$select_num22=mysqli_num_rows($select22);
if($select_num22 !=0){
   	WHILE($get122=mysqli_fetch_array($select22)){
    	    $username =$get122['username']; 
		
		 $insert=mysqli_query($db,"UPDATE manifest SET parcel_uploader='$username' WHERE code ='$code'");



		
	}
}


		
	}
}
?>
