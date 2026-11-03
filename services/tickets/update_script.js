$(document).ready(function(){

	$("#insert").click(function(){
	var firstname = $("#firstname").val();
	var s_no = $("#s_no").val();
	var r_firstname = $("#r_firstname").val();
	var r_no = $("#r_no").val();
	var trip = $( "#trip option:selected" ).text();
	var date = $("#date").val();
	var status_em = $( "#status_em option:selected" ).text();
	var amount = $("#amount").val();
	var question = $("#question").val();
	var due_amount2 = $("#due_amount2").val();
	var id_update = $("#id_update").text();
	var code_sent = $("#code_send").text();
    var detail = $("#summernote").val();	
   var answer = $("#answer").val();
var loan = $("#loan").val();
	
	if(firstname == ""){
			 swal("Fill Recivers Name!", "Please Enter Reciever's Name!", "warning");
			 control.disabled="disabled";
	}if(r_firstname == ""){
			 swal("Fill Recivers Name!", "Please Enter Reciever's Name!", "warning");

			 control.disabled="disabled";
	}
if(amount == ""){
						 swal("Fill Ammount!", "Please Enter Ammount!", "warning");

			 control.disabled="disabled";
	}
	if(detail == ""){
					 swal("Fill Detail!", "Please Enter Details!", "warning");
			 control.disabled="disabled";
	}if(question == ""){
						 swal("Fill Quantity!", "Please Enter Quantites!", "warning");

			 control.disabled="disabled";
	}
		$("#saving").text("Saving...");

if(isNaN(loan)) {
var loan = 0;
}
	


  $.ajax({
				url:"update_registered_luggage.php",
				method:"POST",
				data:{firstname:firstname,s_no:s_no,r_firstname:r_firstname,r_no:r_no,trip:trip,date:date,status_em:status_em,amount:amount,detail:detail,question:question,id_update:id_update,due_amount2:due_amount2,answer:answer,loan:loan},
				success:function(response){
			if($.trim(response)=="found"){
			
				$("#saving").text("Save");
						 swal("Manifest!", "Found in Manifest!", "warning");
			 			 control.disabled="disabled";
						}else if($.trim(response)=="trip"){
							$("#saving").text("Save");
						 swal("Cant change!", "Delete luggage first to change route!", "warning");
			 			 control.disabled="disabled";
						}else if($.trim(response)==""){
							$("#load_user_paid").load("load_user_paid.php");
			$("#load_user_to_be").load("load_user_to_be.php");
			$("#load_all_sum").load("load_all_sum.php");
			
						
							
																$("#empTable").DataTable().ajax.reload();
	
$('.exampleModalFullscreen').modal('show');
						
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket_luggage.php?id="+code_sent;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
	 $("#insert").attr("disabled", false);
	$("#saving").text("Save");

						}
				
	}
			
			});
	});
	
	$(".close_load").click(function(){

	});
});