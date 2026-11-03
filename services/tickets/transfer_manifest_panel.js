$("#transfer_m_p_new").click(function(){
	
seat_array_list = $("#seat_array_list").text();

if(seat_array_list==""){
	Swal.fire({

  icon: 'error',
  title: 'Select seat first',
  showConfirmButton: false,
  timer: 1500
})
}else{
	

	
	$("#carouselExampleIndicators_manifest").carousel("next");	
	select_all_seats = $("#seat_array_list").text();
	
	var route_day_id_modal =$("#route_day_id_modal_manifest").text();
	 var route_id_modal =$("#route_id_modal_manifest").text();
var start_route_modal = $("#start_route_modal_manifest").text();
var end_route_modal = $("#end_route_modal_manifest").text();
var trip_date_modal = $("#trip_date_modal_manifest").text();
var bus = $("#bus_modal_manifest").text();



	$.ajax({
				url:"md_list_select.php",
				method:"POST",
				data:{select_all_seats:select_all_seats},
				success:function(response){
				$(".seat_name_class_manifset").html(response);
	
				
	}
			
			});
			
			
	route_id_start = $("#sub_route_id_modal_manifest").text();
		 	$.ajax({
				url:"dependence/load_start_route_area_manifest.php",
				method:"POST",
				data:{route_id_start:route_id_start},
				success:function(response){
					
		$("#trip_start_modal_manifest").html(response);
				
				route_id_modal=$("#route_id_modal_manifest").text();
				route_day_id_modal = $("#route_day_id_modal_manifest").text();
				bus_modal_manifest = $("#bus_modal_manifest").text();
				var selected_route_id = $("#trip_start_modal_manifest").val();
				
	$.ajax({
				url:"dependence/load_bus_name.php",
				method:"POST",
				data:{route_id_modal:route_id_modal,route_day_id_modal:route_day_id_modal,bus_modal_manifest:bus_modal_manifest},
				success:function(response){
	$("#select_bus_modal_manifest").html(response);

	}
			
			});
			 var route_id_start = route_id_modal;
			 var sub_route_id_end="";
								$.ajax({
				url:"dependence/start_point.php",
				method:"POST",
			 
				data:{route_id_start:route_id_start,sub_route_id_end:sub_route_id_end},
				success:function(response){
				
	$("#trip_start_tranit_manifest").html(response);
				
	}
			
			});		

			
								$.ajax({
				url:"dependence/end_point.php",
				method:"POST",
				data:{route_id_start:route_id_start,sub_route_id_end:sub_route_id_end},
				success:function(response){
					
	$("#trip_end_tranit_manifest").html(response);
				
	}
			
			});	
			
				id=route_day_id_modal;
		route_id=route_id_modal;
		trip_date=trip_date_modal;
		 current_route_id = $("#trip_start_modal_manifest").val();
		select_all_seats=select_all_seats;
		$.ajax({
				url:"load_seat_chart_transfer_manifest.php",
				method:"POST",
				data:{id:id,bus:bus,route_id:route_id,trip_date:trip_date,current_route_id:current_route_id,select_all_seats:select_all_seats},
				success:function(response){
				
		$("#load_class_manifest").html(response);
				
	}
	
			
			});
    }
   });	
			
				$.ajax({
				url:"load_bus_name.php",
				method:"POST",
				data:{route_id_modal:route_id_modal,route_day_id_modal:route_day_id_modal},
				success:function(response){
				
		$("#select_bus_modal2").html(response);
				
	}
			
			});
			var start_route_modal2 = $("#start_route_modal_manifest").text().replace(/ /g,"_");
			 var end_route_modal2 = $("#end_route_modal_manifest").text();

			
			
//$("#load_class_manifest").load("load_seat_chart_transfer_manifest.php?start_location="+start_route_modal2+"&ending_location="+end_route_modal2+"&id="+route_day_id_modal+"&route_id="+route_id_modal+"&trip_date="+trip_date_modal+"&bus="+bus+"&current_route_id="+current_route_id+"&select_all_seats="+select_all_seats);
//$("#load_user_t_content_manifest").load("manifest_passenger.php?start_location="+start_route_modal2+"&ending_location="+end_route_modal2+"&id="+route_day_id_modal+"&route_id="+route_id_modal+"&trip_date="+trip_date_modal+"&bus="+bus+"&select_all_seats="+select_all_seats);
 	

   										 


	/*							$.ajax({
				url:"dependence/start_point.php",
				method:"POST",
				data:{route_id_start:route_id_start},
				success:function(response){
					
	alert($("#trip_start_tranit_manifest").val());
				
	}
			
			});	*/
 $("#carouselExampleIndicators_manifest").carousel({
  interval: false,
});
			
}
});

