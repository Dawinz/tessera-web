manifest_seat_no = $("#manifest_seat_no").text();
if(manifest_seat_no ==""){
	
}else{
	manifest_seat_no = $("#manifest_seat_no").text();
manifest_route_day_id = $("#manifest_route_day_id").text();
manifest_trip_date = $("#manifest_trip_date").text();
manifest_route_id = $("#manifest_route_id").text();
manifest_start_location = $("#manifest_start_location").text();
manifest_ending_location = $("#manifest_ending_location").text();
$("#starting_location").val(manifest_start_location);
$("#ending_location").val(manifest_ending_location);
$("#starting_date").val(manifest_trip_date);
var manifest_start_location = manifest_start_location.replace(/ /g,"_");
var manifest_ending_location = manifest_ending_location.replace(/ /g,"_");


	  $("#load_route_content").html('<div class="placeholder-glow" ><span class="placeholder col-12"style="height:300px;width:700px;"></span></div>');
	$("#load_route_content").load("load_with_go_date_only_front.php?starting_location="+manifest_start_location+"&ending_location="+manifest_ending_location+"&starting_date="+manifest_trip_date+"&manifest_seat_no="+manifest_seat_no+"&route_day_id="+manifest_route_day_id);
											 $.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{start_location:manifest_start_location,route_id:manifest_route_id},
				success:function(response){
		$('#trip_start').html(response);
				
	}
			
			});		

		
			$.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{ending_location:manifest_ending_location,route_id:manifest_route_id},
				success:function(response){
				
		$('#trip_end').html(response);
				
	}
			
			});	

	$("#load_seat_chart").html(" <div> <div class='placeholder-glow' ><span class='placeholder col-12'style='height:650px;width:200px;'></span></div></div>");
	$("#load_seat_chart").load("load_seat_chart_from_manifest.php?id="+manifest_route_day_id+"&trip_date="+manifest_trip_date+"&route_id="+manifest_route_id+"&start_location="+manifest_start_location+"&ending_location="+manifest_ending_location+"&manifest_seat_no="+manifest_seat_no);
	

}
