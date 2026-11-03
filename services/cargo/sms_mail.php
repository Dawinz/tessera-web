<?php
require "PHPMailer/PHPMailerAutoload.php";
include 'config.php';
$textareaValue = $_POST['textareaValue'];

    																
				include 'config.php';
    $select1=mysqli_query($db,"SELECT * FROM tkt_passenger ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
			$phone = $get1['phone'];
    	  
$number =  str_replace("+","","$phone");
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.nextsms.co.tz/api/sms/v1/text/single",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{ \"from\":\"NEXTSMS\", \"to\":\"$number\", \"text\":\"$textareaValue\" }",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic dGVjY3Jvc3M6dGVjY3Jvc3MxMjM0NQ==",
    "Content-Type: application/json",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);



  
    	}
	echo "successfully";

}
?>