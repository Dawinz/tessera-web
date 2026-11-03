<?php
$apiKey = '980f4974-7813-4995-a0c0-7d945f12a099';
$apiSecret = 'b5b97c14-65d1-4337-b87a-b3a6c29cf809';
$accountApiCredentials = $apiKey . ':' . $apiSecret;

$base64Credentials = base64_encode($accountApiCredentials);
$authHeader = 'Authorization: Basic ' . $base64Credentials;

$sendData = '{ "messages" : [ { "content" : "Hello SMS World from PHP", "destination" : "27727103819" } ] }';

$options = array(
    'http' => array(
        'header' => array("Content-Type: application/json", $authHeader),
        'method' => 'POST',
        'content' => $sendData,
        'ignore_errors' => true
    )
);

$sendResult = file_get_contents('https://rest.smsportal.com/bulkmessages', false, stream_context_create($options));

$status_line = $http_response_header[0];
preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
$status = $match[1];

if ($status === '200') {
    echo "Success:\n";
    var_dump($sendResult);
} else {
    echo "Failure:\n";
    var_dump($sendResult);
}
?>