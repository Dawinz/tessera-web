<?php

$select_combine=mysqli_query($db,"SELECT expenses FROM   account_transaction   where bus ='$busname' and trip_date='$trip_date' ");
$select_num_combine=mysqli_num_rows($select_combine);
if($select_num_combine !=0){
   	WHILE($get_combine=mysqli_fetch_array($select_combine)){
    	    $combine_expenses =$get_combine['expenses']; 
	}
	$total_expenses_combine = $combine_expenses+$amount;
$update=mysqli_query($db,"UPDATE  account_transaction SET expenses='$total_expenses_combine' where bus ='$busname' and trip_date='$trip_date'");

}else{
	$sqlQ = "INSERT INTO   account_transaction  (bus,trip_date,expenses) VALUES (?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sss", $busname,$trip_date,$amount); 
                $insert = $stmt->execute();		
	
}
?>