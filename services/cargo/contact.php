<?php
include('WhatsappAPI.php'); // include given API file
$wp = new WhatsappAPI("1020", "66700c59325404e8b00c2aa2f22303a8ea0267a9");
include 'connect.php';
$select=mysqli_query($db,"SELECT *  FROM  number   ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
WHILE($get=mysqli_fetch_array($select)){
 $r_no=$get['number'];  
 // create an object of the WhatsappAPI class with your user id and api key

$number = $r_no; // NOTE: Phone Number should be with country code
$message = 'Hello World'; // You can use WhatsApp Code to compose text messages like *bold*, ~Strikethrough~, ```Monospace```
$media_url = 'http://www.africau.edu/images/default/sample.pdf'; // Direct global accessible URL of the file, image, docs etc.
 // Max file size should be 10MB otherwise you may get error.
$group_name = 'My Test Group 😋';
$caption = 'Its my Caption'; // For media files



/*

  UNCOMMENT YOUR REQUIRED FUNCTIONS FROM BELOW

*/

// Send Text Message to number
 $status = $wp->sendText($number, $message);

// Send PDF, Documents, File, Images etc  Message to number
// $status = $wp->sendFromURL($number, $media_url, $caption);

// Send Text message in Group
//$status = $wp->sendTextInGroup($group_name, $message);
// Send Media message in Group
//$status = $wp->sendFromURLInGroup($group_name, $media_url, $caption);

$status = json_decode($status);

if($status->status == 'error'){
    echo $status->response;
}elseif($status->status == 'success'){
    echo 'Success <br />';
    echo $status->response;
}else{
  print_r($status);
}
}}
?>