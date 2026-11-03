$(document).ready(function(){
   $("#form_trip_m").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
		
		
		
			$.ajax({
            type: 'POST',
            url: 'update/trip_time_auto_route.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#search_time").attr("disabled", true);
	  $(".time_text").text("Adding time..");
            
            },
            success: function(response){
	
				$("#search_time").attr("disabled", false);
                $("#response_m").html(response);
				id = $("#route_day_id_r").val();
				route_id = $("#route_id_r").val();
						$.ajax({
				url:"search/route_time.php",
				method:"POST",
				
				beforeSend:function()
	    		{
 //$("#load_manually_time").html("<div class=`placeholder-glow` ><span class=`placeholder col-12`style=`height:300px`></span></div>");		
	    		},
				data:{id:id,route_id:route_id},
				success:function(response){
		$("#load_route_time").html(response);
}
			
			});
				
		 }
        });	
		
		
});
});