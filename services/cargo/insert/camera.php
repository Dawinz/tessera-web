<?php
include 'connect.php';
$situation = $_POST['situation'];
$subject = $_POST['subject'];
$detail = $_POST['detail'];
$monthyear = $_POST['monthyear'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('h:i A', time());
$detail = base64_encode($detail);
   $insert=mysqli_query($db,"INSERT INTO camera VALUES('','$situation','$subject','$detail','$monthyear','$date_insert')");
   if($situation=="high"){

   }else{

   }

?>