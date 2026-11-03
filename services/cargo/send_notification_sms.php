<?php

//index.php

?>

<!DOCTYPE html>
<html>
 <head>
  <title>How to Create Progress Bar for Data Insert in PHP using Ajax</title>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  
  <br />
  <br />
  <div class="container">
   <h1 align="center">SMS</h1>
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <h3 class="panel-title">Enter Data</h3>
    </div>
      <div class="panel-body">
       <span id="success_message"></span>
       <div="sample_form">
    <div class="sms_text">sms</div>

        <div class="form-group" align="center">
         <button name="save" id="save" class="btn btn-info" value="Send" >Send</button>
        <div class="response"></div>
		</div>
       </div>
       <div class="form-group" id="process" style="display:none;">
        <div class="progress">
       <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
       </div>
      </div>
       </div>
      </div>
     </div>
  </div>
 </body>
</html>
 <script src="insert_notification_sms.js"></script>


