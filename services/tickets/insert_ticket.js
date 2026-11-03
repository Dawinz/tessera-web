$(document).ready(function () {
	var manifest_id = $("#manifest_id").text();
	var manifest_seat_no = $("#manifest_seat_no").text();
	manifest_route_day_id = $("#manifest_route_day_id").text();
	manifest_trip_date = $("#manifest_trip_date").text();
	manifest_route_id = $("#manifest_route_id").text();
	manifest_start_location = $("#manifest_start_location").text();
	manifest_ending_location = $("#manifest_ending_location").text();

	function srReloadPassengerInfoTable() {
		if (window.SR_reloadPassengerInfoTable) {
			window.SR_reloadPassengerInfoTable();
			return;
		}
		if (!$.fn.DataTable) {
			return;
		}
		var $table = $('#passenger_info');
		if ($table.length && $.fn.DataTable.isDataTable($table)) {
			$table.DataTable().ajax.reload();
		}
	}

	$('#insert_form').on('submit', function (event) {
		sub_route_id = $("#sub_route_id").text();


		$("input:radio[value=female]").each(function () {
			if (this.checked) {


				buton_class = $(this).attr('class').replace(/\D/g, '');

				$("#female" + buton_class).val("female");
				$("#male" + buton_class).val("");
			} else {


			}


		});
		$("input:radio[value=male]").each(function () {
			if (this.checked) {


				buton_class = $(this).attr('class').replace(/\D/g, '');

				$("#male" + buton_class).val("male");
				$("#female" + buton_class).val("");
			} else {


			}


		});




		event.preventDefault();

		var error = '';

		count = 1;

		$('.firstname').each(function () {

			if ($(this).val() == '') {
				error += "<li>Please Enter firstname at " + count + " Row</li>";
				$(this).css('border', '1px solid #043A5A');

			}

			count = count + 1;

		});

		count = 1;

		$('.phone_no').each(function () {

			if ($(this).val() == '') {

				error += "<li>Please Enter Phone number at " + count + " Row</li>";


			}

			count = count + 1;

		});




		if (error == '') {

			var form_data = $(this).serialize();
			var $bookingForm = $(this);
			confrimd_seats = $(".confm_text").html();
			total_price_ticket = $(".t_book_d").html();

			Swal.fire({
				title: 'Confirm booking',
				html: '<p class="schedule-book-confirm__lead">Check passenger names, mobile numbers, and seats before you continue.</p>',
				icon: 'question',
				showDenyButton: true,
				showCancelButton: true,
				confirmButtonText: 'Book ticket',
				denyButtonText: 'Reserve seat',
				cancelButtonText: 'Go back',
				buttonsStyling: false,
				reverseButtons: true,
				focusCancel: true,
				customClass: {
					popup: 'schedule-book-confirm',
					title: 'schedule-book-confirm__title',
					htmlContainer: 'schedule-book-confirm__body',
					actions: 'schedule-book-confirm__actions',
					confirmButton: 'btn btn-primary px-4',
					denyButton: 'btn btn-soft-warning px-4',
					cancelButton: 'btn btn-soft-secondary px-4',
					icon: 'schedule-book-confirm__icon'
				}
			}).then((result) => {

				if (result.isConfirmed) {
					route_id_selected = $("#route_id_selected").val();
					var form_data = new FormData($bookingForm[0]);
					form_data.append('route_id_main', route_id_selected);

					$.ajax({

						url: "insert_multiple_passengers.php",

						method: "POST",

						data: form_data,
						contentType: false,   // required when using FormData
						processData: false,
						beforeSend: function () {
							$('#submit_button').attr('disabled', 'disabled');
							$('#loader_button').text("Booking....");
						},
						success: function (data) {
							//alert(data);
							$('#loader_button').text("Continue");
							if ($.trim(data) == "no_full") {
								if (window.TesseraStates) {
									TesseraStates.toastErr('Full book error', 'You cannot book the full way for this selection.');
								} else {
									Swal.fire({
										icon: 'error',
										title: 'Full Book Error',
										html: '<span style="color:white;">You can not book full way</span>!',
									});
								}
							} else if ($.trim(data) == "finshed") {
								if (window.TesseraStates) {
									TesseraStates.toastErr('Seat occupied', 'That seat is already taken. Pick another.');
								} else {
									Swal.fire({
										icon: 'error',
										title: 'Occupied',
										html: '<span style="color:white;">Seat is already occupied</span>!',
									});
								}
							} else {
								id = $("#route_day_id").text();
								trip_date = $("#trip_date").text();
								route_id = $("#route_id").text();
								start_location = $("#start_location2").text().replace(/ /g, "_");

								ending_location = $("#ending_location2").text().replace(/ /g, "_");
								bus = $("#bus").text();

								fare_price = $("#fare_price").text();
								$("#total_price_ticket ").text("0");
								$('.confm_text').html("");


								if (manifest_seat_no !== "") {
									var start_location3 = $("#manifest_start_location").text();
									var ending_location3 = $("#manifest_ending_location").text();
									manifest_start_location = start_location3.replace(/ /g, "_");

									manifest_ending_location = ending_location3.replace(/ /g, "_");


									$("#load_seat_chart").load("load_seat_chart_from_manifest.php?id=" + id + "&trip_date=" + trip_date + "&route_id=" + route_id + "&start_location=" + manifest_start_location + "&ending_location=" + manifest_ending_location + "&bus=" + bus + "&fare_price=" + fare_price + "&sub_route_id=" + sub_route_id);

								} else if (manifest_id !== "") {
									var start_location3 = $("#manifest_start_location").text();
									var ending_location3 = $("#manifest_ending_location").text();
									manifest_start_location = start_location3.replace(/ /g, "_");
									manifest_ending_location = ending_location3.replace(/ /g, "_");
									$("#load_seat_chart").load("load_seat_chart_from_manifest.php?id=" + id + "&trip_date=" + trip_date + "&route_id=" + route_id + "&start_location=" + manifest_start_location + "&ending_location=" + manifest_ending_location + "&bus=" + bus + "&fare_price=" + fare_price);
								} else {
									$("#load_seat_chart").load("load_seat_chart.php?id=" + id + "&trip_date=" + trip_date + "&route_id=" + route_id + "&start_location=" + start_location + "&ending_location=" + ending_location + "&bus=" + bus + "&fare_price=" + fare_price + "&sub_route_id=" + sub_route_id);

								}




								$('#load_seat_chart').addClass("stop");
								srReloadPassengerInfoTable();

								if ($.trim(data) == 'log_out') {
									window.location = 'index.php';
								} else if (data == "finished") {
									if (window.TesseraStates) {
										TesseraStates.toastErr('Booking closed', 'You cannot add more passengers.');
									} else {
										Swal.fire({
											icon: "error",
											title: "Closed",
											text: "You cannot add more passenger"
										});
									}
								} else if (data == "full_no") {
									if (window.TesseraStates) {
										TesseraStates.toastErr('Booking failed', 'Something went wrong. Please try again.');
									} else {
										Swal.fire({
											icon: "error",
											title: "Closed",
											text: "Something went wrong"
										});
									}
								} else {
									if (window.TesseraStates) {
										TesseraStates.toastOk('Ticket booked successfully', 'Opening ticket for print...');
									}
									window.open("print_multiple_ticket.php?print_id=" + data);
									srReloadPassengerInfoTable();

								}

							$('#item_table tbody').empty();
							if (window.srSyncPassengerEmptyState) {
								window.srSyncPassengerEmptyState();
							}

								$('.picker_select').selectpicker('refresh');

								$('#submit_button').attr('disabled', false);
								$('#submit_button').val("Continue");

							}


						},
						error: function () {
							$('#loader_button').text("Continue");
							$('#submit_button').attr('disabled', false);
							if (window.TesseraStates) {
								TesseraStates.toastErr('Booking failed', 'Could not reach the server. Try again.');
							}
						}
					})
				} else if (result.isDenied) {

				if (window.TesseraStates) {
					TesseraStates.toastWarn('Reserve unavailable', 'Use Lock seat for now to hold the seat.');
				} else {
					alert("reserve is currently unavailable please lock the seat");
				}

				}
			})



		}
		else {
			$('#error').html('<div class="alert alert-danger"><ul>' + error + '</ul></div>');
		}









	});


});
