<?php
$select_combine_monthly=mysqli_query($db,"SELECT expenses FROM    monthly_account    where month ='$month' ");
$select_num_combine_monthly=mysqli_num_rows($select_combine_monthly);
if($select_num_combine_monthly !=0){
   	WHILE($get_combine_monthly=mysqli_fetch_array($select_combine_monthly)){
    	    $combine_expenses_monthly =$get_combine_monthly['expenses']; 
	}
	$total_expenses_combine = $combine_expenses_monthly+$amount;
$update=mysqli_query($db,"UPDATE  monthly_account SET expenses='$total_expenses_combine' where month ='$month'");

}else{
	$sqlQ = "INSERT INTO   monthly_account   (month,expenses) VALUES (?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ss", $month,$amount); 
                $insert = $stmt->execute();		
	
}
?>