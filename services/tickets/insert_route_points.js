$(document).ready(function () {
	$('#search').on('click', function () {
		var route_name = $('#route_name').val();
		var point_start = $('#point_start option:selected').text();
		var place_name = $.trim($('#place_name').val() || '');

		if (!route_name) {
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

		$('#search').text('registering...').prop('disabled', true);
		$.ajax({
			url: 'insert_route_points.php',
			method: 'POST',
			data: {
				route_name: route_name,
				point_start: point_start,
				place_name: place_name
			},
			success: function (response) {
				$('#form-event').trigger('reset');
				$('#place_name').val('');
				$('#search').prop('disabled', false).text('Register');
				$('#example').DataTable().ajax.reload();
				if ($.trim(response) === 'succesfull') {
					swal({ text: 'Route point saved.', timer: 2000, showConfirmButton: false });
					$('#myModal').modal('hide');
				} else {
					swal('Found', 'Route point already exists.', 'warning');
				}
			},
			error: function () {
				$('#search').prop('disabled', false).text('Register');
				swal('', 'Could not save route point.', 'error');
			}
		});
	});
});
