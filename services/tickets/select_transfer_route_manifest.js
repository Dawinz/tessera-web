$("#search_trip_manifest").click(function(){
		 var trip_start_modal = $( "#trip_start_modal2 option:selected" ).text().replace(/ /g,"_");
		 var trip_end_modal = $( "#trip_end_modal2 option:selected" ).text().replace(/ /g,"_");
		 var select_bus_modal = $( "#select_bus_modal2 option:selected" ).text();
		 starting_date_select = $("#starting_date_select2").val();
		 ticket_no_original= $("#ticket_array_list").text();
		 route_day_id_modal_manifest = $("#route_day_id_modal_manifest").text();
		 route_id_modal_manifest = $("#route_id_modal_manifest").text();


if(select_bus_modal==="No Bus"){

Swal.fire('No Bus');
}else{
				 $("#load_class_manifest").load("loader_big.php");

 $("#load_class_manifest").load("load_seat_chart_transfer_sel_manifest.php?start_location="+trip_start_modal+"&ending_location="+trip_end_modal+"&bus="+select_bus_modal+"&trip_date="+starting_date_select+"&ticket_no_original="+ticket_no_original);
			 $("#load_user_t_content_manifest").load("manifest_passenger.php?start_location="+trip_start_modal+"&ending_location="+trip_end_modal+"&route_id="+route_id_modal_manifest+"&trip_date="+starting_date_select+"&id="+route_day_id_modal_manifest);
		 	
}
			
		 

});
	 $('.action').change(function(){
		 var trip_end_modal = $( "#trip_end_modal2 option:selected" ).text();
		var starting_date_select = $( "#starting_date_select2" ).val();

  if($(this).val() != '')
  {
	 
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';


  
   $.ajax({
    url:"fetch_dependent_ticket.php",
    method:"POST",
    data:{action:action, query:query,trip_end_modal:trip_end_modal,starting_date_select:starting_date_select},
    success:function(data){
		
     $('#select_bus_modal2').html(data);
    }
   })
  }
 });
$('.action2').change(function(){
		 var trip_start_modal = $( "#trip_start_modal2 option:selected" ).text();
		var starting_date_select = $( "#starting_date_select2" ).val();

  if($(this).val() != '')
  {
	 
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';


  
   $.ajax({
    url:"fetch_dependent_ticket_end.php",
    method:"POST",
    data:{action:action, query:query,trip_start_modal:trip_start_modal,starting_date_select:starting_date_select},
    success:function(data){
		alert(data);
		
     $('#select_bus_modal2').html(data);
    }
   })
  }
 });
 
  $("#starting_date_select2").change(function(){
		 var trip_end_modal = $( "#trip_end_modal2 option:selected" ).text();
		var starting_date_select = $( "#starting_date_select2" ).val();
	 var trip_start_modal = $( "#trip_start_modal2 option:selected" ).text();
  if($(this).val() != '')
  {
	 
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';


  
   $.ajax({
    url:"fetch_dependent_ticket_date.php",
    method:"POST",
    data:{action:action, query:query,trip_end_modal:trip_end_modal,trip_start_modal:trip_start_modal},
    success:function(data){
		
     $('#select_bus_modal2').html(data);
    }
   })
  }
 });
