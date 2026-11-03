$(document).ready(function() {
	   $("#update_currency_update2").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
error="";
	
	 $.ajax({
            type: 'POST',
            url: 'update/currency_value.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Add Currency..");
            
            },
            success: function(response){
				alert(response);
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
	});
});