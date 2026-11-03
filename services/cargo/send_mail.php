<?php
require "PHPMailer/PHPMailerAutoload.php";
include 'config.php';
$textareaValue = $_POST['textareaValue'];
$subject = $_POST['subject'];
    																
				include 'config.php';
    $select1=mysqli_query($db,"SELECT * FROM tkt_passenger ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
			$email = $get1['email'];
    	  


    $mailto = $email;
    $mailSub = $subject;
    $mailMsg = $textareaValue;

   $mail = new PHPMailer();
   $mail ->IsSmtp();
   $mail ->SMTPDebug = 0;
   $mail ->SMTPAuth = true;
   $mail ->SMTPSecure = 'ssl';
   $mail ->Host = "host59.registrar-servers.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username = "noreply@teccross.co.tz";
   $mail ->Password = "Teccross@360";
   $mail ->SetFrom("noreply@teccross.co.tz");
   $mail ->Subject = $mailSub;
   $mail ->Body = $mailMsg;
   $mail ->AddAddress($mailto);



  
    	}
		   if(!$mail->Send())
   {
       echo "Mail Not Sent";
   }
   else
   {
       echo "Mail Sent";
   }


}
?>