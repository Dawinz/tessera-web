<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$username_check = $_SESSION['username'];
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('m-Y', time());


$select_charge=mysqli_query($db,"SELECT *  FROM  routes ");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){
			$account_location =$get_charge['route_full_name'];
		
$sql = $db->query("SELECT SUM(to_be ) AS  to_be,SUM(paid ) AS  paid  FROM parcel_info where   month='$date_insert' and account='$account_location' and username ='$username' and open !='yes' and username !=''  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be =$data['to_be'];
				$paid =$data['paid'];

                if($to_be==""){

				}else{
				
				}

		
                $select_charge_no=mysqli_query($db,"SELECT to_be  FROM  parcel_info WHERE status = 'To be Payed' and   month='$date_insert' and account='$account_location' and username ='$username' and open !='yes' ");
                $select_num_charge_no=mysqli_num_rows($select_charge_no);
                
                $select_charge_no=mysqli_query($db,"SELECT paid  FROM  parcel_info WHERE status = 'paid' and   month='$date_insert' and account='$account_location' and username ='$username' and open !='yes' ");
                $select_num_charge_paid=mysqli_num_rows($select_charge_no);
                         
                     
                $select_charge_no=mysqli_query($db,"SELECT Due payment  FROM  parcel_info WHERE  status = 'Due payment' and  month='$date_insert' and account='$account_location' and username ='$username' and open !='yes' ");
                $select_num_charge_due=mysqli_num_rows($select_charge_no);
                
            
	
//$insert=mysqli_query($db,"UPDATE monthly_charges  SET to_be='$to_be',paid='$paid',no_to_be='$select_num_charge_no',no_paid='$select_num_charge_paid',no_due='$select_num_charge_due' WHERE  date_insert ='$date_insert' and  location='$account_location' and username='$username_check'"); 




		}}



    }}

		 

	
	
?>