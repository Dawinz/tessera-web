$(document).ready(function() {
	   $("#signup").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
error="";
	
if($("#choose_route").val() == 'Choose Route')
			{
				error += "<li>Choose route name.</li>";
			}
			
			if($("#route_currency").val() == 'Choose Currency')
			{
				error += "<li>Choose Currency.</li>";
			}

			if($("#Main_route").val() == 'Choose Status')
			{
				error += "<li>Choose Status.</li>";
			}
			
			if(error==""){
			 $.ajax({
            type: 'POST',
            url: 'insert/route_currency.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Add Currency..");
            
            },
            success: function(response){
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
			}
	});
});