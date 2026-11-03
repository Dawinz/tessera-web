$(document).ready(function(){
	
	
	   $("#insert_trip_all").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
error="";
	$("select[name='day_of_week[]']").each(function(){

			if($(this).val() == 'Choose Trip Days')
			{
				
			
error += "<li>Choose a day "+count+" Row</li>";

			}

			count = count + 1;

		});

		$("select[name='route_name[]']").each(function(){

			if($(this).val() == 'Choose Route Name')
			{
				
			
error += "<li>Choose route name "+count+" Row</li>";

			}

			count = count + 1;

		});	

		$("select[name='vehicle_name[]']").each(function(){

			if($(this).val() == 'Choose Vehicle Name')
			{
				
			
error += "<li>Choose Vehicle "+count+" Row</li>";

			}

			count = count + 1;

		});
		
		
if(error==""){
	  $.ajax({
            type: 'POST',
            url: 'insert/insert_route_trip.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#search").attr("disabled", true);
	  $("#search").text("Adding route..");
            
            },
            success: function(response){
				//alert(response)
			$("#search").attr("disabled", false);
	  $("#search").text("Register");
	response = $.trim(response);
Swal.fire({
  title: "Trip added",
  icon: "success"
});	
$("#example").DataTable().ajax.reload();
$("#search").attr("disabled", false);
$("#search").text("Register");
		 }
        });				
}else{
					Swal.fire({
  title: "Missing Info",
  html: "<span style='color:white;'>"+error+"</span>",
  icon: "warning"
});		
		
}
});
});