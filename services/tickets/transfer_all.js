$(document).ready(function(){
$("#transfer_all").click(function(){
route_day_id_old = $("#route_day_id_old").text();
route_id_old = $("#route_id_old").text();
trip_date_old= $("#trip_date_old").text();

route_day_id_new_one= $("#route_day_id_new_one").text();
route_id_new_one= $("#route_id_new_one").text();
trip_date_new_one= $("#trip_date_new_one").text();

$("#holder").text('Transfering...');

if(route_day_id_new_one==""){
	$("#holder").text('Transfer all');
	 alertify.error("Select Transfer Date");
alertify.set('notifier','position', 'top-left');
 control.disabled="disabled";

}

   $.ajax({
				url:"transfer_all.php",
				method:"POST",
				data:{route_day_id_old:route_day_id_old,route_id_old:route_id_old,trip_date_old:trip_date_old,route_day_id_new_one:route_day_id_new_one,route_id_new_one:route_id_new_one,trip_date_new_one:trip_date_new_one},
				success:function(response){
					$("#holder").text('Transfer all');
if($.trim(response)==""){
swal("Transfert!", "All Seats are transfered From "+trip_date_old+" To "+trip_date_new_one, "success");
$("#load_current_seats").load("load_current_seats.php?route_day_id="+route_day_id_old+"&route_id="+route_id_old+"&trip_date="+trip_date_old+"");
$("#load_current_seats_chart").load("load_current_seats_chart.php?route_day_id="+route_day_id_old+"&route_id="+route_id_old+"&trip_date="+trip_date_old+"");
$("#load_new_seats").load("load_new_seats.php?route_day_id="+route_day_id_new_one+"&route_id="+route_id_new_one+"&trip_date="+trip_date_new_one+"");
$("#load_new_seats_chart").load("load_new_seats_chart.php?route_day_id="+route_day_id_new_one+"&route_id="+route_id_new_one+"&trip_date="+trip_date_new_one+"");
	$("#holder").text('Transfer all');
	
}else{
	swal("Seat found! ", "Seat "+response+"found", "warning");
$("#load_current_seats").load("load_current_seats.php?route_day_id="+route_day_id_old+"&route_id="+route_id_old+"&trip_date="+trip_date_old+"");
$("#load_current_seats_chart").load("load_current_seats_chart.php?route_day_id="+route_day_id_old+"&route_id="+route_id_old+"&trip_date="+trip_date_old+"");
$("#load_new_seats").load("load_new_seats.php?route_day_id="+route_day_id_new_one+"&route_id="+route_id_new_one+"&trip_date="+trip_date_new_one+"");
$("#load_new_seats_chart").load("load_new_seats_chart.php?route_day_id="+route_day_id_new_one+"&route_id="+route_id_new_one+"&trip_date="+trip_date_new_one+"");
	
}			
				
	}
			
			});



});
});