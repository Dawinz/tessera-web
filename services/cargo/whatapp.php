<?php
$code="56767";
$data = [
    'phone' => '255715511420', // Receivers phone
    'body' => '*SR CLASSIC COACH,*
*TAARIFA YA MAKUBALIANO NA FIDIA YA MZIGO KWAKO WENYE NAMBARI 54784.*
Ndugu mteja wa SR CLASSIC COACH,
tunapenda kukupa pole na kukushukuru kwa subira yako.kampuni ya SR CLASSIC COACH imemaliza zoezi la tathmini ya uharibifu  mizigo ilo ungua kwenye basi namba 6884AK05.tunaomba muhusika afike makao makuu yetu ya Tanzania yaliokua *Ilala mtaa wa lindi na iringa* kwa zoezi la majadliano na fidia.
*Muda ni kwanzia saa 2:30 asubui mpaka saa 9:00 mchana*
*muhusika anabidi afike na kitambulisho chake cha taifa*,
Kwa mara nyingine tunapenda kuchukua jukumu la kuwashukuru kwa subira yenu na kuapamoja na sisi katika kipindi hiki kigumu kwa pande zote mbili.asanteni sana an jioni njema', // Message
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