<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';

$trip = $_POST['trip'];
$trip_assign = $_POST['trip_assign'];
$date =$_POST['date'];
$dateStr = $_POST['dateStr'];

$select1=mysqli_query($db,"SELECT *  FROM employee WHERE number_e = '$employee' ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $firstname1=$get13['firstname']; 
			$surname2=$get13['surname']; 
		 

		
	}
}
	
 $insert=mysqli_query($db,"INSERT INTO warning_form VALUES('','$trip','$trip_assign','$firstname1 $surname2','','')");

?>