<?php 
include_once 'connect.php';

$route = $_POST['country_id'];
if (isset($_POST['country_id'])) {
	$query = "SELECT * FROM warning where category='$route' ";
	$result = $db->query($query);
	if ($result->num_rows > 0 ) {
			echo '<option value="">Choose Warning Sentence</option>';
		 while ($row = $result->fetch_assoc()) {
		 	echo '<option value='.$row['id'].'>('.$row['number'].') '.$row['warning'].'</option>';
		 }
	}else{

		echo '<option>'.$route.'</option>';
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