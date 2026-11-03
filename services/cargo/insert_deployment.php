<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$location = $_POST['location'];
$employee = $_POST['employee'];
$status = $_POST['status_'];
$monthyear = $_POST['monthyear'];
$day = $_POST['day'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('h:i A', time());
$date_staturs = $date_insert = date('A', time());

												$select1=mysqli_query($db,"SELECT *  FROM employee WHERE number_e = '$employee' ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $firstname1=$get13['firstname']; 
			$surname2=$get13['surname']; 
		 

		
	}
}
		/////////////////////////////////////////
//////////////////////////////////////////////////////		
									
$select1=mysqli_query($db,"SELECT *  FROM m_roll WHERE date='$monthyear'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
		$select1=mysqli_query($db,"SELECT *  FROM deploy WHERE emp_id='$employee' && t_status='$date_staturs' && date='$day/$monthyear' && location='$location'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
echo"Username Exist";

}else{
			  $insert=mysqli_query($db,"INSERT INTO deploy VALUES('','$location','$firstname1 $surname2','$employee','$date_insert','$day/$monthyear','$status','$date_staturs')");

   $insert=mysqli_query($db,"UPDATE m_roll SET `$day`='$status'  WHERE date ='$monthyear' && $day='$day' && em_id='$employee'");
}
///////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
}else{
	
											$select1=mysqli_query($db,"SELECT *  FROM employee ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $firstname=$get13['firstname']; 
			$surname=$get13['surname']; 
			$number_e=$get13['number_e'];   
		  $insert=mysqli_query($db,"INSERT INTO m_roll VALUES('','$firstname $surname','$number_e','$monthyear','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')");

		
	}
}
		   $insert=mysqli_query($db,"UPDATE m_roll SET `$day`='$status'  WHERE date ='$monthyear' && $day='$day' && em_id='$employee'");
			  $insert=mysqli_query($db,"INSERT INTO deploy VALUES('','$location','$firstname1 $surname2','$employee','$date_insert','$day/$monthyear','$status','$date_staturs')");
}
	
											?>
