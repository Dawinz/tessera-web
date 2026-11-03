<?php
include '../connect.php';


$select1=mysqli_query($db,"SELECT *  FROM  parcel_info   group by date_insert  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['date_insert'];
             $date_insert =$get13['date_insert']; 
			 
			 echo "$date_insert<br>";
			 
      			 $select_charge=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE   date_insert='$date_insert' group by username");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){
			$account_location =$get_charge['account'];
			$username_charge =$get_charge['username'];

$select_charge_no=mysqli_query($db,"SELECT to_be  FROM  parcel_info WHERE status = 'To be Payed' and   date_insert='$date_insert' and account='$account_location' and username ='$username_charge'");
$select_num_charge_no=mysqli_num_rows($select_charge_no);
$insert=mysqli_query($db,"UPDATE daily_charges  SET no_to_be='$select_num_charge_no' WHERE  date_insert ='$date_insert' and  location='$account_location' and username ='$username_charge'");

$select_charge_no=mysqli_query($db,"SELECT paid  FROM  parcel_info WHERE status = 'paid' and   date_insert='$date_insert' and account='$account_location' and username ='$username_charge'");
$select_num_charge_paid=mysqli_num_rows($select_charge_no);

	 $insert=mysqli_query($db,"UPDATE daily_charges  SET no_paid='$select_num_charge_paid' WHERE  date_insert ='$date_insert' and  location='$account_location' and username ='$username_charge'");
	 
	 
$select_charge_no=mysqli_query($db,"SELECT Due payment  FROM  parcel_info WHERE  status = 'Due payment' and  date_insert='$date_insert' and account='$account_location' and username ='$username_charge'");
$select_num_charge_due=mysqli_num_rows($select_charge_no);

	 $insert=mysqli_query($db,"UPDATE daily_charges  SET no_due='$select_num_charge_due' WHERE  date_insert ='$date_insert' and  location='$account_location' and username ='$username_charge'");

	
	
	




}}
		
	}
}

?>