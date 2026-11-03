<?php


include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM vehicle_name  order by id desc limit 2,1");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date =$get12['date']; 
			$name=$get12['Name'];
			

	echo $name;
								$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date = '$date' and bus='$name' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
			$percentage = $total_amount_t;
$totalWidth = 11000000;

$new_width = ($percentage / $totalWidth) * 100 ;
			echo $new_width;
		}}else{
			echo "0.00";
		}

		
	}
}
?>