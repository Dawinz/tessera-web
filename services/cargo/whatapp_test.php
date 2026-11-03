<?php
$data = [
    'phone' => '255715511420', // Receivers phone
    'template' => 'template_name', // Template name
    'namespace' => 'namespace_of_template', // Namespace of template
    'language' =>  ['code' => 'en', 'policy' => 'deterministic'], // Language parameters
];
$json = json_encode($data); // Encode data to JSON
// URL for request POST /message
$token = 'ork0odk5fsdmnwjw';
$instanceId = '349661';
$url = "https://api.chat-api.com/instance{$instanceId}/sendTemplate?token={$token}";
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