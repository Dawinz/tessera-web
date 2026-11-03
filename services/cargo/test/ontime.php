<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT * FROM `parcel_info` WHERE trip_date='2024-01-01' and account='Tunduma to Lubumbashi'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	  
             $code =$get13['code']; 
             $bus =$get13['bus']; 
      $insert=mysqli_query($db,"UPDATE manifest  SET bus='$bus' WHERE  trip_date='2024-01-01' and  code='$code'"); 
    
	 
			 echo "$code<br>";
			 

}}
?>