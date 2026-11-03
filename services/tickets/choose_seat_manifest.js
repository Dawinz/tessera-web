$(document).ready(function(){

fare_price= $("#fare_price ").text();
fare_price=parseInt(fare_price);
route_day_id2= $("#route_day_id").text();
trip_date= $("#trip_date ").text();
route_id= $("#route_id ").text();
countries = $("#countries").html();

var arr = [
 
];
 $('.seat_name').click(function() {
	 
	 contact_holder  = $("#contact_holder").html();

	
	 
	 
	  trip_start= $("#trip_start option:selected").text();
trip_end= $("#trip_end option:selected").text();

	this_class = $(this).attr("class");
	
	
	if((this_class==="first available_new_seat seat_col seat_name") || (this_class==="first seat_col seat_name available_new_seat")){

$('audio#pop')[0].play();
	
	$(this).removeClass("available_new_seat");
	$(this).addClass("available_new_seat_booked");
	 
	 var start_location= $("#start_location3").text();
var ending_location= $("#ending_location3").text();
 total_price_ticket= $("#total_price_ticket ").text();
total_price_ticket=parseInt(total_price_ticket);
new_ticket = fare_price + total_price_ticket;
$("#total_price_ticket ").text(new_ticket);

	 var confm_text = $('.confm_text').text();
	 
	 
 $('.accordion-content').hide("fast");
 	var imgSrc = $(".down_image").attr('src');
	
	 if (imgSrc == 'images/up-arrows5.png') {
		 
		   $('.down_image').attr('src','images/arrow_up.png');
	 }else{

	 }
	

        var bid = $(this).text();
		
		
		new_bid = bid;
push_text = '<span class="text-danger"style="font-size:17px;font-weight:bold;"> '+bid+' </span><img src="images/arrow-right_grey.png"> <span style="color:white;">'+fare_price+'</span> <br>';

arr.push(push_text);

      $('.confm_text').html(arr);
	  
	  
	  
	count++;

		$('#item_table').append(add_input_field(count));


	 	if($('.copy_seat_info:checked').length == $('.copy_seat_info').length){
			       var firstname = $('.firstname').val();
        var lastname = $('.lastname').val();
		var phone_no = $('.phone_no').val();
		var verification = $('.verification').val();
		var age = $('.age').val();

$("input:radio[value=female]").each(function(){
              if(this.checked){

				
				$("input:radio[value=female]").each(function(){
 this.checked = true;
				
					});
				
				
				
				
			  }else{
				  				 

			  }
    
	
			});
			$("input:radio[value=male]").each(function(){
              if(this.checked){

				
				$("input:radio[value=male]").each(function(){
 this.checked = true;
				
					});
				
				
				
				
			  }else{
				  				 

			  }
    
	
			});
		
		$('.firstname').val(firstname);
		$('.lastname').val(firstname);
		$('.phone_no').val(phone_no);
		$('.verification').val(verification);
		$('.age').val(age);
	
		
		
		
	
	}else{

	}

		$('.selectpicker').selectpicker('refresh');





	  	}else if ((this_class==="first available_new_seat_half seat_col seat_name")|| (this_class==="first seat_col seat_name available_new_seat available_new_seat_half")|| (this_class==="first seat_col seat_name available_new_seat_half")){
	
	
	
	$(this).removeClass("available_new_seat_half");
	$(this).addClass("available_new_seat_half_booked");
	 $('audio#pop')[0].play();
	 var start_location= $("#start_location3").text();
var ending_location= $("#ending_location3").text();
 total_price_ticket= $("#total_price_ticket ").text();
total_price_ticket=parseInt(total_price_ticket);
new_ticket = fare_price + total_price_ticket;
$("#total_price_ticket ").text(new_ticket);

	 var confm_text = $('.confm_text').text();
	 
	 
 $('.accordion-content').hide("fast");
 	var imgSrc = $(".down_image").attr('src');
	
	 if (imgSrc == 'images/up-arrows5.png') {
		 
		   $('.down_image').attr('src','images/arrow_up.png');
	 }else{

	 }
	

        var bid = $(this).text();
		
		
		new_bid = bid;
push_text = '<span class="text-danger"style="font-size:17px;font-weight:bold;"> '+bid+' </span><img src="images/arrow-right_grey.png"> <span style="color:white;">'+fare_price+'</span> <br>';

arr.push(push_text);

      $('.confm_text').html(arr);
	  
	  
	  
	count++;

		$('#item_table').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');





		}else{
			
									var bid = $(this).text();
push_text = '<span class="text-danger"style="font-size:17px;font-weight:bold;"> '+bid+' </span><img src="images/arrow-right_grey.png"> <span style="color:white;">'+fare_price+'</span> <br>';

index = arr.indexOf(push_text);
arr.splice(index, 1);
 $('.confm_text').html(arr);
total_price_ticket= $("#total_price_ticket ").text();
total_price_ticket=parseInt(total_price_ticket);
new_ticket =  total_price_ticket-fare_price;
$("#total_price_ticket ").text(new_ticket);

			$('audio#pop')[0].play();
			var bid = $(this).text();
		
		
		new_bid = bid;
		
		 $('.tr'+new_bid+'').remove();
		
		 
		 if(this_class==="first seat_col seat_name available_new_seat_half_booked"){
			 	
	$(this).removeClass("available_new_seat_half_booked");
		$(this).addClass("available_new_seat_half");
			
		 }else{
			 $(this).addClass("available_new_seat");
	$(this).removeClass("available_new_seat_booked"); 

		 }
	
	
	

		
	}
	
	

	
	  
                });

	var count = 0;
	
	function add_input_field(count)
	{


		var html = '';

		html += '<tr class="tr'+new_bid+'">';

		html += '<td><input type="text" name="firstname[]" class="form-control firstname" id="ftn'+count+'" /></td>';

		html += '<td><input type="text" name="lastname[]" class="form-control lastname" id="lst'+count+'" /></td>';
		html += '<td><div class="input-group"><div class="input-group-text"> <select name="inter_phone[]" class="no_code"style="background:none;border:none;color:#7E93A0;" id="intl'+count+'">'+contact_holder+'</select></div><input type="number" name="phone_no[]"class="form-control phone_no" id="'+count+'" placeholder=""></div><div style="z-index: 100;display:none;position: absolute;height:fill-content" class="card response_card"id="search_container'+count+'"></div></td>';
		html += '<td style="width:100px;"><span >F <input type="radio" id="age1"  name="gender'+count+'[]" class="gender_value'+count+'"value="female"> M <input type="radio" class="gender_value'+count+'" id="age" name="gender'+count+'[]" value="male"></span></td>';
		html += '<td style="width:120px;"><select name="countries[]" class="selectpicker form-select country " id="countries'+count+'"><option value=""class="country_holder">Choose</option>'+countries+'</td>';

		html += '<td style="width:120px;"><select name="id_type[]" class="form-select id_type" id="id_type'+count+'"><option>Id type</option><option>Passport</option><option>Id</option></select></td>';
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
	
	

	
	
	
	$(document).on('keyup', '.phone_no', function(){
		
		var this_phone_id = $(this).attr('id');
		
var check = $("#search"+count).text();
var query = $(this).val();
seat_id= this_phone_id;
$("#search_container"+this_phone_id).show();
      if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'searching_phone.php',
                                method: 'POST',
                                data: {
									
                                    search: 1,seat_id:seat_id,
                                    q: query
                                },
                                success: function (data) {
                                    $("#search_container"+this_phone_id).html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
 if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
	});
	
	
	

$(document).on('change','.foc', function(){
foc = $(this).val();

$('#foc_panel').modal('show');
my_foc_id = $(this).attr('id')
my_foc_id = my_foc_id.replace(/\D/g,'');
$("#foc_text").text(my_foc_id);



});

$(document).on('change','.transit', function(){

$('#trans_panel').modal('show');
my_trans_id = $(this).attr('id')
my_trans_id = my_trans_id.replace(/\D/g,'');
$("#trans_text").text(my_trans_id);
});
$(document).on('change','.advance', function(){
$(".t_price").text(fare_price);
$('#advance_panel').modal('show');
my_advc_id = $(this).attr('id')
my_advc_id = my_advc_id.replace(/\D/g,'');
$("#advance_chcek_text").text(my_advc_id);

});


	
	
$( "body" ).click(function() {
$(".response_card").hide();
});

	$('.selectpicker').selectpicker('refresh');
	
	
			


});