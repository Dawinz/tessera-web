$(document).ready(function(){
	$("#insert").click(function(){
	var firstname = $("#r_firstname").val();
	var date = $("#date").val();
	var status_em = $( "#status_em option:selected" ).text();
	 var detail = $("#summernote").summernote("code");	
		var pay = $("#pay").val();
	
   
	if(firstname == ""){
		alert("fill the firstname");
			 control.disabled="disabled";
	}

	
	
	$("#insert").text("loading..");
  $.ajax({
				url:"insert_exp.php",
				method:"POST",
				data:{firstname:firstname,date:date,status_em:status_em,detail:detail,pay:pay},
				success:function(response){
				window.location="print_exp_pos.php?id="+response;

	}
			
			});
	});
});