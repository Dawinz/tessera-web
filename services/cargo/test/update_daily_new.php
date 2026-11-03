<?php
include '../connect.php';


$select1=mysqli_query($db,"SELECT *  FROM  parcel_info   group by date_insert  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['month'];
             $date_insert =$get13['date_insert']; 
			 
			 echo "$date_insert<br>";
			 
$newDate = date("Ym", strtotime($date_insert)); 
$select_users=mysqli_query($db,"SELECT *  FROM  parcel_info   WHERE date_insert = '$date_insert' group by username");
$select_num_users=mysqli_num_rows($select_users);
if($select_num_users !=0){
   	WHILE($get_users=mysqli_fetch_array($select_users)){
    	     
			$username =$get_users['username'];
			$location =$get_users['account'];


 
		$sql = $db->query("SELECT SUM(to_be ) AS  to_be,SUM(paid ) AS  paid  FROM parcel_info where   date_insert='$date_insert' and account='$location' and username='$username' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be =$data['to_be'];
				$paid =$data['paid'];
				echo ''.number_format($to_be ,2).'<br>'.$newDate.'';
			
			 $select_charge=mysqli_query($db,"SELECT *  FROM  daily_charges WHERE   date_insert ='$date_insert' and  location='$location'");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
				
$insert=mysqli_query($db,"INSERT INTO daily_charges 
(to_be,paid,location,date_insert,sort,username ) VALUES 
('$to_be','$paid','$location','$date_insert','$newDate','$username')"); 
}else{
$insert=mysqli_query($db,"UPDATE daily_charges  SET to_be='$to_be', paid='$paid' WHERE  date_insert ='$date_insert' and  location='$location'"); 

}
			




		}}else{
			echo "0.00";
		}


}}
			 
			 
			 
		/*
            $newDate = date("Ym", strtotime($date_insert));  
		$sql = $db->query("SELECT SUM(to_be ) AS  to_be  FROM parcel_info where   date_insert='$date_insert' and account='$trip'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be =$data['to_be'];
				echo ''.number_format($to_be ,2).'<br>'.$newDate.'';
				
	  $insert=mysqli_query($db,"INSERT INTO daily_charges 
(paid,location,date_insert,sort ) VALUES 
('$to_be','$trip','$date_insert','$newDate')"); 

/*
	  $insert=mysqli_query($db,"UPDATE monthly_charges  SET paid='$to_be' WHERE  date_insert ='$date' and  location='$trip'"); 

		}}else{
			echo "0.00";
		}

*/
		
	}
}

?>