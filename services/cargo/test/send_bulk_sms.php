<?php
include '../connect.php' ;

$select=mysqli_query($db,"SELECT DISTINCT s_no FROM parcel_info WHERE CHAR_LENGTH(s_no) = 13 and  s_no  LIKE '+255%' order by id desc");
$select_num=mysqli_num_rows($select);

if($select_num !=0){

WHILE($get=mysqli_fetch_array($select)){
	
		$s_no = $get['s_no'];	
		
		$s_no = substr($s_no, 1);
			$s_no_str = strlen($s_no);
			if($s_no_str ==12){
				
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
  CURLOPT_POSTFIELDS => "{ \"from\":\"Sr Classic\", \"to\":\"$s_no\", \"text\":\"Safirisha mizigo yako kwa uharaka katika vipindi hivi vya sikukuu.Tunapenda kuwambia kwamba Tumeongeza idadi ya mabasi kwa dhumuni la kusafirisha mizigo kwa uharaka na hapo hapo kuongeza upatikanaji wa nafasi kwa abiria wanaosafiri vipindi hivi vya sikukuu.\" }",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic QWJkdWxheml6X2c6dGVjY3Jvc3MxMjM0NQ==",
    "Content-Type: application/json",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
				
			}
		
}
}



?>