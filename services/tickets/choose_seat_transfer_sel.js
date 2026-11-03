$(document).ready(function(){




 $('.seat_name').click(function() {

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
new_id = $("#new_id").text();
trip_start_tranit = $("#trip_start_tranit").val();
trip_end_tranit = $("#trip_end_tranit").val();
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

	
		
		
    $.ajax({
				url:"update_seat.php",
				method:"POST",
				data:{bid:bid,trip_end_tranit:trip_end_tranit,trip_start_tranit:trip_start_tranit,new_id:new_id,ticket_no_original:ticket_no_original,start_location_shift_t:start_location_shift_t,ending_location_shift_t:ending_location_shift_t,route_day_id_shift_t:route_day_id_shift_t,trip_date_shift_t:trip_date_shift_t,route_id_shift_t:route_id_shift_t,seat_shift_t:seat_shift_t,bus_shift_t:bus_shift_t,trip_start:trip_start,trip_end:trip_end},
				success:function(response){
				//alert(response);
$("#seat_no").addClass("disabledbutton");
	$("#passenger_info").DataTable().ajax.reload();

	window.open("print_multiple_ticket.php?id="+ticket_no_original);


trip_start_modal = $("#new_id").text();
select_bus_modal = $("#bus_shift_t").text();
starting_date_select = $("#trip_date_shift_t").text();
ticket_no_original = $("#ticket_no_original").text();
trip_end_modal = "";
		   $.ajax({
    url:"load_seat_chart_transfer_sel.php",
    method:"POST",
    data:{trip_start_modal:trip_start_modal, select_bus_modal:select_bus_modal,trip_end_modal:trip_end_modal,starting_date_select:starting_date_select,ticket_no_original:ticket_no_original},
    success:function(data){
		
     $('#load_class').html(data);
    }
   })
	}
			
			});
  }
})



});});