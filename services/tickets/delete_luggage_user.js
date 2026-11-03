$(document).ready(function(){
	$("#delete").click(function(){
var password = $("#password").val();
var id = $("#id").text();
var code = $("#code").text();
$("#delete_r").text("deleting..");

	   $.ajax({
				url:"delete_luggage_user.php",
				method:"POST",
				data:{password:password,id:id,code:code},
				success:function(response){
	
			if($.trim(response) == "found"){
			$("#password'.$row['id'].'").hide();
				
						swal({
  title: "Inside Manifest!",
  text: "Found In the Manifest , you cant Delete it!",
  icon: "warning",
});	
		 
			}else if ($.trim(response) == "wrong"){
			swal("Password is Incorrect!");		
			$("#delete_r").text("delete");
			}else if($.trim(response) == "deleted"){
				$("#password").val("");
				$("#delete_r").text("Delete");
			 alertify.success("You have delete recipt no "+code);
alertify.set("notifier","position", "top-right");
			
			}else if($.trim(response) == "not_uploader"){
							$("#delete_r").text("delete");

									swal({
  title: "Not uploader!",
  text: "You cant delete you are not the uploader of admin",
  icon: "warning",
});	
			}		
				}
			
			});


	});
	
	
	$(document).on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
	  
var password = $("#password").val();
var id = $("#id").text();
var code = $("#code").text();
$("#delete_r").text("deleting..");

	   $.ajax({
				url:"delete_luggage_user.php",
				method:"POST",
				data:{password:password,id:id,code:code},
				success:function(response){
			if($.trim(response) == "found"){
			$("#password'.$row['id'].'").hide();
				
						swal({
  title: "Inside Manifest!",
  text: "Found In the Manifest , you cant Delete it!",
  icon: "warning",
});	
		 
			}else if ($.trim(response) == "wrong"){
			swal("Password is Incorrect!");		
			$("#delete_r").text("delete");
			}else if($.trim(response) == "deleted"){
				$("#password").val("");
				$("#delete_r").text("Delete");
			 alertify.success("You have delete recipt no "+code);
alertify.set("notifier","position", "top-right");
			
			}else if($.trim(response) == "not_uploader"){
							$("#delete_r").text("delete");

									swal({
  title: "Not uploader!",
  text: "You cant delete you are not the uploader of admin",
  icon: "warning",
});	
			}		
				}
			
			});	  
	  
	  
	  
	      return false;  
  }
});
	
});