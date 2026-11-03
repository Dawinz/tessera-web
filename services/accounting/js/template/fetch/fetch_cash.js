$(".change_r").click(function(){
	my_id = $(this).attr('id') || "";
	my_name = $(this).attr('data');
	$(".cname_route").text(my_name || 'All Routes');
	$(".first_b").removeClass("btn-indigo").addClass("btn-dark");
	$(this).removeClass("btn-dark").addClass("btn-indigo");
	if ($.fn.DataTable.isDataTable('#example1')) {
		$('#example1').DataTable().destroy();
	}
	fetch_data(my_id);
});

my_id = "";
fetch_data(my_id);

function fetch_data(my_id) {
	var cashLang = (window.TesseraStates && TesseraStates.listLanguage)
		? Object.assign(
			TesseraStates.listLanguage('No cash entries yet', 'No cash entries match your search'),
			{ searchPlaceholder: "Search…", search: "", processing: '' }
		)
		: { searchPlaceholder: "Search…", search: "", processing: '' };
	$('#example1').DataTable({
		stripeClasses: [],
		processing: true,
		serverSide: true,
		serverMethod: 'post',
		ordering: true,
		order: [[1, 'desc']],
		language: cashLang,
		ajax: {
			url: 'fetch/fetch_cash.php',
			dataSrc: function (json) {
				return (json && (json.data || json.aaData)) ? (json.data || json.aaData) : [];
			},
			data: { my_id: my_id },
			error: (window.TesseraStates && TesseraStates.loadError)
				? TesseraStates.loadError('Could not load cash entries')
				: undefined
		},
		columns: [
			{ data: 'authorizer', defaultContent: '' },
			{ data: 'open_date', defaultContent: '' },
			{ data: 'opening_stock', defaultContent: '' },
			{ data: 'id', defaultContent: '' },
			{ data: 'amount_remain', defaultContent: '' },
			{ data: 'date', defaultContent: '' }
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
}
