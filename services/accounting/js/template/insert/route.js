$(document).ready(function() {
	

	 $('#route_category').change(function(){
	
		route_category = $("#route_category option:selected").text();
	if(route_category=="Sub Route"){
	$(".chmroute").show();
}
	 });
	
	   $("#signup").on('submit', function(e){
        e.preventDefault();
	
		count = 1;
		
error="";
	
route_name = $("#choose_route option:selected").text();
	if(route_name=="Choose Route"){
error += "<li>Fill Route name.</li>";
	}

	
route_category = $("#route_category option:selected").text();
	if(route_category=="Choose Route Category"){
error += "<li>Fill Route Category.</li>";
	}
	Main_route = $("#Main_route option:selected").text();	
alert(route_name+Main_route);


	
	
	
	
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