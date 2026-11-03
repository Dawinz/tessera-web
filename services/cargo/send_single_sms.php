<?php
$s_no='255715511420';
$sms="
*OFFER*
*SR CLASSICCOACH* 
Tunakupatia ticket moja bure kutoka Dar-es-salaam kulekea Lubumbashi DRC Congo pindi mteja wetu atapofikisha ticket nane(8) za sr classic coach.
Na pia itatoa ticket moja  bure kwa abiria ataokua  na ticket 10 za sr classic kutoka Lubumbashi DRC Congo kuelekwa Dar es salaam.Save *150,000* na zaidi kwakukata ticket na Sr classic coach

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
?>