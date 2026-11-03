$(document).ready(function(){
$(".lock").click(function(){
	
	$(this).text("Unlock");
});
 $('.seat_name').click(function() {
	 
	lock_test =  $(".lock").text();
lock_test = $.trim(lock_test);
	if($.trim(lock_test) =="l"){
	alert();
	}else{
			$(".radios_attr").show();
	$(".table_td_radio").show();
	$(".lock_seat_td").show();
	}
 $('.accordion-content').hide("fast");
 	var imgSrc = $(".down_image").attr('src');
	
	 if (imgSrc == 'images/up-arrows5.png') {
		 
		   $('.down_image').attr('src','images/arrow_up.png');
	 }else{

	 }
	

        var bid = $(this).text();
      
	count++;

		$('#item_table').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	  
	  
                });

	var count = 0;
	
	function add_input_field(count)
	{


		var html = '';

		html += '<tr>';

		html += '<td class="table_td_radio"style="display:none;"><input type="checkbox" id="switch8" switch="danger"  /><label for="switch8" data-on-label="lock" data-off-label="No" class="mb-0 lock_seat_td"style="display:none;"></label><input type="checkbox" id="switch1" switch="none" checked /> <label for="switch1" data-on-label="Trans " data-off-label="Off" class="mb-0"style="display:none;"></label> <input type="checkbox" id="switch3" switch="bool" checked /><label for="switch3" data-on-label="Foc" data-off-label="No" class="mb-0"style="display:none;"></label><input type="checkbox" id="switch5" switch="warning" checked /><label for="switch5" data-on-label="T,fer" data-off-label="No" class="mb-0"style="display:none;"></label></td>';
		html += '<td><input type="text" name="item_name[]" class="form-control item_name" /></td>';

		html += '<td><input type="text" name="item_quantity[]" class="form-control item_quantity" /></td>';
		html += '<td><div class="input-group"><div class="input-group-text"> <select class="no_code"style="background:none;border:none;color:#7E93A0;" id="inlineFormSelectPref"> <option >+255</option></select></div><input type="text" class="form-control phone_no" id="'+count+'" placeholder=""></div><div style="z-index: 100;display:none;position: absolute;height:fill-content" class="card response_card"id="search_container'+count+'"></div></td>';
		html += '<td style="width:100px;"><span >F <input type="radio" id="age1" name="age" value="30"> M <input type="radio" id="age1" name="age" value="30"></span></td>';

		html += '<td><select name="item_unit[]" class="form-control selectpicker" data-live-search="true"><option value="">Adult</option><option value="">Child</option></select></td>';
		html += '<td><input type="text" name="item_name[]" class="form-control item_name" /></td>';

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

	});
	
$( "body" ).click(function() {
$(".response_card").hide();
});
	$('#item_table').append(add_input_field(0));

	$('.selectpicker').selectpicker('refresh');
	
	
			
 $("#jquery-intl-phone").intlTelInput({
    });

});