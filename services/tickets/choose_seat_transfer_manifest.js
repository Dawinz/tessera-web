$(document).ready(function(){




 $('.seat_name').click(function() {
	  
	var bid = $(this).text();

select_all_seats_shift_t = $("#select_all_seats_shift_t").text();
start_location = $("#trip_start_tranit_manifest").val();
end_location = $("#trip_end_tranit_manifest").val();
  
start_location_shift_t = $("#start_location_shift_t_m").text();
ending_location_shift_t = $("#ending_location_shift_t_m").text();
route_day_id_shift_t = $("#route_day_id_shift_t_m").text();
trip_date_shift_t = $("#trip_date_shift_t_m").text();
route_id_shift_t = $("#route_id_shift_t_m").text();
seat_shift_t = $("#ticket_array_list").text();
seat_array_list = $("#seat_array_list").text();
bus_shift_t = $("#bus_shift_t_m").text();
current_route_id = $("#current_route_id").text();
print_id = $("#print_id").text();

if(seat_array_list===""){

			 control.disabled="disabled";
}
$('audio#pop')[0].play();
	 	$(this).removeClass("available_new_seat");
	$(this).addClass("available_new_seat_complete");


		let myStr = seat_array_list;
let firstWord = myStr.split(",")[0];
					
$('#check_box'+firstWord).prop('checked', false);
$('#d_despair'+firstWord).remove();
$.ajax({
				url:"transfer_selected_seats.php",
				method:"POST",
				data:{print_id:print_id,start_location:start_location,end_location:end_location,seat_shift_t:seat_shift_t,current_route_id:current_route_id,start_location_shift_t:start_location_shift_t,ending_location_shift_t:ending_location_shift_t,route_day_id_shift_t:route_day_id_shift_t,trip_date_shift_t:trip_date_shift_t,route_id_shift_t:route_id_shift_t,seat_shift_t:seat_shift_t,bus_shift_t:bus_shift_t,bid:bid},
							beforeSend:function()
	    		{

	    	  $('#load_user_t_content_manifest').html('<div class="placeholder-glow"> <span class="placeholder col-12"style=width:600px;height:200px;"></span></div>');
	    		},
				success:function(response){
//alert(response)
			new_response = $.trim(response).replace(/ /g,",");		
			 $("#ticket_array_list").text(new_response);
			 
			 
		print_id = $("#print_id").text();
		$.ajax({
				url:"search/transfer_print_table.php",
				method:"POST",
				data:{print_id:print_id},
			
				success:function(response){
				
		$("#load_user_t_content_manifest").html(response);
				
	}
			
			});	

	}
			});
			
			
			

			
			$.ajax({
				url:"Remain_seat.php",
				method:"POST",
				data:{seat_array_list:seat_array_list,bid:bid},
				success:function(response){
					
			 new_response = $.trim(response).replace(/ /g,",");	
$("#seat_array_list").text(new_response);

select_all_seats=new_response;
if(select_all_seats===""){
	$(".seat_name_class_manifset").html("<span class='text-success'>All the seats are have transfered successfully</span>");
}else{
	$.ajax({
				url:"md_list_select.php",
				method:"POST",
				data:{select_all_seats:select_all_seats},
				success:function(response){
				$(".seat_name_class_manifset").html(response);
	
	}
			
			});
			
			
			
				$.ajax({
				url:"search/transfer_print_table.php",
				method:"POST",
				data:{print_id:print_id},
				success:function(response){
		$("#load_user_t_content_manifest").html(response);
				
	}
			
			});	
			
}

	}
			});


});



});