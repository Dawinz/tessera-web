$(document).ready(function(){
$('#insert').click(function(){
	
var name = $("#name").val();
var passowrd = $("#password").val();
var fullname = $("#fullname").val();
var status_em = $( "#status_em option:selected" ).text();
var location = $( "#location option:selected" ).text();
if(location=="location"){
	 swal("Fill Role!", "Insert Location!", "warning");
			 control.disabled="disabled";
}if(status_em=="Role"){
	 swal("Fill Role!", "Insert Role!", "warning");
			 control.disabled="disabled";
}if(fullname==""){
	 swal("Fill Fullname!", "Insert Fullname!", "warning");
			 control.disabled="disabled";
}if(name==""){
	 swal("Fill Name!", "Insert Name!", "warning");
			 control.disabled="disabled";
}if(passowrd==""){
 swal("Password!", "Insert Password!", "warning");
			 control.disabled="disabled";}
			 $('#insert').text("Inserting..");
  $.ajax({
				url:"insert_person.php",
				method:"POST",
				data:{name:name,passowrd:passowrd,status_em:status_em,fullname:fullname,location:location},
				success:function(response){
						swal("Good job!", "inserted!", "success");
	$('#insert').text("Save");
	}
			
			});
});
});