<?php
include 'connect.php';
$uploader= "abdulaziz";
			$quantity= "1";
			$left= "1"; 
$select2=mysqli_query($db,"SELECT *  FROM  parcel_info where trip_date='01/27/2021'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $id =$get12['id']; 
			$trip_date =$get12['trip_date']; 
			$username= $get12['r_name']; 
			$code= $get12['code']; 
			$amount= $get12['amount']; 
			$paid= $get12['paid']; 
			$to_be= $get12['to_be']; 
			$status=  $get12['status']; 
			$detail=  $get12['detail']; 
			$bus=  $get12['bus']; 
	 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$username','$amount','$trip_date','$bus','$paid','$to_be','$status','$uploader','$left','$quantity','$detail')");
	echo"$id<br>";

		
	}
}
?>