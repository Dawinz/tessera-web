<?php
$state_id = $_POST['state_id'];
				include 'config.php';
    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no = '$state_id'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
			$id = $get1['trip_id_no'];
			echo $id;
			
    	  }}
session_start();
$_SESSION['state_id'] = $id;




?>