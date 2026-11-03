<?php

$select_manifest=mysqli_query($db,"SELECT income,balance FROM   manifest   where bus ='$busname' and trip_date='$trip_date' ");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
   	WHILE($get_manifest=mysqli_fetch_array($select_manifest)){
    	    $combine_manifest =$get_manifest['income']; 
    	    $combine_manifest_balance =$get_manifest['balance']; 
	}
}



$update=mysqli_query($db,"UPDATE  manifest  SET 
	 income ='".$combine_manifest+$amount."',
	 balance ='".$combine_manifest_balance+$amount."'
	where bus ='$busname' and trip_date='$trip_date'");	


?>