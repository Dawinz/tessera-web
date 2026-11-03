$(document).ready(function(){
$("#register_user").click(function(){
var fullname = $("#fullname").val();
lastname = $("#lastname").val();
username = $("#username").val();
password_no = $("#password").val();
number = $("#phone_no").val();
email = $("#email").val();
role = $("#role option:selected" ).text();

if(fullname=="") {
				 swal("", "Please Insert Firstname!", "warning");

			 control.disabled="disabled";
}
if(lastname=="") {
				 swal("", "Please Insert Lastname!", "warning");

			 control.disabled="disabled";
}
if(username=="") {
				 swal("", "Please Insert username!", "warning");

			 control.disabled="disabled";
}
if(password_no=="") {
				 swal("", "Please Insert password!", "warning");

			 control.disabled="disabled";
}
if(number=="") {
				 swal("", "Please Insert Phone number!", "warning");

			 control.disabled="disabled";
}
if(email=="") {
				 swal("", "Please Insert Email!", "warning");

			 control.disabled="disabled";
}
if(role=="Role") {
				 swal("", "Please Insert Role!", "warning");

			 control.disabled="disabled";
}
$("#register_user").text("Registering");
 $("#register_user").attr("disabled", true);
$.ajax({
				url:"insert_user.php",
				method:"POST",
				data:{fullname:fullname,lastname:lastname,username:username,password_no:password_no,number:number,email:email,role:role},
				success:function(response){
if($.trim(response) == "succesfull"){
					
							  swal({
  
    text: "update Succefull.",
    timer: 2000,
    showConfirmButton: false
  });

  $("#register_user").text("Register");
						}else{
							swal("Found", "Trip found !", "warning");
						}
													$("#example").DataTable().ajax.reload();
													$("#register_user").attr("disabled", false);
						}

			});

});
});