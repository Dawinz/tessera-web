<?php
include 'connect.php';
$uploader= "abdulaziz";
			$quantity= "1";
			$left= "1"; 
$select2=mysqli_query($db,"SELECT *  FROM  manifest  GROUP BY (trip_date)");
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