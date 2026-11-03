$(document).ready(function () {
	$('#insert_route').on('submit', function (e) {
		e.preventDefault();

		var $form = $(this);
		var error = '';
		var count = 1;

		$form.find("select[name='starting_point[]']").each(function () {
			if ($(this).val() == 'Choose Starting Point') {
				error += '<li>Please Starting Point on row ' + count + ' </li>';
			}
			count = count + 1;
		});

		count = 1;
		$form.find("select[name='end_point[]']").each(function () {
			if ($(this).val() == 'Choose End Point' || $(this).val() == 'End Point') {
				error += '<li>Please End Point on row ' + count + ' </li>';
			}
			count = count + 1;
		});

		count = 1;
		$form.find("select[name='route_type[]']").each(function () {
			if ($(this).val() == '' || $(this).val() == 'Choose Type') {
				error += '<li>Please choose route type on row ' + count + ' </li>';
			}
			count = count + 1;
		});

		count = 1;
		$form.find("select[name='Currnecy[]']").each(function () {
			if ($(this).val() == 'Choose Currnecy') {
				error += '<li>Please Currency on row ' + count + ' </li>';
			}
			count = count + 1;
		});

		count = 1;
		$form.find('.price').each(function () {
			if ($(this).val() == '') {
				error += '<li>Please Fill Price on row' + count + ' </li>';
			}
			count = count + 1;
		});

		count = 1;
		$form.find('.Initial').each(function () {
			if ($(this).val() == '') {
				error += '<li>Please Fill Initial on row' + count + ' </li>';
			}
			count = count + 1;
		});

		if (error == '') {
			$.ajax({
				type: 'POST',
				url: 'insert/register_route.php',
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function () {
					$('#search').attr('disabled', true);
					$('#search').text('Adding route..');
				},
				success: function (response) {
					$('#example').DataTable().ajax.reload();
					$('#search').attr('disabled', false);
					$('#search').text('Register');
					response = $.trim(response);
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: 'Your work has been saved',
						showConfirmButton: false,
						timer: 1500,
					});
					$('#insert_route').trigger('reset');
				},
			});
		} else {
			Swal.fire({
				title: 'Missing Info',
				html: "<span style='color:white;'>" + error + '</span>',
				icon: 'warning',
			});
		}
	});
});
