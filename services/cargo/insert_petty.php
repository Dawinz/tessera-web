<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$firstname = $_POST['firstname'];
$date = $_POST['date'];
$status_em = $_POST['status_em'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);
$subject = $_POST['subject'];
$r_date = $_POST['r_date'];




	  $insert=mysqli_query($db,"INSERT INTO petty_cash VALUES('','$firstname','$date','$status_em','$detail','$r_date')");
echo $sr_code;	
	
    	  

?>