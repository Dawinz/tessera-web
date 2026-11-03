<?php 
include_once 'config.php';
$date = $_POST['date'];
$nameOfDay = date('N', strtotime($date));
$date2 =$nameOfDay+1;
$route = $_POST['country_id'];
if (isset($_POST['country_id'])) {
	$query = "SELECT * FROM trip where weekend NOT LIKE '%$date2%' AND route= '$route' ";
	$result = $db->query($query);
	if ($result->num_rows > 0 ) {
			echo '<option value="">Select city</option>';
		 while ($row = $result->fetch_assoc()) {
		 	echo '<option value='.$row['id'].'>'.$row['trip_title'].'</option>';
		 }
	}else{

		echo '<option>Destination not found</option>';
	}

}elseif (isset($_POST['state_id'])) {
	 

	$query = "SELECT * FROM cities where region_id=".$_POST['state_id'];
	$result = $db->query($query);
	if ($result->num_rows > 0 ) {
			echo '<option value="">Select Address</option>';
		 while ($row = $result->fetch_assoc()) {
		 	echo '<option value='.$row['id'].'>'.$row['name'].'</option>';
		 }
	}else{

		echo '<option>No Address Found!</option>';
	}

}


?>