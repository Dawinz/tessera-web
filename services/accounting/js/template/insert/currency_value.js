$(document).ready(function() {
	   $("#signup").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
error="";
	
if($("#choose_first").val() == 'Choose Primary Currency')
			{
				error += "<li>Choose primary currency.</li>";
			}
if($("#route_second").val() == 'Choose Secondary Currency')
			{
				error += "<li>Choose secondary currency.</li>";
			}
if($("#value").val() == '')
			{
				error += "<li>Fill Value.</li>";
			}
			if($("#choose_first").val() == $("#route_second").val())
			{
				error += "<li>Equal.</li>";
			}

	if(error==""){
			 $.ajax({
            type: 'POST',
            url: 'insert/currency_value.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Add Currency..");
            
            },
            success: function(response){
			//	alert(response);
				if($.trim(response)==""){
					$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
				}else{
					$("#save").notify(
 "Something went wrong",
 "warning",
  { position:"right" }
);
				}
	$("#example1").DataTable().ajax.reload();

$("#save").attr("disabled", false);
	  $("#save").text("Save changes");
            }
        });	
			}else{
			
	 						Swal.fire({
  title: "Missing Info",
  html: error,
  icon: "warning"
});	
			}	});
});