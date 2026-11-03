$(document).ready(function(){


$("#check_transfer").click(function(){
var ticket_no = $("#ticket_no").val();
var prev_id = $("#prev_id").text();
$("#ti_response").text("checking....");


  $.ajax({
				url:"check_ticket.php",
				method:"POST",
				data:{ticket_no:ticket_no,prev_id:prev_id},
				success:function(response){
					$("#ti_response").text("");

if($.trim(response) == "past"){
 swal("Date", "Check date and proceed !", "warning");

						}else{
							
						}


	}
			
			});


});





});