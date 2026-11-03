<?php
include 'connect.php';

$select2=mysqli_query($db,"SELECT *  FROM  manifest  WHERE trip_date='07/30/2022' and bus='5503AK05'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $q_left =$get12['q_left']; 
			$quantity=$get12['quantity'];
			$code=$get12['code'];
			

	if($q_left== $quantity){
		$insert2=mysqli_query($db,"UPDATE parcel_info SET store='out' WHERE code = '$code'");
	}else{
		$insert2=mysqli_query($db,"UPDATE parcel_info SET store='half_out' WHERE code = '$code'");
	}

		
	}
}else{
	echo "no";
}
?>