$("#modal_prev_manifest").click(function(){
$("#carouselExampleIndicators_manifest").carousel("prev");	
$("#manifest_place_holder").load("passenger_manifest_whole.php?id="+route_day_id_modal+"&trip_date="+trip_date_modal+"&route_id="+route_id_modal+"&bus="+bus);

	
});
$("#reservation_id").click(function(){
	
		select_all_seats = $("#ticket_array_list").text();
	
		if(select_all_seats===""){
	Swal.fire({

  icon: 'error',
  title: 'Select seat first',
  showConfirmButton: false,
  timer: 1500
})
}else{
	
$(".bs-example-modal-xl_manifest").modal('hide');
$("#myModal_resverd").modal('show');
seat_array_list_text = $("#seat_array_list").text();
$(".seat_name_class_list").text(seat_array_list_text);

select_all_seats = $("#seat_array_list").text();
	
var route_day_id_modal =$("#route_day_id_modal_manifest").text();
var route_id_modal =$("#route_id_modal_manifest").text();
var start_route_modal = $("#start_route_modal_manifest").text();
var end_route_modal = $("#end_route_modal_manifest").text();
var trip_date_modal = $("#trip_date_modal_manifest").text();
var bus = $("#bus_modal_manifest").text();



}

});
$("#complete_reservation").click(function(){
$("#complete_reservation").attr("disabled", true);
	var route_day_id_modal =$("#route_day_id_modal_manifest").text();
	 var route_id_modal =$("#route_id_modal_manifest").text();
var start_route_modal = $("#start_route_modal_manifest").text();
var end_route_modal = $("#end_route_modal_manifest").text();
var trip_date_modal = $("#trip_date_modal_manifest").text();
var bus = $("#bus_modal_manifest").text();
select_all_seats = $("#seat_array_list").text();



	$.ajax({
				url:"update_reserved_array.php",
				method:"POST",
				data:{route_id_modal:route_id_modal,trip_date_modal:trip_date_modal,select_all_seats:select_all_seats,route_day_id_modal:route_day_id_modal},
				success:function(response){
				$("#passenger_info").DataTable().ajax.reload();

$("#complete_reservation").attr("disabled", false);
				Swal.fire(
  'Complete!',
  '',
  ''
)
	}
			
			});	


});
$("#delete_selected_booking").click(function(){
	select_all_seats = $("#ticket_array_list").text();
	if(select_all_seats===""){
	Swal.fire({

  icon: 'error',
  title: 'Select seat first',
  showConfirmButton: false,
  timer: 1500
})
}else{
$("#myModal_delete_manifest").modal('show');
	$(".bs-example-modal-xl_manifest").modal('hide');
select_all_seats = $("#ticket_array_list").text();
seat_array_list_text = $("#seat_array_list").text();
	$(".seat_name_class_list").text(seat_array_list_text);
}
	
	
	


});
$("#cancel_reservation").click(function(){
	$("#cancel_reservation").attr("disabled", true);

		var route_day_id_modal =$("#route_day_id_modal_manifest").text();
	 var route_id_modal =$("#route_id_modal_manifest").text();
var start_route_modal = $("#start_route_modal_manifest").text();
var end_route_modal = $("#end_route_modal_manifest").text();
var trip_date_modal = $("#trip_date_modal_manifest").text();
var bus = $("#bus_modal_manifest").text();
resoons_to_delete = $(".resoons_to_delete").val();

if(resoons_to_delete==""){
		Swal.fire({

  icon: 'error',
  title: 'please let us know why you want to delete',
  showConfirmButton: false,
  timer: 3500
})
	$("#cancel_reservation").attr("disabled", false);

}else{
	$("#delete_text").text("Deleting...");
	$.ajax({
				url:"delete_reserved.php",
				method:"POST",
				data:{resoons_to_delete:resoons_to_delete,route_id_modal:route_id_modal,trip_date_modal:trip_date_modal,select_all_seats:select_all_seats,route_day_id_modal:route_day_id_modal},
				success:function(response){
						$("#delete_text").text("Complete");

					$("#cancel_reservation").attr("disabled", false);

$("#complete_reservation").attr("disabled", false);
if($.trim(response)=="not"){
Swal.fire({

  icon: 'error',
  title: 'You are not authorized to delete',
  showConfirmButton: false,
  timer: 1500
})
}else if($.trim(response)=="past"){
Swal.fire({

  icon: 'error',
  title: 'you can not delete a past ticket',
  showConfirmButton: false,
  timer: 1500
})
}else if($.trim(response)=="no ticket"){
Swal.fire({

  icon: 'error',
  title: 'Ticket not found',
  showConfirmButton: false,
  timer: 1500
})
}else{
$("#myModal_delete_manifest").modal("hide");

Swal.fire({

  icon: 'success',
  title: 'Ticket Deleted',
  showConfirmButton: false,
  timer: 1500
})	
}
	}
			
			});
}
});
$("#print_multiple").click(function(){
	
	select_all_seats = $("#ticket_array_list").text();
	
		if(select_all_seats===""){
	Swal.fire({

  icon: 'error',
  title: 'Select seat first',
  showConfirmButton: false,
  timer: 1500
})
}else{
	
	
	$(".bs-example-modal-xl_manifest").modal('hide');
	$(".exampleModalFullscreen").modal('show');
select_all_seats = $("#ticket_array_list").text();


			
			
				var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_multiple_ticket_manifest.php?print_id="+select_all_seats;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	

}
});