$(document).ready(function () {
	$('#example1').DataTable({
		stripeClasses: [],
		processing: true,
		serverSide: true,
		serverMethod: 'post',
		language: {
			searchPlaceholder: "Search…",
			search: "",
			processing: ''
		},
		ajax: {
			url: 'fetch/currency.php',
			dataSrc: function (json) {
				return (json && (json.data || json.aaData)) ? (json.data || json.aaData) : [];
			}
		},
		columns: [
			{ data: 'name', defaultContent: '' },
			{ data: 'symbol', defaultContent: '' },
			{ data: 'status', defaultContent: '' },
			{ data: 'detail', defaultContent: '' },
			{ data: 'edit', defaultContent: '', orderable: false },
			{ data: 'delete', defaultContent: '', orderable: false }
		],
		lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'copyHtml5', footer: true },
			{ extend: 'excelHtml5', footer: true },
			{ extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
			{ extend: 'pdfHtml5', footer: true }
		]
	});
});
