<?php
include 'connect.php';
				$select12=mysqli_query($db,"
SELECT *
FROM (
(SELECT * FROM dummy )
UNION DISTINCT
(SELECT * FROM dummy )
)
 AS i
GROUP BY s_no ORDER BY id desc
");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
WHILE($get132=mysqli_fetch_array($select12)){
	    $s_no =$get132['s_no'];
		$s_no = substr($s_no, 1);
			$r_no_str = strlen($s_no);
		$sms="
BONUS BONUS BONUS

*SR CLASSIC COACH* 


À tous les clients SR CLASSIC, nous tenons à vous informer que lorsque vous complétez 9 billets de dar es salaam à lubumbashi, vous recevrez un billet gratuit.  Avec 10 billets pour Lubumbashi à Dar es Salaam, vous obtiendrez à nouveau un billet gratuit.  Veuillez protéger vos billets et en obtenir un gratuitement.  Merci beaucoup et bienvenue.


VOYAGER C’EST CLASSIQUE, BIEN VOYAGER C’EST SR CLASSIC COACH. ☺️
";
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
	$sms="
*Offer*
*SR CLASSIC COACH.* 

Tunakupatia ticket moja bure kutoka Dar-es-salaam kulekea Lubumbashi DRC Congo pindi mteja utakapofikisha ticket tisa(9) za sr classic coach.
Na pia itatoa ticket moja  bure kwa abiria ataokua  na ticket 10 za sr classic kutoka Lubumbashi DRC Congo kuelekwa Dar es salaam.Save *150,000* na zaidi kwakukata ticket na Sr classic coach.
Tunakutakia mchana mwema na ijuma pili njema ☺️
";
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
}}

?>