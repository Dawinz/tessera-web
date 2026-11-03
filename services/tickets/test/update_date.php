<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  manifest  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['trip_date']; 
					 $newDate = date("m-Y", strtotime($date));  

		$day = date('d', strtotime($date));
$month = date('m', strtotime($date));
$year = date('Y', strtotime($date));
$sort = "$year$month$day";
$update=mysqli_query($db,"UPDATE  manifest SET sort='$sort' WHERE trip_date='$date'");

		
	}
}

?>