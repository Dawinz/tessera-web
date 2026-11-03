$(document).ready(function(){


     $('.selectpicker').select2();

var fare_price = 0;
var route_day_id2 = '';
var trip_date = '';
var route_id = '';
var sub_route_id = '';
var countries = '';
var count = 0;
var new_bid = '';
var contact_holder = '';
var trip_start = '';
var trip_end = '';
var start_location = '';
var ending_location = '';

function srPlaySeatPop() {
	var el = document.getElementById('pop');
	if (el && typeof el.play === 'function') {
		el.play().catch(function () {});
	}
}

function srRefreshSeatContext() {
	fare_price = parseInt($('#fare_price').text(), 10) || 0;
	route_day_id2 = $('#route_day_id').text();
	trip_date = $('#trip_date').text();
	route_id = $('#route_id').text();
	sub_route_id = $('#sub_route_id').text();
	countries = $('#countries').html() || countries || '';
}

function srSeatLabel($seat) {
	var label = $seat.find('[id="seat_no"]').first().text();
	return $.trim(label || $seat.text());
}

function srIsAvailableSeat($el) {
	return $el.hasClass('available_new_seat')
		&& !$el.hasClass('available_new_seat_booked')
		&& !$el.hasClass('available_new_seat_booked_r');
}

function srIsHalfSeat($el) {
	return $el.hasClass('available_new_seat_half')
		&& !$el.hasClass('available_new_seat_half_booked');
}

function srRefreshSelectPickers() {
	if ($.fn.selectpicker) {
		$('.selectpicker').selectpicker('refresh');
	}
}

function srSyncPassengerEmptyState() {
	var $empty = $('#schedule-passenger-empty');
	if (!$empty.length) {
		return;
	}
	var hasRows = $('#item_table tbody tr').length > 0;
	$empty.toggleClass('d-none', hasRows);
}
window.srSyncPassengerEmptyState = srSyncPassengerEmptyState;

function srGetPassengerRows() {
	return $('#item_table tbody tr.schedule-passenger-row');
}

var SR_PASSENGER_PILL_LIMIT = 6;
var srJumpSelectSyncing = false;

function srDestroyPassengerJumpSelect() {
	var $jump = $('#schedule-passenger-jump');
	if ($jump.length && $jump.data('select2')) {
		$jump.off('.srJump');
		$jump.select2('destroy');
	}
}

function srInitPassengerJumpSelect() {
	if (!$.fn.select2 || !srIsScheduleBookPage()) {
		return;
	}
	var $jump = $('#schedule-passenger-jump');
	if (!$jump.length || $jump.hasClass('d-none')) {
		return;
	}

	srDestroyPassengerJumpSelect();

	var $parent = $jump.closest('.schedule-panel-card--passengers');
	$jump.select2({
		width: '100%',
		minimumResultsForSearch: 0,
		placeholder: 'Search seat...',
		allowClear: false,
		dropdownParent: $parent.length ? $parent : $(document.body),
		dropdownCssClass: 'schedule-passenger-jump-dropdown',
		language: {
			noResults: function () {
				return 'No matching seat';
			},
			searching: function () {
				return 'Searching...';
			}
		}
	});

	$jump.on('select2:open.srJump', function () {
		setTimeout(function () {
			var $search = $('.schedule-passenger-jump-dropdown .select2-search__field');
			if ($search.length) {
				$search.attr('placeholder', 'Type seat number...').trigger('focus');
			}
		}, 0);
	});
}

function srSetJumpSelectValue(seatId) {
	var $jump = $('#schedule-passenger-jump');
	if (!$jump.length || !seatId) {
		return;
	}
	srJumpSelectSyncing = true;
	$jump.val(seatId);
	if ($jump.data('select2')) {
		$jump.trigger('change.select2');
	}
	srJumpSelectSyncing = false;
}

function srScrollPassengerTabIntoView($tab) {
	var container = document.getElementById('schedule-passenger-pills-wrap');
	if (!container || !$tab || !$tab.length) {
		return;
	}
	var tab = $tab[0];
	var padding = 12;
	var tabLeft = tab.offsetLeft;
	var tabRight = tabLeft + tab.offsetWidth;
	var viewLeft = container.scrollLeft;
	var viewRight = viewLeft + container.clientWidth;
	if (tabLeft < viewLeft + padding) {
		container.scrollLeft = Math.max(0, tabLeft - padding);
	} else if (tabRight > viewRight - padding) {
		container.scrollLeft = tabRight - container.clientWidth + padding;
	}
}

function srUpdatePassengerPosition(activeSeat) {
	var $rows = srGetPassengerRows();
	var total = $rows.length;
	var index = 0;
	$rows.each(function (i) {
		if ($(this).data('seat') === activeSeat) {
			index = i + 1;
		}
	});
	if (index > 0) {
		$('#schedule-passenger-position').text('Passenger ' + index + ' of ' + total);
	}
	$('#schedule-active-seat-badge').text('Seat ' + activeSeat);
	var $jump = $('#schedule-passenger-jump');
	if ($jump.length) {
		srSetJumpSelectValue(activeSeat);
	}
}

function srActivatePassengerSeat(seatId) {
	if (!seatId) {
		return;
	}
	var $rows = srGetPassengerRows();
	$rows.addClass('d-none').removeClass('schedule-passenger-row--active');
	$rows.filter('[data-seat="' + seatId + '"]').removeClass('d-none').addClass('schedule-passenger-row--active');
	$('#schedule-passenger-tabs .nav-link').removeClass('active').attr('aria-selected', 'false');
	var $tab = $('#schedule-passenger-tabs .nav-link[data-seat="' + seatId + '"]');
	$tab.addClass('active').attr('aria-selected', 'true');
	srScrollPassengerTabIntoView($tab);
	srUpdatePassengerPosition(seatId);
}

function srRebuildPassengerTabs(preferredSeat) {
	if (!srIsScheduleBookPage()) {
		srGetPassengerRows().removeClass('d-none schedule-passenger-row--active');
		return;
	}

	var $wrap = $('#schedule-passenger-tabs-wrap');
	var $tabs = $('#schedule-passenger-tabs');
	var $count = $('#schedule-passenger-count');
	var $pillsWrap = $('#schedule-passenger-pills-wrap');
	var $jump = $('#schedule-passenger-jump');
	var $rows = srGetPassengerRows();
	var count = $rows.length;

	if (count === 0) {
		$wrap.addClass('d-none');
		$tabs.empty();
		srDestroyPassengerJumpSelect();
		$jump.empty();
		return;
	}

	if (count === 1) {
		$wrap.addClass('d-none');
		$tabs.empty();
		srDestroyPassengerJumpSelect();
		$jump.empty();
		$rows.removeClass('d-none schedule-passenger-row--active');
		return;
	}

	$wrap.removeClass('d-none');
	$count.text(count === 1 ? '1 passenger selected' : count + ' passengers selected');

	var activeSeat = preferredSeat || $jump.val() || $tabs.find('.nav-link.active').data('seat');
	var hasPreferred = false;
	$rows.each(function () {
		if ($(this).data('seat') === activeSeat) {
			hasPreferred = true;
		}
	});
	if (!hasPreferred) {
		activeSeat = $rows.last().data('seat');
	}

	$jump.empty();
	$rows.each(function () {
		var seat = $(this).data('seat');
		$jump.append('<option value="' + seat + '">Seat ' + seat + '</option>');
	});

	if (count > SR_PASSENGER_PILL_LIMIT) {
		$pillsWrap.addClass('d-none');
		$jump.removeClass('d-none');
		$tabs.empty();
		srInitPassengerJumpSelect();
	} else {
		$pillsWrap.removeClass('d-none');
		$jump.addClass('d-none');
		srDestroyPassengerJumpSelect();
		$tabs.empty();
		$rows.each(function () {
			var seat = $(this).data('seat');
			var isActive = seat === activeSeat;
			$tabs.append(
				'<button type="button" class="nav-link' + (isActive ? ' active' : '') + '" data-seat="' + seat + '" role="tab" aria-selected="' + (isActive ? 'true' : 'false') + '">Seat ' + seat + '</button>'
			);
		});
	}

	srActivatePassengerSeat(activeSeat);
}
window.srRebuildPassengerTabs = srRebuildPassengerTabs;

srRefreshSeatContext();

var arr = [
 
];
 $(document).off('click.srChooseSeat', '.seat_name').on('click.srChooseSeat', '.seat_name', function () {
	 srRefreshSeatContext();

	 contact_holder  = srLoadDialCodeOptions();

	 
	  trip_start= $("#trip_start option:selected").text();
trip_end= $("#trip_end option:selected").text();

	var $seat = $(this);
	
	
	if (srIsAvailableSeat($seat)) {

srPlaySeatPop();
	
	$seat.removeClass("available_new_seat");
	$seat.addClass("available_new_seat_booked");
	 
	 var start_location= $("#start_location2").text();
var ending_location= $("#ending_location2").text();

 total_price_ticket= parseInt($("#total_price_ticket").text(), 10) || 0;
new_ticket = fare_price + total_price_ticket;
$("#total_price_ticket").text(new_ticket);
	 var confm_text = $('.confm_text').text();
	 
 $('.accordion-content').hide("fast");
 	var imgSrc = $(".down_image").attr('src');
	
	 if (imgSrc == 'images/up-arrows5.png') {
		 
		   $('.down_image').attr('src','images/arrow_up.png');
	 }else{

	 }
	

        var bid = srSeatLabel($seat);
		
		
		new_bid = bid;
push_text = '<span class="text-danger"style="font-size:17px;font-weight:bold;"> '+bid+' </span><img src="images/arrow-right_grey.png"> <span style="color:white;">'+fare_price+'</span> <br>';

arr.push(push_text);

      $('.confm_text').html(arr);
	  
	  
	  
	count++;

		$('#item_table').append(add_input_field(count));
		srInitPassengerRowControls($('#item_table tr.tr' + new_bid));
		srSyncPassengerEmptyState();
		srRebuildPassengerTabs(new_bid);

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

		srRefreshSelectPickers();





	  	}else if (srIsHalfSeat($seat)){
	

	
	$seat.removeClass("available_new_seat_half");
	$seat.addClass("available_new_seat_half_booked");
	 srPlaySeatPop();
	 var start_location= $("#start_location").text();
var ending_location= $("#ending_location").text();
 total_price_ticket= parseInt($("#total_price_ticket").text(), 10) || 0;
new_ticket = fare_price + total_price_ticket;
$("#total_price_ticket").text(new_ticket);

	 var confm_text = $('.confm_text').text();
	 
	 
 $('.accordion-content').hide("fast");
 	var imgSrc = $(".down_image").attr('src');
	
	 if (imgSrc == 'images/up-arrows5.png') {
		 
		   $('.down_image').attr('src','images/arrow_up.png');
	 }else{

	 }
	

        var bid = srSeatLabel($seat);
		
		
		new_bid = bid;
push_text = '<span class="text-danger"style="font-size:17px;font-weight:bold;"> '+bid+' </span><img src="images/arrow-right_grey.png"> <span style="color:white;">'+fare_price+'</span> <br>';

arr.push(push_text);

      $('.confm_text').html(arr);
	  
	  
	  
	count++;

		$('#item_table').append(add_input_field(count));
		srInitPassengerRowControls($('#item_table tr.tr' + new_bid));
		srSyncPassengerEmptyState();
		srRebuildPassengerTabs(new_bid);

		srRefreshSelectPickers();





		}else{
						var bid = srSeatLabel($seat);
						var this_class = $seat.attr('class') || '';

push_text = '<span class="text-danger"style="font-size:17px;font-weight:bold;"> '+bid+' </span><img src="images/arrow-right_grey.png"> <span style="color:white;">'+fare_price+'</span> <br>';

index = arr.indexOf(push_text);
arr.splice(index, 1);
 $('.confm_text').html(arr);
total_price_ticket= parseInt($("#total_price_ticket").text(), 10) || 0;
new_ticket =  total_price_ticket-fare_price;
$("#total_price_ticket").text(new_ticket);

			
			srPlaySeatPop();
		
		
		new_bid = bid;
		
		 $('.tr'+new_bid+'').remove();
		 srSyncPassengerEmptyState();
		 srRebuildPassengerTabs();
		 
		 if($seat.hasClass('available_new_seat_half_booked')){
			 	
	$seat.removeClass("available_new_seat_half_booked");
		$seat.addClass("available_new_seat_half");
			
		 }else{
			 $seat.addClass("available_new_seat");
	$seat.removeClass("available_new_seat_booked"); 

		 }
	
	
	

		
	}
	
	

	
	  
                });

	
	function srIsScheduleBookPage() {
		return $('.schedule-booking-panel--page').length > 0;
	}

	function srLoadDialCodeOptions() {
		var $fullList = $('#phone_dial_codes_holder');
		if ($fullList.length && $.trim($fullList.html())) {
			return srNormalizeContactHolder($fullList.html(), true);
		}
		return srNormalizeContactHolder($('#contact_holder').html(), true);
	}

	function srNormalizeContactHolder(html, keepLabel) {
		if (!html || !$.trim(html)) {
			return '<option value="+255">+255 - Tanzania</option>';
		}

		var seen = {};
		var out = '';
		$('<select></select>').html(html).find('option').each(function () {
			var val = $.trim($(this).attr('value') || $(this).text());
			var text = $.trim($(this).text());
			if (!val) {
				return;
			}
			if (seen[val]) {
				return;
			}
			seen[val] = true;
			if (!keepLabel || text === val || text.indexOf(val) !== 0) {
				text = val;
			}
			out += '<option value="' + val + '">' + text + '</option>';
		});

		return out || '<option value="+255">+255 - Tanzania</option>';
	}

	function srInitPhoneDialSelect($context) {
		if (!$.fn.select2 || !srIsScheduleBookPage()) {
			return;
		}

		($context || $(document)).find('.schedule-phone-dial').each(function () {
			var $el = $(this);
			if ($el.data('select2')) {
				return;
			}

			var $phoneCell = $el.closest('.schedule-passenger-phone-cell');
			var $dropdownParent = $phoneCell.length
				? $phoneCell
				: $el.closest('.schedule-panel-card--passengers');

			$el.select2({
				width: '100%',
				minimumResultsForSearch: 0,
				dropdownParent: $dropdownParent,
				dropdownCssClass: 'schedule-phone-dial-dropdown',
				placeholder: '+255',
				dropdownAutoWidth: true,
				templateSelection: function (data) {
					if (!data.id) {
						return data.text;
					}
					return data.id;
				},
				templateResult: function (data) {
					return data.text;
				}
			});

			if (!$el.val()) {
				$el.val('+255').trigger('change');
			}

			$el.on('select2:open', function () {
				$el.closest('.schedule-passenger-row').addClass('schedule-passenger-row--dial-open');
			});
			$el.on('select2:close', function () {
				$el.closest('.schedule-passenger-row').removeClass('schedule-passenger-row--dial-open');
			});
		});
	}

	function srInitPassengerRowControls($row) {
		if (!$row || !$row.length) {
			return;
		}
		srInitPhoneDialSelect($row);
		$row.find('.schedule-phone-dial-native').each(function () {
			var $el = $(this);
			if (!$el.val()) {
				$el.val('+255');
			}
		});
		srRefreshSelectPickers();
	}

	function srSectionHead(icon, title) {
		return '<td class="schedule-passenger-section-head" colspan="1">'
			+ '<span class="schedule-passenger-section-head__label"><i class="' + icon + ' me-1"></i>' + title + '</span>'
			+ '</td>';
	}

	function srFieldCell(fieldClass, label, inputHtml, inputId) {
		var idAttr = inputId ? ' for="' + inputId + '"' : '';
		return '<td class="schedule-field schedule-field--' + fieldClass + '">'
			+ '<label class="schedule-field__label"' + idAttr + '>' + label + '</label>'
			+ inputHtml
			+ '</td>';
	}

	function srPhoneCellHtml(count) {
		var options = srLoadDialCodeOptions();

		if (srIsScheduleBookPage()) {
			return ''
				+ '<td class="schedule-field schedule-field--phone schedule-passenger-phone-cell">'
				+ '<label class="schedule-field__label" for="' + count + '">Mobile number</label>'
				+ '<div class="schedule-phone-field schedule-phone-field--native">'
				+ '<select name="inter_phone[]" class="form-select no_code schedule-phone-dial-native" id="intl' + count + '" aria-label="Country code">' + options + '</select>'
				+ '<input type="tel" name="phone_no[]" class="form-control phone_no" id="' + count + '" placeholder="712 345 678" autocomplete="tel-national">'
				+ '</div>'
				+ '<div class="schedule-phone-lookup response_card" id="search_container' + count + '"></div>'
				+ '</td>';
		}

		return '<td><div class="input-group"><div class="input-group-text"> <select name="inter_phone[]" class="no_code"style="background:none;border:none;color:#7E93A0;" id="intl' + count + '">' + options + '</select></div><input type="text" name="phone_no[]"class="form-control phone_no" id="' + count + '" placeholder=""></div><div style="z-index: 100;display:none;position: absolute;height:fill-content" class="card response_card"id="search_container' + count + '"></div></td>';
	}

	function srPassengerOptionsCell(count) {
		if (!srIsScheduleBookPage()) {
			return '<td style="width:20px;"><input name="foc[]" type="checkbox" id="switch'+count+'" switch="danger" class="foc"/><label for="switch'+count+'" data-on-label="Yes" data-off-label="Foc" class="mb-0 foc'+count+'"></label><input name="transit[]"type="checkbox" id="switcht'+count+'" switch="dark"  class="transit"/><label for="switcht'+count+'" data-on-label="Yes" data-off-label="Tran" class="mb-0"></label><input name="advance[]"type="checkbox" id="switchtad'+count+'" switch="primary"  class="advance"/><label for="switchtad'+count+'" data-on-label="Yes" data-off-label="advc" class="mb-0"></label></td>';
		}

		return ''
			+ '<td class="schedule-passenger-options-cell">'
			+ '<span class="schedule-field__label d-block">Booking options</span>'
			+ '<div class="schedule-passenger-options schedule-passenger-options--page-stack">'
			+ '<div class="schedule-passenger-option">'
			+ '<span class="schedule-passenger-option__label">Free of charge</span>'
			+ '<div class="schedule-passenger-option__switch">'
			+ '<input name="foc[]" type="checkbox" id="switch'+count+'" switch="danger" class="foc"/>'
			+ '<label for="switch'+count+'" data-on-label="Yes" data-off-label="No" class="mb-0 foc'+count+'"></label>'
			+ '</div></div>'
			+ '<div class="schedule-passenger-option">'
			+ '<span class="schedule-passenger-option__label">Transit</span>'
			+ '<div class="schedule-passenger-option__switch">'
			+ '<input name="transit[]" type="checkbox" id="switcht'+count+'" switch="dark" class="transit"/>'
			+ '<label for="switcht'+count+'" data-on-label="Yes" data-off-label="No" class="mb-0"></label>'
			+ '</div></div>'
			+ '<div class="schedule-passenger-option">'
			+ '<span class="schedule-passenger-option__label">Advance payment</span>'
			+ '<div class="schedule-passenger-option__switch">'
			+ '<input name="advance[]" type="checkbox" id="switchtad'+count+'" switch="primary" class="advance"/>'
			+ '<label for="switchtad'+count+'" data-on-label="Yes" data-off-label="No" class="mb-0"></label>'
			+ '</div></div>'
			+ '</div>'
			+ '</td>';
	}

	function add_input_field(count)
	{
		var html = '';
		var pageLayout = srIsScheduleBookPage();

		if (pageLayout) {
			html += '<tr class="tr' + new_bid + ' schedule-passenger-row schedule-passenger-row--pro" data-seat="' + new_bid + '">';
			html += srFieldCell('firstname', 'First name',
				'<input type="text" name="firstname[]" class="form-control firstname" id="ftn' + count + '" placeholder="First name" autocomplete="given-name">',
				'ftn' + count);
			html += srFieldCell('lastname', 'Surname',
				'<input type="text" name="lastname[]" class="form-control lastname" id="lst' + count + '" placeholder="Surname" autocomplete="family-name">',
				'lst' + count);
			html += srFieldCell('gender', 'Gender',
				'<div class="schedule-passenger-gender" role="radiogroup" aria-label="Gender">'
				+ '<label class="schedule-passenger-gender__choice"><input type="radio" name="gender' + count + '[]" class="gender_value' + count + '" value="female"> Female</label>'
				+ '<label class="schedule-passenger-gender__choice"><input type="radio" name="gender' + count + '[]" class="gender_value' + count + '" value="male"> Male</label>'
				+ '</div>');
			html += srPhoneCellHtml(count);
			html += srFieldCell('nationality', 'Nationality',
				'<select name="countries[]" class="selectpicker form-select country" id="countries' + count + '"><option value="" class="country_holder">Select country</option>' + countries + '</select>',
				'countries' + count);
			html += srFieldCell('idtype', 'ID type',
				'<select name="id_type[]" class="form-select id_type" id="id_type' + count + '"><option value="">Select ID type</option><option>Passport</option><option>National id</option></select>',
				'id_type' + count);
			html += srFieldCell('idnumber', 'ID number',
				'<input type="text" name="verification[]" class="form-control verification" id="very' + count + '" placeholder="Passport or national ID">',
				'very' + count);
			html += srPassengerOptionsCell(count);
		} else {
			html += '<tr class="tr' + new_bid + ' schedule-passenger-row" data-seat="' + new_bid + '">';
			html += '<td><input type="text" name="firstname[]" class="form-control firstname" id="ftn' + count + '" /></td>';
			html += '<td><input type="text" name="lastname[]" class="form-control lastname" id="lst' + count + '" /></td>';
			html += srPhoneCellHtml(count);
			html += '<td style="width:80px;"><span>F <input type="radio" id="age1" name="gender' + count + '[]" class="gender_value' + count + '" value="female"> M <input type="radio" class="gender_value' + count + '" id="age" name="gender' + count + '[]" value="male"></span></td>';
			html += '<td style="width:120px;"><select name="countries[]" class="selectpicker form-select country " id="countries' + count + '"><option value=""class="country_holder">Choose</option>' + countries + '</td>';
			html += '<td style="width:120px;"><select name="id_type[]" class="form-select id_type" id="id_type' + count + '"><option value="Id type"class="id_type_holder">Id type</option><option>Passport</option><option>National id</option></select></td>';
			html += '<td style="width:120px;"><input style="width:120px;"type="text" name="verification[]" class="form-control verification" id="very' + count + '" /></td>';
			html += srPassengerOptionsCell(count);
		}
		html += '<td style="display:none;"><input type="text" name="Foc_info[]" class="form-control foc_info" id="foc_info'+count+'"  /></td>';

		html += '<td style="display:none;"><input type="text" name="female[]" class="form-control female"id="female'+count+'"  /></td>';
		html += '<td style="display:none;"><input type="text" name="male[]" class="form-control male"id="male'+count+'"  /></td>';
		html += '<td style="display:none;"><input type="text" name="trans_info[]" class="form-control trans_info"id="trans_info'+count+'"  /></td>';
        html += '<td style="display:none;"><input type="text" name="start_location[]" class="form-control start_location" value="'+start_location+'"/></td>';
		html += '<td style="display:none;"><input type="text" name="ending_location[]" class="form-control ending_location" value="'+ending_location+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="starting_point[]" class="form-control starting_point"value="'+trip_start+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="end_point[]" class="form-control end_point" value="'+trip_end+'"/></td>';
	   html += '<td style="display:none;"><input type="text" name="route_day_id[]" class="form-control route_day_id" value="'+route_day_id2+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="trip_date[]" class="form-control trip_date" value="'+trip_date+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="route_id[]" class="form-control route_id" value="'+route_id+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="seat_id[]" class="form-control seat_id" value="'+new_bid+'" /></td>';
		html += '<td style="display:none;"><input type="text" name="adnce_info[]" class="form-control adnce_info" id="adnce_info'+count+'"  /></td>';
		html += '<td style="display:none;"><input type="text" name="sub_route_id[]" class="form-control sub_route_id" id="sub_route_id'+count+'"  value="'+sub_route_id+'"/></td>';

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
 total_price_ticket= $("#total_price_ticket ").text();
total_price_ticket=parseInt(total_price_ticket);
new_ticket = fare_price - total_price_ticket;
$("#total_price_ticket ").text(new_ticket);
foc = $(this).val();

$('#foc_panel').modal('show');
my_foc_id = $(this).attr('id')
my_foc_id = my_foc_id.replace(/\D/g,'');
$("#foc_text").text(my_foc_id);



});

$(document).on('change','.transit', function(){

 total_price_ticket= $("#total_price_ticket ").text();
total_price_ticket=parseInt(total_price_ticket);
new_ticket = fare_price - total_price_ticket;
$("#total_price_ticket ").text(new_ticket);

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

	srRefreshSelectPickers();

	$(document).on('click', '#schedule-passenger-tabs .nav-link', function () {
		srActivatePassengerSeat($(this).data('seat'));
	});

	$(document).on('change', '#schedule-passenger-jump', function () {
		if (srJumpSelectSyncing) {
			return;
		}
		srActivatePassengerSeat($(this).val());
	});

	$('#schedule-passenger-prev').on('click', function () {
		var $rows = srGetPassengerRows();
		var activeSeat = $rows.filter('.schedule-passenger-row--active').data('seat');
		var idx = -1;
		$rows.each(function (i) {
			if ($(this).data('seat') === activeSeat) {
				idx = i;
			}
		});
		if (idx > 0) {
			srActivatePassengerSeat($rows.eq(idx - 1).data('seat'));
		}
	});

	$('#schedule-passenger-next').on('click', function () {
		var $rows = srGetPassengerRows();
		var activeSeat = $rows.filter('.schedule-passenger-row--active').data('seat');
		var idx = -1;
		$rows.each(function (i) {
			if ($(this).data('seat') === activeSeat) {
				idx = i;
			}
		});
		if (idx >= 0 && idx < $rows.length - 1) {
			srActivatePassengerSeat($rows.eq(idx + 1).data('seat'));
		}
	});

});