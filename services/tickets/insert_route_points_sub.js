$(document).ready(function () {
	$('#search').on('click', function () {
		var route_name = $('#route_name option:selected').text();
		var point_start = $('#point_start option:selected').text();
		var place_name = $.trim($('#place_name').val() || '');
		var a_time = $.trim($('#a_time').val() || '');

		if (route_name === 'Choose Route Name') {
			swal('', 'Please choose route name.', 'warning');
			return;
		}
		if (point_start === 'Choose Point') {
			swal('', 'Please choose point.', 'warning');
			return;
		}
		if (place_name === '') {
			swal('', 'Please enter place name.', 'warning');
			return;
		}
		if (a_time === '') {
			swal('', 'Please enter arrival time.', 'warning');
			return;
		}

		$('#search').text('registering...').prop('disabled', true);
		$.ajax({
			url: 'insert_route_points_sub.php',
			method: 'POST',
			data: {
				route_name: route_name,
				point_start: point_start,
				place_name: place_name,
				a_time: a_time
			},
			success: function (response) {
				$('#search').text('Register').prop('disabled', false);
				$('#form-event').trigger('reset');
				$('#place_name, #a_time').val('');
				$('#example').DataTable().ajax.reload();
				if ($.trim(response) === 'succesfull') {
					swal({ text: 'Sub-route point saved.', timer: 2000, showConfirmButton: false });
					$('#myModal').modal('hide');
				} else {
					swal('Found', 'Sub-route point already exists.', 'warning');
				}
			},
			error: function () {
				$('#search').prop('disabled', false).text('Register');
				swal('', 'Could not save sub-route point.', 'error');
			}
		});
	});
});
