$(document).ready(function(){
$('#login').click(function(){

var username = $('#username').val();
var password = $('#password').val();

if(username==""){
				 swal("", "Please Enter username!", "warning");

			 control.disabled="disabled";
}else if(password ==""){
				 swal("", "Please Enter password!", "warning");

			 control.disabled="disabled";
}

$('#login').text(".. authenticating ..");
  $.ajax({
				url:"login.php",
				method:"POST",
				data:{username:username,password:password},
				success:function(response){
				
if($.trim(response) == "nothing"){
$('#login').text("Log in");
swal("Authentication Error", "Check the username and password or Consult your administator!", "warning");

			}else if($.trim(response)=="Administrator"){
$('#login').text("Redirecting...");
window.location="register_luggage.php";
								

			}else if($.trim(response)=="Super Administrator"){
$('#login').text("Redirecting...");
window.location="home.php";
								

			}else if($.trim(response)=="Agent"){
$('#login').text("Redirecting...");
window.location="register_luggage.php";
								

			}
			
			
			
			
			
			}

			
			});

});


$(document).on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
var username = $('#username').val();
var password = $('#password').val();

if(username==""){
				 swal("", "Please Enter username!", "warning");

			 control.disabled="disabled";
}else if(password ==""){
				 swal("", "Please Enter password!", "warning");

			 control.disabled="disabled";
}

$('#login').text(".. authenticating ..");
  $.ajax({
				url:"login.php",
				method:"POST",
				data:{username:username,password:password},
				success:function(response){
					
if($.trim(response) == "nothing"){
$('#login').text("Log in");
swal("Authentication Error", "Check the username and password or Consult your administator!", "warning");

			}else if($.trim(response)=="Administrator"){
$('#login').text("Redirecting...");
window.location="register_luggage.php";
								

			}else if($.trim(response)=="Super Administrator"){
$('#login').text("Redirecting...");
window.location="home.php";
								

			}else if($.trim(response)=="Agent"){
$('#login').text("Redirecting...");
window.location="register_luggage.php";
								

			}
			
			
			
			
			
			}

			
			});

    return false;  
  }
});

});