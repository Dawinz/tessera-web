$(document).ready(function() {
	   $("#signup").on('submit', function(e){
        e.preventDefault();
	
		count = 1;
		
error="";
	
route_name = $("#choose_route option:selected").text();
	if(route_name=="Choose Route"){
error += "<li>Fill Route name.</li>";
	}
currecncy_route = $("#currecncy_route option:selected").text();
	if(currecncy_route=="Choose Currency"){
error += "<li>Fill Currency.</li>";
	}
	
				if(error == '')
		{
			
	
	  $.ajax({
            type: 'POST',
            url: 'insert/insert_route.php',
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
		if(($.trim(response)) =="found"){
				$("#save").notify(
 "Currency found",
 "error",
  { position:"right" }
);
	
									    $("#save").attr("disabled", false);

	}else{
$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
		  $("#save").text("Save changes");
									    $("#save").attr("disabled", false);
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
});});