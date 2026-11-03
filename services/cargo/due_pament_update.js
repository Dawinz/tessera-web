		$('#savebtn').click(function(){
          
	 	var due_amount = $("#due_amount").val();
				var due_amount2 = $("#due_amount2").val();
			
	 
if(due_amount==""){
swal("Fill ", "Fields cannot be empty!", "warning");
control.disabled="disabled";

}if(parseInt(due_amount)==0){
swal("Fill ", "Fields cannot be empty!", "warning");
control.disabled="disabled";

}if(due_amount2==""){
swal("Fill ", "Fields cannot be empty!", "warning");
control.disabled="disabled";

}if(parseInt(due_amount2)==0){
swal("Fill ", "Fields cannot be empty!", "warning");
control.disabled="disabled";

}
	  				var due_amount = parseInt($("#due_amount").val().replace(/,/g, ''));
				var due_amount2 = parseInt($("#due_amount2").val().replace(/,/g, ''));

	  if(parseInt(due_amount2) >= parseInt(due_amount)){
				 swal("Paid Large than total amount!", "Paid can't be equal or greater than Total amount payment!", "warning");

	  } else if(parseInt(due_amount2)=="0"){
		  		 swal("Paid !", "Can not be zero!", "warning");
	  } else if(parseInt(due_amount)=="0"){
		  		 swal("To be !", "Can not be zero!", "warning");
	  }else {
		  $('#amount').val(due_amount);
		  $('#answer').val(due_amount2);
		  	$('#due_modal').modal('hide');

	  }

});