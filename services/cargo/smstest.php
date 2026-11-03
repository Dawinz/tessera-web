<?php
$phone = "+255715511420";
    	  
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
  CURLOPT_POSTFIELDS => "{ \"from\":\"NEXTSMS\", \"to\":\"$number\", \"text\":\"bulk\" }",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic dGVjY3Jvc3M6dGVjY3Jvc3MxMjM0NQ==",
    "Content-Type: application/json",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
?>


  