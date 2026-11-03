$(document).ready(function(){

 $("#update_currency").on('submit', function(e){
        e.preventDefault();
		
			count = 1;
error="";

name_update = $("#name_update").val();

if($("#name_update").val() == '')
			{

				error += "<li>update currency name.</li>";


			}	

if($("#value_update").val() == '')
			{

				error += "<li>update value .</li>";


			}
if($("#symbol_update").val() == '')
			{

				error += "<li>update Symbol .</li>";


			}


			if(error == '')
		{
		    $.ajax({
            type: 'POST',
            url: 'update/update_currency.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#save").attr("disabled", true);
	  $("#text_change").text("updating...");
            
            },
            success: function(response){
								alert(response);

	  $("#save").attr("disabled", false);
	  $("#text_change").text("Save Changes");
$("#save").notify(
 "Changes saved",
 "success",
  { position:"right" }
);
			$("#example1").DataTable().ajax.reload();
	  
            }
        });	
		}else{
alert(error);
		}


});

});