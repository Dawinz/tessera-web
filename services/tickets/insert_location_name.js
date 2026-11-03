$(document).ready(function () {
	$('#search').on('click', function () {
		var l_name = $.trim($('#l_name').val() || '');
		if (l_name === '') {
			swal('', 'Please enter location name.', 'warning');
			return;
		}

		$('#search').text('Saving...').prop('disabled', true);
		$.ajax({
			url: 'save_location.php',
			method: 'POST',
			data: { l_name: l_name },
			success: function (response) {
				$('#search').text('Register').prop('disabled', false);
				if ($.trim(response) === 'succesfull') {
					swal({ text: 'Location saved.', timer: 1500, showConfirmButton: false });
					$('#l_name').val('');
					$('#myModal').modal('hide');
					if ($.fn.DataTable.isDataTable('#example')) {
						$('#example').DataTable().ajax.reload(null, false);
					}
				} else {
					swal('Found', 'Location already exists.', 'warning');
				}
			},
			error: function () {
				$('#search').text('Register').prop('disabled', false);
				swal('', 'Could not save location.', 'error');
			}
		});
	});
});
