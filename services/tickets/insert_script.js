$(document).ready(function(){
	$("#insert").click(function(){
	var firstname = $("#firstname").val();
	var middlename = $("#middlename").val();
	var s_no = $("#s_no").val();
	var r_firstname = $("#r_firstname").val();
	var r_surname = $("#r_surname").val();
	var r_no = $("#r_no").val();
	var trip = $( "#trip option:selected" ).text();
	var status_em = $( "#status_em option:selected" ).text();
	var amount = $("#amount").val();
	var question = $("#question").val();
	var answer = $("#answer").val();
	var due = $("#due").val();
	var due_amount2 = $("#due_amount2").val();
    var detail = $("#summernote").val();
	var loan = $("#loan").val();
	if(firstname == ""){
			 swal("Fill Firstname!", "Please Enter Name!", "warning");
			 			 control.disabled="disabled";

	}	if(s_no==""){
			 swal("Fill Sender's Number!", "Please Fill  Senders Number!", "warning");
			 			 control.disabled="disabled";
	} if(s_no=="+255"){
			 swal("Fill  senders Number!", "Please Fill Sender's Number!", "warning");
			 			 control.disabled="disabled";
	}if(r_no==""){
			 swal("Fill Receiver's Number!", "Please Fill  Senders Number!", "warning");
			 			 control.disabled="disabled";
	} if(r_no=="+243"){
			 swal("Fill  Receiver's Number!", "Please Fill Sender's Number!", "warning");
			 			 control.disabled="disabled";
	}if(r_firstname == ""){
 swal("Fill Reciever's name!", "Please Enter Reciver's name!", "warning");	
 control.disabled="disabled";
	}

	if(r_no == ""){
		alert("fill the phone");
			 control.disabled="disabled";
	}
	if(trip == "Choose Route"){
	swal("Fill Route!", "Please Fill  Route!", "warning");		
	control.disabled="disabled";
	}if(trip == "You Have no route"){
	swal("Fill Route!", "Please Fill  Route!", "warning");		
	control.disabled="disabled";
	}
	if(status_em=="Payment Status"){
			 swal("Fill Status!", "Please Fill Payment Status!", "warning");
			 			 control.disabled="disabled";
	}

if(amount == ""){
 swal("Fill Ammount!", "Please Fill Ammount!", "warning");
 control.disabled="disabled";
	}
	if(detail == ""){
		alert("fill the fill status");
			 control.disabled="disabled";
	}if(question == ""){
swal("Fill Quantity!", "Please Fill quantity!", "warning");
control.disabled="disabled";
	}
	
	
	$("#saving").text("loading..");
	 $("#insert").attr("disabled", true);
	 
	   $.ajax({
				url:"insert_script.php",
				method:"POST",
				data:{firstname:firstname,middlename:middlename,s_no:s_no,r_firstname:r_firstname,r_surname:r_surname,r_no:r_no,trip:trip,status_em:status_em,amount:amount,detail:detail,question:question,answer:answer,due:due,loan:loan},
				success:function(response){
					
					$('#loan_area').hide();	
						if($.trim(response)=="equal"){
						 swal("luggage code dublicate!", "luggage is inside!", "warning");
			 			 control.disabled="disabled";
						}else if($.trim(response)=="log_out"){
				window.location="log_out.php";
						}else{
				

							 $("#rgform").trigger("reset");
							 alertify.success("Success recipt "+response);
alertify.set('notifier','position', 'bottom-right');
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

iframe.src = "print_ticket_luggage.php?id="+response;
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
	

});