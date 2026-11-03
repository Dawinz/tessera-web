<?php

include '../connect.php';

$select2=mysqli_query($db,"SELECT *  FROM  parcel_info where   date_insert =' 10/31/2022' and month ='10/2022' GROUP BY (username)");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $username =$get12['username']; 

$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where  username='$username' and date_insert =' 10/31/2022' and month ='10/2022'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['paid'];
				echo ''.$username.' '. $amount.',<br>';
			
		}}else{
			echo "Nothing";
		}	


}}

?>