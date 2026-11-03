<?php


$total_to_be = 0;
$total_paid = 0;
$select_manifest=mysqli_query($db,"SELECT to_be,paid FROM   manifest_entries   where bus ='$vehicle_name' and trip_date='$trip_date' and route_name='$current_route'");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
   	WHILE($get_manifest=mysqli_fetch_array($select_manifest)){
    	    $to_be_new =$get_manifest['to_be']; 
    	    $paid_new =$get_manifest['paid']; 
    	  
    	  
$total_to_be += $to_be_new;
$total_paid += $paid_new;

	}
} 

echo "$paid $to_pay";
$update=mysqli_query($db,"UPDATE  manifest  SET 
	paid='".$paid."',
	to_be='".$to_pay."',
	amount='".$to_pay+$paid."'
	where bus ='$vehicle_name' and trip_date='$trip_date'");	

?>