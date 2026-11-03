$(document).ready(function(){
	
	
	$("#send").click(function(){
			var code = "1";
			$("#send").text("loading...");
  $.ajax({
				url:"send_not.php",
				method:"POST",
				data:{code:code},
				success:function(response){
swal("Warning!", "No Luggage With That Number!", "warning");
	}
		
			});
});

});