$(document).ready(function(){
bus = $("#bus_shift_t_m3").text();
trip_date = $("#trip_date_shift_t_m3").text();
route_id = $("#route_id_shift_t_m3").text();
route_day_id = $("#route_day_id_shift_t_m3").text();
start_location_shift_t_m3 = $("#start_location_shift_t_m3").text();
ending_location_shift_t_m3 = $("#ending_location_shift_t_m3").text();
	
$("#bus_t").val(bus);
$("#trip_date_t").val(trip_date);
$("#route_id_t").val(route_id);
$("#route_day_id_t").val(route_day_id);
$("#start_route_t").val(start_location_shift_t_m3);
$("#end_route_id_t").val(ending_location_shift_t_m3);

	var count_trans = 0;
	
	function add_input_field_transfer(count_trans,firstElement,bid)
	{

		var html = '';

		html += '<tr>';
		
		html += '<td><input style="background:none;border:none;"class="form-control" type="text" id="seat_id_trans" name="seat_id_trans[]"value="'+firstElement+'" readonly></td>';
		html += '<td><input style="background:none;border:none;"class="form-control letter'+firstElement+'" type="text" id="'+bid+'" name="seat_id_no[]"value="'+bid+'" readonly></td>';

		var remove_button = '';

		if(count_trans > 0)
		{
			remove_button = '<button id="'+firstElement+'"type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
	


 $('.seat_name').click(function() {
$(this).removeClass("available_new_seat");
$(this).addClass("available_new_seat_booked");
var bid = $(this).text();
$(this).css("pointer-events","none");
select_all_seats_shift_t_m3 = $("#select_all_seats_shift_t_m3").text();
const split_string = select_all_seats_shift_t_m3.split(",");

secontarray = split_string[1];


const firstElement = split_string.shift();
$("#save").prop( "disabled", false );
//alert(firstElement+split_string);

$("#seat_id"+firstElement).hide();
$("#seat_id"+secontarray).addClass('blinker');
$("#seat_id"+secontarray).removeClass('bg-success');
$("#seat_id"+secontarray).addClass('bg-danger');
$("#select_all_seats_shift_t_m3").text(split_string);

if(select_all_seats_shift_t_m3==""){
$("#finish_c").text("Done");
}else{
	
	count_trans++;

		$('#transit_table').append(add_input_field_transfer(count_trans,firstElement,bid));
}

if(split_string==""){
$("#finish_c").text("Done");
}


});

$(document).on('click', '.remove', function(){
	
	$("#finish_c").text("");
this_id =$(this).attr('id');
seat_arragment =$('.letter'+this_id+'').attr('id');
asdadsf = $('.this_l'+seat_arragment+'').text();
$('.this_l'+seat_arragment+'').attr('disabled','disabled');
$('.this_l'+seat_arragment+'').addClass("available_new_seat");
	$('.this_l'+seat_arragment+'').removeClass("available_new_seat_booked"); 
	$('.this_l'+seat_arragment+'').bind("click");
		$('.this_l'+seat_arragment+'').css("pointer-events","auto");;

select_all_seats_shift_t_m3 = $("#select_all_seats_shift_t_m3").text();
const split_string = select_all_seats_shift_t_m3.split(",");
split_string.push(this_id);
//split_string.shift(); 
select_first_elm = $("#select_all_seats_shift_t_m3").text();
if(select_first_elm==""){
	split_string.shift(); 
	$("#select_all_seats_shift_t_m3").text(split_string);	

}else{
$("#select_all_seats_shift_t_m3").text(split_string);	
}




	$(this).closest('tr').remove();
	
	});	
$("#finish_c").text("");
});