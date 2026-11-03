$(document).ready(function () {
	$('#register_user').on('click', function () {
		var fullname = $.trim($('#fullname').val() || '');
		var lastname = $.trim($('#lastname').val() || '');
		var username = $.trim($('#username').val() || '');
		var password_no = $('#password').val() || '';
		var number = $.trim($('#phone_no').val() || '');
		var email = $.trim($('#email').val() || '');
		var role = $('#role option:selected').text();

		if (fullname === '') {
			swal('', 'Please enter first name.', 'warning');
			return;
		}
		if (lastname === '') {
			swal('', 'Please enter last name.', 'warning');
			return;
		}
		if (username === '') {
			swal('', 'Please enter username.', 'warning');
			return;
		}
		if (password_no === '') {
			swal('', 'Please enter password.', 'warning');
			return;
		}
		if (number === '') {
			swal('', 'Please enter phone number.', 'warning');
			return;
		}
		if (email === '') {
			swal('', 'Please enter email.', 'warning');
			return;
		}
		if (role === 'Role') {
			swal('', 'Please choose role.', 'warning');
			return;
		}

		$('#register_user').text('Registering...').prop('disabled', true);
		$.ajax({
			url: 'insert_user.php',
			method: 'POST',
			data: {
				fullname: fullname,
				firstname: fullname,
				lastname: lastname,
				username: username,
				password_no: password_no,
				number: number,
				email: email,
				role: role
			},
			success: function (response) {
				$('#register_user').text('Register').prop('disabled', false);
				$('#rgform, #form-event').trigger('reset');
				if ($.trim(response) === 'succesfull') {
					swal({ text: 'User registered.', timer: 2000, showConfirmButton: false });
					$('#exampleModalScrollable').modal('hide');
					$('#example').DataTable().ajax.reload();
				} else if ($.trim(response) === 'found') {
					swal('Found', 'Username already exists.', 'warning');
				} else {
					swal('', 'Could not register user.', 'warning');
				}
			},
			error: function () {
				$('#register_user').text('Register').prop('disabled', false);
				swal('', 'Could not register user.', 'error');
			}
		});
	});
});
