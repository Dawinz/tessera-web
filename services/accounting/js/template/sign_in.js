$(document).ready(function(){
$('#login').click(function(){
var username = $('#username').val();
var password = $('#password').val();

if(username==""){
				 Swal.fire({
  title: "Enter Username!",
  icon: "warning"
});

			 control.disabled="disabled";
}else if(password ==""){
				 Swal.fire({
  title: "Enter Password!",
  icon: "warning"
});
			 control.disabled="disabled";
}

$('#login').text(".. authenticating ..");
  $.ajax({
				url:"authentication/login.php",
				method:"POST",
				data:{username:username,password:password},
				success:function(response){
					response = $.trim(response);
		$('#login').text("Sign in");

if(response=="locked"){
					 Swal.fire({
  title: "Your account is temporary or permanent locked!",
  icon: "warning"
});
}else if (response=="nothing"){
						 Swal.fire({
  title: "Wrong username or password!",
  icon: "warning"
});
}else{
	$('#login').text("Redirecting...");
window.location="manifest.php";
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
				url:"authentication/login.php",
				method:"POST",
				data:{username:username,password:password},
				success:function(response){
							$('#login').text("Sign in");

if(response=="locked"){
					 Swal.fire({
  title: "Your account is temporary or permanent locked!",
  icon: "warning"
});
}else if (response=="nothing"){
						 Swal.fire({
  title: "Wrong username or password!",
  icon: "warning"
});
}else{
	$('#login').text("Redirecting...");
window.location="manifest.php";
}
			
			
			
			
			}

			
			});

    return false;  
  }
});

});