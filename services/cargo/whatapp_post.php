<?php
include 'connect.php';
$select135=mysqli_query($db,"SELECT *  FROM  manifest  WHERE trip_date='12/02/2021' and bus='6884AK05'");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	    $code=$get1335['code']; 
$select=mysqli_query($db,"SELECT *  FROM  parcel_info  WHERE code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $s_no=$get['r_no'];  
			$code=$get['code'];  
			$s_name=$get['s_name']; 
			$r_name=$get['r_name'];   
			$s_no = substr($s_no, 1);
			$s_no_str = strlen($s_no);
			if($s_no_str ==12){
 $sms="
 *SR CLASSIC COACH,*
*TAARIFA YA MAKUBALIANO NA FIDIA YA MZIGO KWAKO WENYE NAMBARI $code.*
Ndugu mteja wa SR CLASSIC COACH,
tunapenda kukupa pole na kukushukuru kwa subira yako.kampuni ya SR CLASSIC COACH imemaliza zoezi la tathmini ya uharibifu  mizigo ilo ungua kwenye basi namba 6884AK05.tunaomba muhusika afike makao makuu yetu ya Tanzania yaliokua *Ilala mtaa wa lindi na iringa* kwa zoezi la majadliano na fidia.
*Muda ni kwanzia saa 2:30 asubui mpaka saa 9:00 mchana*.
*muhusika anabidi afike na kitambulisho chake cha taifa*,
Kwa mara nyingine tunapenda kuchukua jukumu la kuwashukuru kwa subira yenu na kuapamoja na sisi katika kipindi hiki kigumu kwa pande zote mbili.asanteni sana an jioni njema

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
			}else{
			
			
			}		

}}}}
?>