	   $("#signup").on('submit', function(e){
	

        e.preventDefault();
		
		count = 1;
		
error="";
	
		$("select[name='Vehicle[]']").each(function(){
vehicle = $.trim(($(this).val()));

			if(vehicle == 'Choose Vehicle')
			{

error += "<li>Choose Vehicle.</li>";
	}
	
		});	
		
	$('.item').each(function(){	
	item =  $.trim(($(this).val()));
	if(item=="Choose Item"){
error += "<li>Choose Item.</li>";
	}
	
		});	
	$('.qty').each(function(){		
qty = $(".qty").val();
	if(qty==""){
error += "<li>Add Quantity.</li>";
	}
		});	
		
			$('.price').each(function(){	
		
	amount = $("#amount").val();
	if(amount==""){
error += "<li>Add amount.</li>";
	}
	
	});	
	
			if(error == '')
		{
		
		
		   $.ajax({
            type: 'POST',
            url: 'insert/jobcard.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#save").attr("disabled", true);
	  $("#save").text("Add Currency..");
            
            },
            success: function(response){
				

				  $("#save").attr("disabled", false);
				alert(response);
	if(($.trim(response)) =="found"){
				$("#save").notify(
 "Currency found",
 "error",
  { position:"right" }
);
	
									

	}else{
$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
		  $("#save").text("Save changes");
								
$('#signup').trigger("reset");
$("#example1").DataTable().ajax.reload();

				}
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