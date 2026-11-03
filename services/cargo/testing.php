				<?php
				include 'config.php';
    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where booking_date LIKE '2020-03-19%'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
			$id = $get1['id'];
			echo $id;
			
    	  }}
		  ?>