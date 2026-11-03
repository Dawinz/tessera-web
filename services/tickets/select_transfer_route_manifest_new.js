$("#search_trip_manifest_new").click(function(){
trip_start_modal = $( "#trip_start_modal_manifest" ).val();
bus_modal_manifest = $("#select_bus_modal_manifest").val();
starting_date_select_manifest = $("#starting_date_select_manifest").val();
select_all_seats = $("#seat_array_list").text();	
	
		route_id=trip_start_modal;
		trip_date=starting_date_select_manifest;
		select_all_seats=select_all_seats;	
        bus=bus_modal_manifest;
if(bus=="no"){
Swal.fire({
  icon: 'error',
  title: 'No Trip',
  html: '<div style="color:white;">No trip on '+trip_date +'</div>',
  
})

}else{
	$.ajax({
				url:"load_seat_chart_transfer_manifest.php",
				method:"POST",
				data:{bus:bus,route_id:route_id,trip_date:trip_date,select_all_seats:select_all_seats},
				
				
		beforeSend:function()
	    		{

	    	  $('#load_class_manifest').html('<div class="placeholder-glow"> <span class="placeholder col-12"style="width:350px;height:600px;"></span></div>');
	    		},
				success:function(response){
				
		$("#load_class_manifest").html(response);
				
	}
	
			
			});
}



 });
