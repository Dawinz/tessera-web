<?php
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
									$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   date_insert ='$date_insert' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
				echo " [' $date_insert',  $amount,      $amount, $amount ,],";
			
		}}else{
			echo "Nothing";
		}	


		
	}
}else{
	echo "no";
}
		
?>