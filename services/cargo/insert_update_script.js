$(document).ready(function(){
	$("#insert").click(function(){
	var firstname = $("#firstname").val();
	var middlename = $("#middlename").val();
	var surname = $("#surname").val();
	var birth = $("#birth").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var number_e = $("#number_e").val();
	var sickness = $("#sickness").val();
	var gender = $( "#gender option:selected" ).text();
	var status_em = $( "#status_em option:selected" ).text();
	var grade = $( "#grade option:selected" ).text();
	var account = $("#account").val();
	var currency = $("#currency").val();
    var detail = $("#summernote").summernote("code");	
	var date_join = $("#date_join").val();
	if(firstname == ""){
		alert("fill the firstname");
			 control.disabled="disabled";
	}if(middlename == ""){
		alert("fill the middlename");
			 control.disabled="disabled";
	}if(surname == ""){
		alert("fill the surname");
			 control.disabled="disabled";
	}if(birth == ""){
		alert("fill the birthday");
			 control.disabled="disabled";
	}
if(email == ""){
		alert("fill the email");
			 control.disabled="disabled";
	}
	if(phone == ""){
		alert("fill the phone");
			 control.disabled="disabled";
	}
	if(grade == "Grade"){
		alert("fill the grade");
			 control.disabled="disabled";
	}
if(account == ""){
		alert("fill the account number");
			 control.disabled="disabled";
	}
if(currency == ""){
		alert("fill the employee fill sallary");
			 control.disabled="disabled";
	}
	if(status_em == "Status"){
		alert("fill the fill status");
			 control.disabled="disabled";
	}
	if(gender == "Gender"){
		alert("fill gender");
			 control.disabled="disabled";
	}if(number_e == ""){
		alert(" fill employee number");
			 control.disabled="disabled";
	}if(date_join == ""){
		alert(" fill employee joing date");
			 control.disabled="disabled";
	}
	if(birth == ""){
		alert(" fill employee date of birth");
			 control.disabled="disabled";
	}
	$("#insert").text("loading..");
	var id = $('#id').text();
  $.ajax({
				url:"insert_update_employee.php",
				method:"POST",
				data:{firstname:firstname,middlename:middlename,surname:surname,email:email,phone:phone,grade:grade,account:account,currency:currency,status_em:status_em,gender:gender,number_e:number_e,sickness:sickness,detail:detail,birth:birth,date_join:date_join,id:id},
				success:function(response){
						$("#insert").text("Redirecting..");

					$('.response').text(response);
					var r = $('.response').text();
if($.trim(r)=="found"){
	alert(r);
	 control.disabled="disabled";
}else{
		window.location="drop_new/index.php?id="+response;
}
				
	}
			
			});
	});
});