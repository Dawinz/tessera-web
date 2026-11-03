$("#search_trip").click(function(){
		 var trip_start_modal = $( "#trip_start_modal option" ).val().replace(/ /g,"_");
		 var trip_end_modal = $( "#trip_end_modal option:selected" ).text().replace(/ /g,"_");
		 var select_bus_modal = $( "#select_bus_modal option:selected" ).text();
		 starting_date_select = $("#starting_date_select").val();
		 ticket_no_original= $("#ticket_no_original").text();
	
		 if(select_bus_modal=="No Bus"){
Swal.fire({
  icon: 'error',
  title: 'No Trip',
  html: '<div style="color:white;">No trip on '+starting_date_select +'</div>',
  
})

		 }else{
			
			   $.ajax({
    url:"load_seat_chart_transfer_sel.php",
    method:"POST",
    data:{trip_start_modal:trip_start_modal, select_bus_modal:select_bus_modal,trip_end_modal:trip_end_modal,starting_date_select:starting_date_select,ticket_no_original:ticket_no_original},
    	beforeSend:function()
	    		{

	    			$('#load_class').html('<div class="placeholder-glow"><span class="placeholder col-12"style="width:350px;height:600px;"></span></div>');

	    		},
	success:function(data){
		
     $('#load_class').html(data);
	 
    }
   })
			 
			 
			 			var route_id_start = $( "#trip_start_modal option" ).val().replace();
			 			var sub_route_id_start = $( "#sub_route_id_start option" ).val().replace();
			 			var sub_route_id_end = $( "#sub_route_id_end option" ).val().replace();
		
					   $.ajax({
    url:"dependence/start_point.php",
    method:"POST",
    data:{route_id_start:route_id_start,sub_route_id_start:sub_route_id_start},
    success:function(data){
      alert(data)
$("#trip_start_tranit").html(data);
    }
   });	

					   $.ajax({
    url:"dependence/end_point.php",
    method:"POST",
    data:{route_id_start:route_id_start,sub_route_id_end:sub_route_id_end},
    success:function(data){
$("#trip_end_tranit").html(data);
    }
   });	 

		 }
		 
		 

});