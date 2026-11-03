<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config.php';
$fullname =  $_POST['fullname'];
$trip = $_POST['trip'];
$trip_assign = $_POST['trip_assign'];
$textareaValue=$_POST['textareaValue'];
$textareaValue = base64_encode($textareaValue);
$amount=$_POST['amount'];
$number2=$_POST['number'];
$date =$_POST['date'];
$dateStr = $_POST['dateStr'];
$receiver = $_POST['receiver'];

$n=5; 
function getName($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
} 
  

$code2 = getName($n); 
$code = 'src'.$code2.'';
$number =  str_replace("+","","$number2");
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
  CURLOPT_POSTFIELDS => "{ \"from\":\"NEXTSMS\", \"to\":\"$number\", \"text\":\"your luggage code is :$code\" }",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic dGVjY3Jvc3M6dGVjY3Jvc3MxMjM0NQ==",
    "Content-Type: application/json",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo "successfully";
   $insert=mysqli_query($db,"INSERT INTO luggages_account VALUES('','$textareaValue','$trip','$date','$amount','$fullname','$number2','$trip_assign','$dateStr','$code','$receiver','$number2')");

?>