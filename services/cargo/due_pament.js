
		$('#savebtn').click(function(){
          count_text = $("#amount_id").text();

	 	var due_amount = $("#due_amount").val();
				var due_amount2 = $("#due_amount2").val();
			
	 
if(due_amount==""){
	Swal.fire("Fields cannot be empty!");

control.disabled="disabled";

}if(parseInt(due_amount)==0){
	Swal.fire("Fields cannot be empty!");

control.disabled="disabled";

}if(due_amount2==""){
	Swal.fire("Fields cannot be empty!");

control.disabled="disabled";

}if(parseInt(due_amount2)==0){
	Swal.fire("Fields cannot be empty!");

control.disabled="disabled";

}
	  				var due_amount = parseInt($("#due_amount").val().replace(/,/g, ''));
				var due_amount2 = parseInt($("#due_amount2").val().replace(/,/g, ''));

	  if(parseInt(due_amount2) >= parseInt(due_amount)){
	Swal.fire("Paid Large than total amount!", "Paid can't be equal or greater than Total amount payment!", "warning");
	} else if(parseInt(due_amount2)=="0"){
	 	Swal.fire("Paid !", "Can not be zero!", "warning");
	} else if(parseInt(due_amount)=="0"){
		  	Swal.fire("To be !", "Can not be zero!", "warning");
	}else {
		 $("#due_amount").val("");
	$("#due_amount2").val("");
		  $("#amount_new"+count_text).val(due_amount);
		  $("#answer"+count_text).val(due_amount2);
		  	$('#due_modal').modal('hide');

	  }

});