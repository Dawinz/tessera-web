$(document).ready(function(){
$('.btn-float').click(function(){
var username = $('#username').val();
var password = $('#password').val();
if(username==""){
				 swal("Fill Firstname!", "Please Enter username!", "warning");

			 control.disabled="disabled";
}else if(password ==""){
				 swal("Fill Password!", "Please Enter password!", "warning");

			 control.disabled="disabled";
}
$(".loader").show();
$(".loader").text("loading...");
  $.ajax({
				url:"./insert/login.php",
				method:"POST",
				data:{username:username,password:password},
				success:function(response){
					
			if($.trim(response) == ".nothing"){
				$(".loader").text("");
							swal("Login Error", "Check the username and password!", "warning");

			}else if($.trim(response)==".Operators"){
							$(".loader").text("Redirecting...");
				window.location="employee_update.php";
			}else if(($.trim(response)==".Super Admin") || ($.trim(response)==".admin")){
	$(".loader").text("Redirecting...");
				window.location="choose_server.php";
		
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
swal("Fill Firstname!", "Please Enter password!", "warning");			
control.disabled="disabled";
}else if(password ==""){
swal("Fill Password!", "Please Enter password!", "warning");
			 control.disabled="disabled";
}
$(".loader").show();
$(".loader").text("loading...");
  $.ajax({
				url:"./insert/login.php",
				method:"POST",
				data:{username:username,password:password},
				success:function(response){
		if($.trim(response) == ".nothing"){
				$(".loader").text("");
							swal("Login Error", "Check the username and password!", "warning");

			}else if($.trim(response)==".Operators"){
							$(".loader").text("Redirecting...");
				window.location="employee_update.php";
			}else if(($.trim(response)==".Super Admin")	|| ($.trim(response)==".admin")){					
	$(".loader").text("Redirecting...");
				window.location="choose_server.php";
			}
	}
			
			});

    return false;  
  }
});

});