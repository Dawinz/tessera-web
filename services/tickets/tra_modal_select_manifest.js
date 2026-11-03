 $(document).ready(function(){


 $("#starting_date_select_manifest").change(function(){
		 var trip_end_modal = $( "#trip_end_modal option:selected" ).text();
		var starting_date_select = $( "#starting_date_select_manifest" ).val();
	 var trip_start_modal = $( "#trip_start_modal_manifest" ).val();
  if($(this).val() != '')
  {
	 	  $("#search_trip_manifest_new").attr("disabled", true);

   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';


   $.ajax({
    url:"fetch_dependent_ticket_date.php",
    method:"POST",
    data:{action:action, query:query,trip_end_modal:trip_end_modal,trip_start_modal:trip_start_modal},
    success:function(data){
		
     $('#select_bus_modal_manifest').html(data);
	  $("#search_trip_manifest_new").attr("disabled", false);
    }
   })
  } 
  
  });



}); 

 
