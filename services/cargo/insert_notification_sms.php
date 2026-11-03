<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
ini_set('max_execution_time', '0');
$select2=mysqli_query($db,"SELECT DISTINCT r_no  FROM  parcel_info Where id > 2700");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $s_no =$get12['r_no']; 
		
			$s_no = substr($s_no, 1);
$s_no_str = strlen($s_no);
			if($s_no_str ==12){
			
$sms="*Classic Coach*,
Ndugu mteja wa Classic Coach,Tunapenda kuwatangazia Route yetu mpya ya usafirishaji wa mizigo na abiria.
Route ya moja kwa moja kutoka *Dar-es-salaam > Tunduma > Lubumbashi > Kolowezi*.
Kwa maelezo zaidi wasiliana nasi.
Asante sana na siku njema";
 $data = [
    'phone' => ''.$s_no.'', // Receivers phone
    'body' => ''.$sms.'', // Message
];
$json = json_encode($data); // Encode data to JSON
// URL for request POST /message
$token = 'ork0odk5fsdmnwjw';
$instanceId = '349661';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/message?token='.$token;
// Make a POST request
$options = stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/json',
        'content' => $json
    ]
]);
// Send a request
$result = file_get_contents($url, false, $options);
 echo "yes <br>";
			}else{
			
			
			}




	}

}else{
	
}

?>