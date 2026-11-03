$(document).ready(function(){
   
$("#delete_button").click(function(){
	$("#delete_luggage").modal("hide");
error = "";
var password = $("#password").val();
var id = $("#l_id").val();
var code = $("#l_code").val();
var reason = $("#resonss").val();

if(password==""){
    
error +="fill password";
} 
 if (reason ==""){
error +="fill Reason";
}
if(error==""){


delete_function (password,id,code,reason);

}else{
    alert("fill all feilds");
}


    
});

function delete_function (password,id,code,reason){
       $.ajax({
				url:"delete/luggage.php",
				method:"POST",
                   beforeSend: function(){
				
			 $("#delete_button").attr("disabled", true);
	  $("#delete_r").text("Deleteing");
            
            },
				data:{password:password,id:id,code:code,reason:reason},
				success:function(response){
 //alert(response);

$("#delete_button").attr("disabled", false);
$("#delete_r").text("Delete");

	if($.trim(response) == "found"){
		if (window.TesseraStates) {
			TesseraStates.toastWarn('On manifest', 'Remove it from the manifest before deleting.');
		} else {
						swal({
  title: "Inside Manifest!",
  text: "Found In the Manifest , you cant Delete it!",
  icon: "warning",
});
		}
			}else if ($.trim(response) == "wrong"){
			if (window.TesseraStates) {
				TesseraStates.toastErr('Wrong password', 'Password is incorrect.');
			} else {
				swal("Password is Incorrect!");
			}
			$("#delete_r").text("delete");
			}else if($.trim(response) == "deleted"){
				$("#password").val("");
			if (window.TesseraStates) {
				TesseraStates.toastOk('Cargo deleted', 'Shipment was removed.');
			} else {
			alertify.success("Deleted");
alertify.set('notifier','position', 'top-right');
			}
		$('#empTable').DataTable().ajax.reload();	
			
			}else if($.trim(response) == "not_uploader"){
							$("#delete_r").text("delete");
			if (window.TesseraStates) {
				TesseraStates.toastErr('Not allowed', 'Only the uploader or an admin can delete this.');
			} else {
									swal({
  title: "Not uploader!",
  text: "You cant delete you are not the uploader of admin",
  icon: "warning",
});
			}
			}else{
          
            }	

				}
			
			});

}
});