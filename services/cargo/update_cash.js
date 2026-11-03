$(document).ready(function(){
	
	
	$("#send").click(function(){
			$("#send").text("Posting...");
		    var amount = $('#amount').val();
			var authorizer = $("#authorizer").val();
			var time2 = $("#time2").val();
			var id = $("#id_view").text();


			
  $.ajax({
				url:"update_cash.php",
				method:"POST",
				data:{amount:amount,authorizer:authorizer,id:id,time2:time2},
				success:function(response){
						$("#send").text("Send");
	if($.trim(response)=="cant"){
		 swal("Entry Found!", "Data found inside the cash in hand!", "warning");
	}else{
										window.location="office_exp.php";

	}
}
			
			});
			
			});
		
});

