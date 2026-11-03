
$("#load_item").click(function () {

	var code = $("#code").val();
	var vehicle_name = $("#manifest_data").data("vehicle");
	var trip_date = $("#manifest_data").data("trip_date");
	var username_uploader = $("#manifest_data").data("username");
	var qty = $('#qty').val();
	var to_be = $('#to_be').val();
	var paid = $('#paid').val();
	transit = "";


	const parcel_info_json = sessionStorage.getItem("parcel_info");

	post_luggage(vehicle_name, code, trip_date, qty, to_be, paid, transit, parcel_info_json, username_uploader);

});
$(document).on("keydown", function (e) {
	if (e.key === "Enter" || e.which === 13) {
		e.preventDefault();

		if ($(".upload_manifest").is(":visible")) {
			$("#load_item").trigger("click");
		}
	}
});
$(".upload_manifest input").on("keypress", function (e) {
	if (e.which === 13) {
		e.preventDefault();
		alert()
	}
});
function post_luggage(vehicle_name, code, trip_date, qty, to_be, paid, transit, parcel_info_json, username_uploader) {

	$.ajax({
		url: "load_cargo.php",
		method: "POST",
		data: { code: code, username_uploader: username_uploader, vehicle_name: vehicle_name, trip_date: trip_date, qty: qty, to_be: to_be, paid: paid, transit: transit, parcel_info_json: parcel_info_json },
		beforeSend: function (xhr, settings) {
			$("#load_item").prop('disabled', true);
			$(".search_loader_load").show();
		},
		success: function (response) {
			//alert(response)
			if (response == "") {

				$("#blink").text("1");
				$("#server_response").html(response);
				$('#empTable').DataTable().ajax.reload();
				transitionTo($('.upload_manifest'), $('.luggage_check'), 'back');
				$('#code').val("");
				var success_message = '<strong>' + code + ' </strong>Added in manifest ';
				showManifestAlert(success_message, type = "success")
			} else {
				error_response(response);
			}
			$("#load_item").prop('disabled', false);
			$(".search_loader_load").hide();

		}


	});
}



function transitionTo($out, $in, direction = 'forward') {
	const outClass = direction === 'forward' ? 'slide-out-left' : 'slide-out-right';
	const inClass = direction === 'forward' ? 'slide-in-right' : 'slide-in-left';

	$out.addClass(outClass);

	setTimeout(() => {
		$out.hide().removeClass(outClass);
		$in.css({ display: 'block', opacity: 0 }).addClass(inClass);

		setTimeout(() => {
			$in.css('opacity', '');
			$in.removeClass(inClass);
		}, DURATION);

	}, DURATION);
}

function error_response(response) {
	const jsObject = JSON.parse(response);

	error = (jsObject.error_no); // Output: John
	msg = (jsObject.msg);


	Swal.fire({
		icon: 'error',
		title: 'Miss match',
		text: msg,
		confirmButtonText: 'OK'
	});


	if (error == "EE001") {
		$("#qty").addClass("is-invalid");
		$("#qty").siblings(".invalid-feedback").remove();
		$("#qty").after('<div class="invalid-feedback">Adjust quantity</div>');
	}
	else if (error == "EE002") {
		$("#qty").addClass("is-invalid");
		$("#qty").siblings(".invalid-feedback").remove();
		$("#qty").after('<div class="invalid-feedback">Adjust quantity</div>');
	}
	else if (error == "EE002") {
		$("#qty").addClass("is-invalid");
		$("#qty").siblings(".invalid-feedback").remove();
		$("#qty").after('<div class="invalid-feedback">Adjust quantity</div>');
	}
	else if (error == "EE008") {
		$("#qty").addClass("is-invalid");
		$("#qty").siblings(".invalid-feedback").remove();
		$("#qty").after('<div class="invalid-feedback">Adjust quantity</div>');
	}
	else if (error == "EE005") {
		$("#to_be").addClass("is-invalid");
		$("#to_be").siblings(".invalid-feedback").remove();
		$("#to_be").after('<div class="invalid-feedback">Adjust To be paid</div>');
	}
	else if (error == "EE006") {
		$("#to_be").addClass("is-invalid");
		$("#to_be").siblings(".invalid-feedback").remove();
		$("#to_be").after('<div class="invalid-feedback">Adjust To be paid</div>');
	}
	else if (error == "EE007") {
		$("#paid").addClass("is-invalid");
		$("#paid").siblings(".invalid-feedback").remove();
		$("#paid").after('<div class="invalid-feedback">Adjust paid</div>');
	}
	else if (error == "EE010") {
		$("#paid").addClass("is-invalid");
		$("#paid").siblings(".invalid-feedback").remove();
		$("#paid").after('<div class="invalid-feedback">Adjust paid</div>');
	}
	else if (error == "EE011") {
		$("#to_be").addClass("is-invalid");
		$("#to_be").siblings(".invalid-feedback").remove();
		$("#to_be").after('<div class="invalid-feedback">Adjust Quantity</div>');

		$("#paid").addClass("is-invalid");
		$("#paid").siblings(".invalid-feedback").remove();
		$("#paid").after('<div class="invalid-feedback">Adjust Quantity</div>');


	}


}
function showManifestAlert(message, type = "success") {
	let section = $(".upload_manifest")[0];

	if (!section) return;

	let rect = section.getBoundingClientRect();

	let centerTop = rect.top + (rect.height / 2);
	let centerLeft = rect.left + (rect.width / 2);

	alertify.set("notifier", "position", "top-center");

	$(".alertify-notifier").css({
		"top": centerTop + "px",
		"left": centerLeft + "px",
		"transform": "translate(-50%, -50%)",
		"position": "fixed"
	});

	alertify[type](message);
}