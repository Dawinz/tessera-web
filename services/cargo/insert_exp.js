$(document).ready(function(){
	$("#insert").click(function(){
	var title = $("#title").val();
	var date = $("#date").val();
	var v_no = $("#v_no").text();	
	var cash_hand = $("#cash_hand").text();	
	var amount_remain = $("#amount_remain").text();
	 var detail = $("#summernote").summernote("code");	
		var amount = $("#pay").val();
	
   
	if(title == ""){
	 swal("Authorizer!", "Please Enter A Person who has aurhtorize!", "warning");	
 control.disabled="disabled";
	}
if(date == ""){
	 swal("Date!", "Fill Date!", "warning");	
 control.disabled="disabled";
	}if(amount == ""){
	 swal("Ammount!", "Enter Ammount!", "warning");	
 control.disabled="disabled";
	}

	
	
	$("#insert").text("loading..");
  $.ajax({
				url:"insert_exp.php",
				method:"POST",
				data:{title:title,date:date,detail:detail,amount:amount,v_no:v_no,cash_hand:cash_hand,amount_remain:amount_remain,},
				success:function(response){
				window.location="print_office.exp.php?id="+v_no;

	}
			
			});
	});
});