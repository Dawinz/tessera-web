$(document).ready(function(){
   $("#form_trip_m").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
		
		
		
			$.ajax({
            type: 'POST',
            url: 'update/trip_time_auto.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#search_r").attr("disabled", true);
	  $("#search_r").text("Adding time..");
            
            },
            success: function(response){
		
$("#response_m").html(response);

id = $("#route_day_id_t").val();
route_id = $("#route_id_t").val();
$.ajax({
				url:"search/manullay_trip_time.php",
				method:"POST",
				
				beforeSend:function()
	    		{
 $("#load_manually_time").html("<div class=`placeholder-glow` ><span class=`placeholder col-12`style=`height:300px`></span></div>");		
	    		},
				data:{id:id,route_id:route_id},
				success:function(response){
		$("#load_manually_time").html(response);
}
			
			});

		 }
        });	
		
});
});