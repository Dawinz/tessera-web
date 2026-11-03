$(document).ready(function() {
	   $("#signup").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
error="";
	


part_Category = $("#part_Category option:selected").text();
	if(part_Category=="Choose Category"){
error += "<li>Choose Particular Category.</li>";
	}
	
particular_name = $("#particular_name option:selected").text();
	if(particular_name=="Choose particular"){
error += "<li>Choose Particular Name.</li>";
	}

route_name = $("#choose_route option:selected").text();
	if(route_name==" Choose route name"){
error += "<li>Choose Route name.</li>";
	}
	
route_category = $("#route_category option:selected").text();
	if(route_category=="Route Category"){
error += "<li>Route Category.</li>";
	}
	currency_v = $("#currency_v option:selected").text();
	if(currency_v=="Choose Currency"){
error += "<li>Choose Currency.</li>";
	}
	affect = $("#affect option:selected").text();
	if(affect=="Choose Affect Area"){
error += "<li>Affected Area.</li>";
	}
vehilce_category = $("#vehilce_category option:selected").text();
	if(vehilce_category=="Choose Vehicle"){
error += "<li>Choose vehicle Category.</li>";
	}
value_name = $(".value_name").val();
	if(value_name==""){
error += "<li>Choose Value.</li>";
	}


				if(error == '')
		{
		
		
		   $.ajax({
            type: 'POST',
            url: 'insert/constants.php',
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
				  $("#save").attr("disabled", false);
				//alert(response);
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
});