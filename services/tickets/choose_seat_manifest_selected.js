$(document).ready(function(){



fare_price= $("#fare_price ").text();
fare_price=parseInt(fare_price);
route_day_id2= $("#route_day_id").text();
trip_date= $("#trip_date ").text();
route_id= $("#route_id ").text();
manifest_seat_no= $("#manifest_seat_no").text();


	 

	
	 
	 
	  trip_start= $("#trip_start option:selected").text();
trip_end= $("#trip_end option:selected").text();


	
	new_bid = manifest_seat_no;
	

	  
            

	var count = 0;
	
	function add_input_field(count)
	{


		var html = '';

		html += '<tr class="tr'+manifest_seat_no+'">';

		html += '<td><input type="text" name="firstname[]" class="form-control firstname" id="ftn'+count+'" /></td>';

		html += '<td><input type="text" name="lastname[]" class="form-control lastname" id="lst'+count+'" /></td>';
		html += '<td><div class="input-group"><div class="input-group-text"> <select name="inter_phone[]" class="no_code"style="background:none;border:none;color:#7E93A0;" id="intl'+count+'"> <option >+255</option></select></div><input type="number" name="phone_no[]"class="form-control phone_no" id="'+count+'" placeholder=""></div><div style="z-index: 100;display:none;position: absolute;height:fill-content" class="card response_card"id="search_container'+count+'"></div></td>';
		html += '<td style="width:100px;"><span >F <input type="radio" id="age1"  name="gender'+count+'[]" class="gender_value'+count+'"value="female"> M <input type="radio" class="gender_value'+count+'" id="age" name="gender'+count+'[]" value="male"></span></td>';

		html += '<td><input style="width:40px;"type="text" name="age[]" class="form-control age" id="age'+count+'" /></td>';
		html += '<td><input type="text" name="verification[]" class="form-control verification" id="very'+count+'" /></td>';
		html += '<td><input name="foc[]" type="checkbox" id="switch'+count+'" switch="danger" id="foc" class="foc"/><label for="switch'+count+'" data-on-label="Yes" data-off-label="Foc" class="mb-0 foc'+count+'"></label><br><input name="transit[]"type="checkbox" id="switcht'+count+'" switch="dark"  class="transit"/><label for="switcht'+count+'" data-on-label="Yes" data-off-label="Trans" class="mb-0"class="transit"></label><br><input name="advance[]"type="checkbox" id="switchtad'+count+'" switch="primary"  class="advance"/><label for="switchtad'+count+'" data-on-label="advnc" data-off-label="advance" class="mb-0"class="advance"></label></td>';
		
		html += '<td style="display:none;"><input type="text" name="Foc_info[]" class="form-control foc_info" id="foc_info'+count+'"  /></td>';

		html += '<td style="display:none;"><input type="text" name="female[]" class="form-control female"id="female'+count+'"  /></td>';
		html += '<td style="display:none;"><input type="text" name="male[]" class="form-control male"id="male'+count+'"  /></td>';
		html += '<td style="display:none;"><input type="text" name="trans_info[]" class="form-control trans_info"id="trans_info'+count+'"  /></td>';
        html += '<td style="display:none;"><input type="text" name="start_location[]" class="form-control start_location" value="'+$("#start_location32").text()+'"/></td>';
		html += '<td style="display:none;"><input type="text" name="ending_location[]" class="form-control ending_location" value="'+$("#ending_location32").text()+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="starting_point[]" class="form-control starting_point"value="'+trip_start+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="end_point[]" class="form-control end_point" value="'+trip_end+'"/></td>';
	   html += '<td style="display:none;"><input type="text" name="route_day_id[]" class="form-control route_day_id" value="'+route_day_id2+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="trip_date[]" class="form-control trip_date" value="'+trip_date+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="route_id[]" class="form-control route_id" value="'+route_id+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="seat_id[]" class="form-control seat_id" value="'+new_bid+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="adnce_info[]" class="form-control adnce_info" id="adnce_info'+count+'"  /></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '</tr>';

		return html;




	}
	
	
		count++;

		$('#item_table').append(add_input_field(count));

	
	
	

	
	






	
	

	
	
			


});