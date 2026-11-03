$(document).ready(function(){
	$("#insert").click(function(){
	var firstname = $("#r_firstname").val();
	var date = $("#date").val();
	var status_em = $( "#status_em option:selected" ).text();
	 var detail = $("#summernote").summernote("code");	
	var subject = $("#subject").val();
	var r_date = $("#r_date").val();
	
	
   
	if(firstname == ""){
		alert("fill the firstname");
			 control.disabled="disabled";
	}

	
	
	$("#insert").text("loading..");
  $.ajax({
				url:"insert_petty.php",
				method:"POST",
				data:{firstname:firstname,date:date,status_em:status_em,detail:detail,subject:subject,r_date:r_date},
				success:function(response){
						$("#insert").text("Redirecting..");

					$('.response').text(response);
					var r = $('.response').text();
if($.trim(r)=="found"){
	alert(r);
}else{
}
				
	}
			
			});
	});
});