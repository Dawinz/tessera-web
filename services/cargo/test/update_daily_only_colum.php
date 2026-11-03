<?php
include '../connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info   group by date_insert  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['month'];
             $date_insert =$get13['date_insert']; 
			 
		
			 
        $newDate = date("Ym", strtotime($date_insert)); 
$select_users=mysqli_query($db,"SELECT *  FROM  parcel_info   WHERE date_insert = '$date_insert' group by username");
$select_num_users=mysqli_num_rows($select_users);
if($select_num_users !=0){
   	WHILE($get_users=mysqli_fetch_array($select_users)){
    	     
			$username =$get_users['username'];
			$location =$get_users['account'];


$select_users2=mysqli_query($db,"SELECT *  FROM  daily_charges   WHERE  date_insert ='$date' and  location='$location' and username='$username'");
$select_num_users2=mysqli_num_rows($select_users2);
if($select_num_users2 !=0){
	
}else{
		 echo "$date_insert<br>";
$insert=mysqli_query($db,"INSERT INTO daily_charges 
(location,date_insert,sort,username ) VALUES 
('$location','$date_insert','$newDate','$username')");  
}



}}




/*
	  $insert=mysqli_query($db,"UPDATE monthly_charges  SET paid='$paid' WHERE  date_insert ='$date' and  location='$trip'"); 

		}}else{
			echo "0.00";
		}

*/
		
	}
}

?>