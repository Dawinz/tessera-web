 $(document).ready(function(){
	 	
	 $('.action').change(function(){
		 var trip_end_modal = $( "#trip_end_modal option:selected" ).text();
		var starting_date_select = $( "#starting_date_select" ).val();

  if($(this).val() != '')
  {
	 
   var action = $(this).attr("id");
   var trip_start_modal = $(this).val();
   var result = '';



   $.ajax({
    url:"dependence/route_date_load_bus.php",
    method:"POST",
    data:{trip_start_modal:trip_start_modal,starting_date_select:starting_date_select},
    success:function(data){
     $('#select_bus_modal').html(data);
     
    }
   });

  }
 });
	 $('.action2').change(function(){
		 var trip_start_modal = $( "#trip_start_modal option:selected" ).text();
		var starting_date_select = $( "#starting_date_select" ).val();

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
		
		
     $('#select_bus_modal').html(data);
    }
   })
  }
 });

 $("#starting_date_select").change(function(){
	   $('#select_bus_modal').html("<option>loading bus..<option>");
	    $("#search_trip").attr("disabled", true);
		 var trip_end_modal = $( "#trip_end_modal option:selected" ).text();
		var starting_date_select = $( "#starting_date_select" ).val();
	 var trip_start_modal = $( "#trip_start_modal option:selected" ).val();
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
		
     $('#select_bus_modal').html(data);
	  $("#search_trip").attr("disabled", false);
    }
   })
  }
 });



}); 

 
