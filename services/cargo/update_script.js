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
	weight_name = $(".weight_name").val();
	weight_value = $(".weight_value").val();
	luggage_value = $("#luggage_value").val();
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
				data:{firstname:firstname,s_no:s_no,r_firstname:r_firstname,r_no:r_no,trip:trip,date:date,status_em:status_em,amount:amount,detail:detail,question:question,id_update:id_update,due_amount2:due_amount2,answer:answer,loan:loan,weight_name:weight_name,weight_value:weight_value,luggage_value:luggage_value},
				success:function(response){
					
					$("#saving").text("Save");
		
	if($.trim(response)=="found"){
				$("#saving").text("Save");
						 if (window.TesseraStates) {
							TesseraStates.toastWarn('On manifest', 'This cargo is already on a manifest.');
						 } else {
							swal("Manifest!", "Found in Manifest!", "warning");
						 }
			 			 control.disabled="disabled";
						}else if($.trim(response)=="trip"){
							$("#saving").text("Save");
						 if (window.TesseraStates) {
							TesseraStates.toastWarn('Cannot change route', 'Delete the shipment first to change route.');
						 } else {
							swal("Cant change!", "Delete luggage first to change route!", "warning");
						 }
			 			 control.disabled="disabled";
						}else{
								if (window.TesseraStates) {
									TesseraStates.toastOk('Cargo updated', 'Changes saved. Opening label...');
								}
								window.open("print_ticket_mulitple.php?id="+response);
					

						}
				
	}
			
			});
	});
	
	$(".close_load").click(function(){

	});
});