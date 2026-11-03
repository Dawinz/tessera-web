<?php
include 'connect.php';

$select2=mysqli_query($db,"SELECT *  FROM WHERE trip_date='07/30/2022' and bus ='5503AK05'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $id =$get12['id']; 
			$bus=$get12['bus'];
			

	echo"$id $bus<br>";

		
	}
}else{
	echo "no";
}
?>