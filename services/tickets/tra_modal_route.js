$("#trip_start_modal_manifest").change(function(){
			 var trip_end_modal = $( "#trip_end_modal option:selected" ).text();
		var starting_date_select = $( "#starting_date_select_manifest" ).val();
	 var trip_start_modal =  $(this).val();
  if($(this).val() != '')
  {
	 	  $("#search_trip_manifest_new").attr("disabled", true);

   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';


   $.ajax({
    url:"dependence/route_date_load_bus.php",
    method:"POST",
	beforeSend:function()
	    		{
	    	$('#select_bus_modal_manifest').html("<option> loading...</option>");
	    		},
    data:{starting_date_select:starting_date_select,trip_start_modal:trip_start_modal},
    success:function(data){
		//alert(data);
     $('#select_bus_modal_manifest').html(data);
	  $("#search_trip_manifest_new").attr("disabled", false);
    }
   })
  } 
});