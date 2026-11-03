<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info group by month  order by date_insert Desc ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	  
             $date_insert =$get13['month']; 
         
		$newDate = $date_insert;
			 $newDate = "01-$date_insert";
			  
    $newDate = date("Ym", strtotime($newDate));  
			 
			 
			 $select_charge=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE   month='$date_insert' group by username");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){
			$account_location =$get_charge['account'];
			$username_charge =$get_charge['username'];

$select_charge_no=mysqli_query($db,"SELECT to_be  FROM  parcel_info WHERE status = 'To be Payed' and   month='$date_insert' and account='$account_location' and username ='$username_charge'");
$select_num_charge_no=mysqli_num_rows($select_charge_no);
$insert=mysqli_query($db,"UPDATE monthly_charges  SET no_to_be='$select_num_charge_no' WHERE  date_insert ='$date_insert' and  location='$account_location' and username ='$username_charge'");

$select_charge_no=mysqli_query($db,"SELECT paid  FROM  parcel_info WHERE status = 'paid' and   month='$date_insert' and account='$account_location' and username ='$username_charge'");
$select_num_charge_paid=mysqli_num_rows($select_charge_no);

	 $insert=mysqli_query($db,"UPDATE monthly_charges  SET no_paid='$select_num_charge_paid' WHERE  date_insert ='$date_insert' and  location='$account_location' and username ='$username_charge'");
	 
	 
$select_charge_no=mysqli_query($db,"SELECT Due payment  FROM  parcel_info WHERE  status = 'Due payment' and  month='$date_insert' and account='$account_location' and username ='$username_charge'");
$select_num_charge_due=mysqli_num_rows($select_charge_no);

	 $insert=mysqli_query($db,"UPDATE monthly_charges  SET no_due='$select_num_charge_due' WHERE  date_insert ='$date_insert' and  location='$account_location' and username ='$username_charge'");

	
	
	




}}
		 
			/* 
		
            $newDate = date("Ym", strtotime($date_insert));  
		$sql = $db->query("SELECT SUM(to_be ) AS  to_be  FROM parcel_info where   month='$date' and account='$trip'  ORDER BY month desc");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be =$data['to_be'];
				echo ''.number_format($to_be ,2).'<br>'.$newDate.'';
	/*			
	  $insert=mysqli_query($db,"INSERT INTO monthly_charges 
(to_be,location,date_insert,sort ) VALUES 
('$to_be','$trip','$date','$newDate')"); 


	   $insert=mysqli_query($db,"UPDATE monthly_charges  SET to_be='$to_be' WHERE  date_insert ='$date' and  location='$trip'"); */
	
		

	}}
	
	
	
?>