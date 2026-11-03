$(document).ready(function(){




 $('.seat_name').click(function() {
	  $('audio#pop')[0].play();
	 	$(this).removeClass("available_new_seat");
	$(this).addClass("available_new_seat_booked");
	
	  trip_start= $("#trip_start_modal option:selected").text();
trip_end= $("#trip_end_modal option:selected").text();



	var bid = $(this).text();
ticket_no_original= $("#ticket_no_original").text();
start_location_shift_t = $("#start_location_shift_t").text();
ending_location_shift_t = $("#ending_location_shift_t").text();
route_day_id_shift_t = $("#route_day_id_shift_t").text();
trip_date_shift_t = $("#trip_date_shift_t").text();
route_id_shift_t = $("#route_id_shift_t").text();
seat_shift_t = $("#seat_shift_t").text();
bus_shift_t = $("#bus_shift_t").text();

Swal.fire({
  title: 'Are you sure?',
  html: "<span style='color:white;'>You are about to Transfer Selected seat<span>",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Transfer!'
}).then((result) => {
  if (result.isConfirmed) {
	  	  trip_start= trip_start.replace(/ /g,"_");
		  trip_end= trip_end.replace(/ /g,"_");
	  start_location_shift_t=start_location_shift_t.replace(/ /g,"_");
	  ending_location_shift_t=ending_location_shift_t.replace(/ /g,"_");
	  $("#load_class").load("load_seat_chart_transfer.php?start_location="+start_location_shift_t+"&ending_location="+ending_location_shift_t+"&id="+route_day_id_shift_t+"&trip_date="+trip_date_shift_t+"&route_id="+route_id_shift_t+"&seat_no="+seat_shift_t+"&bus="+bus_shift_t+"&trip_start="+trip_start+"&trip_end="+trip_end);

	  	$('.exampleModalFullscreen').modal('show');
    $.ajax({
				url:"update_seat.php",
				method:"POST",
				data:{bid:bid,ticket_no_original:ticket_no_original,start_location_shift_t:start_location_shift_t,ending_location_shift_t:ending_location_shift_t,route_day_id_shift_t:route_day_id_shift_t,trip_date_shift_t:trip_date_shift_t,route_id_shift_t:route_id_shift_t,seat_shift_t:seat_shift_t,bus_shift_t:bus_shift_t,trip_start:trip_start,trip_end:trip_end},
				success:function(response){
		
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket.php?id="+ticket_no_original;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
	}
			
			});
  }
})



});});