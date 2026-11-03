<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  expenses ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['code']; 
		$code_plus = $code_plus + 1;

		
	}
}
$firstname = $_POST['firstname'];
$date = $_POST['date'];
$status_em = $_POST['status_em'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);
$subject = $_POST['subject'];
$pay = $_POST['pay'];
$pay = preg_replace('/[^0-9.]+/', '', $pay);
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$date_time_insert = date(' h:i A', time());



	  $insert=mysqli_query($db,"INSERT INTO expenses VALUES('','$firstname','$date','$detail','$pay','$status_em','income','$date_time_insert','$date_insert','$code_plus')");
	echo"$code_plus";
	
    	  

?>