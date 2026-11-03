<?php
include 'connect.php';


date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('m-Y', time());


$select_charge=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE date_insert='$date_insert' ");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){



			$trip =$get_charge['location'];
			$username =$get_charge['username'];
		


            $sql = $db->query("SELECT SUM(to_be ) AS  to_be,SUM(paid ) AS  paid  FROM parcel_info where   month='$date_insert' and account='$trip' and username ='$username' and open !='yes' and username !=''  ");
            if ($sql->num_rows > 0) {
                $response = "";
    
                while($data = $sql->fetch_array()) {
                    $to_be =$data['to_be'];
                    $paid =$data['paid'];
    
                    if($to_be==""){
    
                    }else{
                    
                    }

    
    }}

    $select_charge_no=mysqli_query($db,"SELECT to_be  FROM  parcel_info WHERE status = 'To be Payed' and   month='$date_insert' and account='$trip' and username ='$username' and open !='yes' and username !=''");
    $select_num_charge_no=mysqli_num_rows($select_charge_no);
    
    $select_charge_no=mysqli_query($db,"SELECT paid  FROM  parcel_info WHERE status = 'paid' and   month='$date_insert' and account='$trip' and username ='$username' and open !='yes' and username !='' ");
    $select_num_charge_paid=mysqli_num_rows($select_charge_no);
             
         
    $select_charge_no=mysqli_query($db,"SELECT Due payment  FROM  parcel_info WHERE  status = 'Due payment' and  month='$date_insert' and username ='$username' and open !='yes' and username !=''");
    $select_num_charge_due=mysqli_num_rows($select_charge_no);
    


echo "$username $to_be $paid <br>";

    //$insert=mysqli_query($db,"UPDATE monthly_charges  SET to_be='$to_be',paid='$paid',no_to_be='$select_num_charge_no',no_paid='$select_num_charge_paid',no_due='$select_num_charge_due' WHERE  date_insert ='$date_insert' and  location='$trip' and username='$username'"); 


}}
?>