<?php
include 'connect.php';
$no="255715511420";
$select2=mysqli_query($db,"SELECT DISTINCT s_no FROM parcel_info LIMIT 2000,1000");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
		
    	   $s_no=$get12['s_no'];
			$s_no = substr($s_no, 1);
			$s_no_str = strlen($s_no);
			if($s_no_str ==12){
		
		$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://messaging-service.co.tz/api/sms/v1/text/single",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{ \"from\":\"RMNDR\", \"to\":\"$s_no\", \"text\":\"Sr Classic coach.Ndugu mteja wa classic coach tunapenda kuwahimiza kudai risiti ya manunuzi pindi mnaponunua mizigo yenu,Hii ni kuepuka usumbufu unaoweza kujiokeza pindi utakapokamatwa bila risiti.Ahsante.\" }",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic QWJkdWxheml6X2c6dGVjY3Jvc3MxMjM0NQ==",
    "Content-Type: application/json",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
			}
		
	}
	
	
}

		
?>