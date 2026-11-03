$(document).ready(function () {
	$('#search').on('click', function () {
		var currecy_name = $.trim($('#currecy_name').val() || '');
		var Country = $('#Country option:selected').text();
		var initial = $.trim($('#initial').val() || '');
		var currency_status = $('#c_status option:selected').text();
		var fair = $.trim($('#fair').val() || '');

		if (currecy_name === '') {
			swal('', 'Please enter currency name.', 'warning');
			return;
		}
		if (Country === 'Choose Country') {
			swal('', 'Please select country.', 'warning');
			return;
		}
		if (initial === '') {
			swal('', 'Please enter initial.', 'warning');
			return;
		}
		if (currency_status === 'Choose Currency Status') {
			swal('', 'Please choose currency status.', 'warning');
			return;
		}
		if (fair === '') {
			swal('', 'Please enter rate.', 'warning');
			return;
		}

		$('#search').text('registering...').prop('disabled', true);
		$.ajax({
			url: 'save_currency.php',
			method: 'POST',
			data: {
				currecy_name: currecy_name,
				Country: Country,
				initial: initial,
				currency_status: currency_status,
				fair: fair
			},
			success: function (response) {
				$('#search').text('Register').prop('disabled', false);
				$('#example').DataTable().ajax.reload();
				if ($.trim(response) === 'succesfull') {
					swal({ text: 'Currency saved.', timer: 2000, showConfirmButton: false });
					$('#myModal').modal('hide');
				} else if ($.trim(response) === 'not found') {
					swal('Main currency', 'Main currency already exists - choose Normal status.', 'warning');
				} else {
					swal('', 'Could not save currency.', 'warning');
				}
			},
			error: function () {
				$('#search').text('Register').prop('disabled', false);
				swal('', 'Could not save currency.', 'error');
			}
		});
	});
});
