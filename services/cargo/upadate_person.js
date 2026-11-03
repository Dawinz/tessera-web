$(document).ready(function(){
$('#insert').click(function(){
	
var name = $("#name").val();
var fullname = $("#fullname").val();
var passowrd = $("#password").val();
var id = $("#id").text();
var status_em = $( "#status_em option:selected" ).text();
if(status_em=="Role"){
	 swal("Fill Role!", "Insert Role!", "warning");
			 control.disabled="disabled";
}if(name==""){
	 swal("Fill Name!", "Insert Name!", "warning");
			 control.disabled="disabled";
}if(fullname==""){
	 swal("Fill Full Name!", "Insert Fullname!", "warning");
			 control.disabled="disabled";
}
			 $('#insert').text("Inserting..");
  $.ajax({
				url:"update_person.php",
				method:"POST",
				data:{name:name,passowrd:passowrd,status_em:status_em,id:id,fullname:fullname},
				success:function(response){
						swal("Good job!", "inserted!", "success");
	$('#insert').text("Save");
	}
			
			});
});
});