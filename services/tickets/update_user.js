$(document).ready(function(){


$("#update").click(function(){

var role_update = $("#role_update option:selected" ).text();
var fullname_update = $("#fullname_update").val();
var lastname_update = $("#lastname_update").val();
var username_update = $("#username_update").val();
var password_update = $("#password_update").val();
var phone_no_update = $("#phone_no_update").val();
var email_update = $("#email_update").val();
var id_no = $("#id_no").text();


if(fullname_update=="") {
				 swal("", "Choose Firstname!", "warning");

			 control.disabled="disabled";
}

if(lastname_update=="") {
				 swal("", "fill lastname !", "warning");

			 control.disabled="disabled";
}

if(username_update=="") {
				 swal("", "fill usernamee!", "warning");

			 control.disabled="disabled";
}



if(password_update=="") {
				 swal("", "Please Enter Password !", "warning");

			 control.disabled="disabled";
}if(phone_no_update=="") {
				 swal("", "Please Enter Phone number !", "warning");

			 control.disabled="disabled";
}if(email_update=="") {
				 swal("", "Please Enter Email !", "warning");

			 control.disabled="disabled";
}
$("#update").text("Updating...");
 $.ajax({
				url:"update_user.php",
				method:"POST",
				data:{role_update:role_update,fullname_update:fullname_update,lastname_update:lastname_update,username_update:username_update,password_update:password_update,phone_no_update:phone_no_update,email_update:email_update,id_no:id_no},
				success:function(response){
					$("#update").text("Update");
														$("#example").DataTable().ajax.reload();

if($.trim(response) == ""){
					
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $("#save_changes").text("Register");
						}else{
					$('#update').text("Redirecting...");
window.location="index.php";
						}
																					$("#example").DataTable().ajax.reload();
	}
			
			});

});


});