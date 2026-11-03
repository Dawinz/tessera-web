<?php 
    if(isset($_POST['submit'])){

       $apiURL = 'api.chat-api.com/instanceXXXXX/';
       $token = 'abcdefgABCDEFG';

        $phone = $_POST['phone_no'];
        $file_name = $_POST['file_name'];
        $caption = $_POST['caption'];
    
  
   $data = json_encode(array(
    'chatId'=>$phone.'@c.us',
    'body'=>'URL of the image',  //how can i make my local image to this URL 
    'filename'=>$file_name,
    'caption'=> $caption
));

     $url = $apiURL.'sendFile?token='.$token;
     $options = stream_context_create(['http' => [
           'method'  => 'POST',
           'header'  => 'Content-type: application/json',
            'content' => $data
        ]
     ]);
     $response = file_get_contents($url,false,$options);
     echo $response; exit;
}
?>
<html>
<body>
<form action = '' method = ''>
   <input type = "text" name = "phone_no" id = "phone_no" />
   <img src = "#" name = "img" id = "img" /> // here local(from my desktop) image is i am displaying
   <input type = "text" name = "file_name" id = "file_name" />
   <input type = "text" name = "caption" id = "caption" />
   <input type = "submit" name = "submit" value =  "Send" />
</form>
</body>
</html>
