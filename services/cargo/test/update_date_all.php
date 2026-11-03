<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['date_insert']; 
			 $newDate = date("m-Y", strtotime($date));  
	echo "$date ... $newDate<br>";
$update=mysqli_query($db,"UPDATE  parcel_info SET month='$newDate' WHERE id='$id'");

		
	}
}

?>