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
			 echo "$date_insert<br>";
			 

			 
			 
			 $select_charge=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE   month='$date_insert' group by username");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){
			$account_location =$get_charge['account'];
			$username_charge =$get_charge['username'];



	
	
	

		$sql = $db->query("SELECT SUM(to_be ) AS  to_be  FROM parcel_info where   month='$date_insert' and account='$account_location' and username ='$username_charge'  ORDER BY month desc");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be =$data['to_be'];
				echo ''.number_format($to_be ,2).'<br>'.$newDate.'';
/*	
 $insert=mysqli_query($db,"INSERT INTO monthly_charges 
(paid,location,date_insert,sort,username) VALUES 
('$to_be','$account_location','$date_insert','$newDate','$username_charge')"); */
$insert=mysqli_query($db,"UPDATE monthly_charges  SET to_be='$to_be',sort='$newDate' WHERE  date_insert ='$date_insert' and  location='$account_location' and username='$username_charge'"); 
	   
	
		}}


}}



}}
		 

	
	
?>