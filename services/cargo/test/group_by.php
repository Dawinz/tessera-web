<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info GROUP BY date_insert ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $date_insert =$get13['date_insert']; 
			$trip =$get13['account']; 

	$sql = $db->query("SELECT SUM(paid  ) AS  paid   FROM parcel_info where   date_insert='$date_insert'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid  =$data['paid'];
				
$update=mysqli_query($db,"UPDATE  daily_charges  SET paid ='$paid ',location='$trip' WHERE date_insert='$date_insert' ");
			
		}}else{
			echo "0.00";
		}

		
	}
}
echo $select_num1;
?>