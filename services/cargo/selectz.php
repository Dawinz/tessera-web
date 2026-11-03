<?php
include 'connectz.php';
$id_c = 14265;
$select2=mysqli_query($db,"SELECT *  FROM  parcel_info");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   $id=$get12['id'];
		   $code2=$get12['code'];
		$id_c =$id_c + 1;
			$code2 = "$code2.1";


	echo "$id $id_c $code2<br>";
	   $insert=mysqli_query($db,"UPDATE parcel_info SET id='$id_c',code ='$code2'WHERE id ='$id'");

		
	}
	
}
?>