function destroyManifestTable() {
	var $table = $('#example1');
	if ($.fn.DataTable && $.fn.DataTable.isDataTable($table)) {
		$table.DataTable().destroy();
	}
	// Strip leftover chrome so re-init doesn't stack info/paginate/processing
	$table.siblings('.dt-buttons, .dataTables_info, .dataTables_paginate, .dataTables_length, .dataTables_filter, .dataTables_processing, .dt-processing, .dt-container').remove();
	var $wrap = $table.closest('.dataTables_wrapper, .dt-container');
	if ($wrap.length && !$wrap.is($table.parent())) {
		$table.insertBefore($wrap);
		$wrap.remove();
	}
	$('.dataTables_processing, .dt-processing').remove();
	$('#manifestToolbar .dt-buttons').remove();
}

	$(".change_r").click(function(){
			my_id = $(this).attr('id');
			my_name = $(this).attr('data');
            start_date =  $("#start_date").val();
            end_date =  $("#end_date").val();
			vehicle = $("#vehicle_name").val();
			$(".cname_route").text(my_name || 'All Routes');
				$( ".first_b" ).removeClass( "btn-indigo" );
				$( ".first_b" ).removeClass( "btn-dark" );
				$( ".first_b" ).addClass( "btn-dark" );
			$( this).removeClass( "btn-dark" );
			$( this).addClass( "btn-indigo" );
			destroyManifestTable();
	fetch_data(my_id,start_date,end_date,vehicle);
		});      
 $("#start_date").change(function(){
my_id= $("#route_query").val();
start_date =$("#start_date").val();
end_date =$("#end_date").val();
vehicle=$("#vehicle_name").val();
destroyManifestTable();
fetch_data(my_id,start_date,end_date,vehicle);
        })	; 
 $("#end_date").change(function(){
my_id= $("#route_query").val();
start_date =$("#start_date").val();
end_date =$("#end_date").val();
vehicle=$("#vehicle_name").val();
destroyManifestTable();
fetch_data(my_id,start_date,end_date,vehicle);
        })	; 
 $("#vehicle_name").change(function(){
vehicle = $(this).val();
my_id= $("#route_query").val();
start_date =$("#start_date").val();
end_date =$("#end_date").val();
destroyManifestTable();
fetch_data(my_id,start_date,end_date,vehicle);
        }); 

my_id= $("#route_query").val();
start_date=$("#start_date").val();
end_date=$("#end_date").val();
vehicle=$("#vehicle_name").val();

		 fetch_data(my_id,start_date,end_date,vehicle);
		 function fetch_data(my_id,start_date,end_date,vehicle)
 {
	var accountsManifestLang = (window.TesseraStates && TesseraStates.listLanguage)
		? Object.assign(
			TesseraStates.listLanguage('No manifests yet', 'No manifests match your filters'),
			{ searchPlaceholder: "Search..", search: "", processing: '' }
		)
		: { searchPlaceholder: "Search..", search: "", processing: '' };
	var dataTable = $('#example1').DataTable({
				'stripeClasses':[],
                'processing': true,
			 'columnDefs': [{ targets: [7, 8, 9, 10], orderable: false, className: 'text-center' }],
				   "language": accountsManifestLang,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                'url':'fetch/fetch_manifest.php',
				dataSrc: function (json) {
					return (json && (json.data || json.aaData)) ? (json.data || json.aaData) : [];
				},
								"data": {
        "my_id":my_id,
        "start_date":start_date,
        "end_date":end_date,
        "vehicle":vehicle
    },
				error: (window.TesseraStates && TesseraStates.loadError)
					? TesseraStates.loadError('Could not load manifests')
					: undefined
                },
				'columns': [
                      { data: 'code', defaultContent: '' },
                      { data: 'route_name', defaultContent: '' },
                      { data: 'bus', defaultContent: '', render: function (data, type, row) {
							return data || row.buss || '';
						} },
					{ data: 'trip_date', defaultContent: '' },
                      { data: 'income', defaultContent: '' },
                      { data: 'expenses', defaultContent: '' },
                      { data: 'balance', defaultContent: '' },
                      { data: 'insert', defaultContent: '', orderable: false },
                      { data: 'view', defaultContent: '', orderable: false },
                      { data: 'update', defaultContent: '', orderable: false },
                      { data: 'delete', defaultContent: '', orderable: false },
                      { data: 'lock', defaultContent: '', orderable: false },
                ],
				 lengthMenu: [25, 50, 100, 150],
				dom: 'Brtip',
	"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            $(nRow).removeClass('sr-row-complete sr-row-half');
            if ( aData.analysed == "Complete" )
            {
                $(nRow).addClass('sr-row-complete');
            }else if ( aData.analysed == "Half Checked" ){
                $(nRow).addClass('sr-row-half');
			}
             if ( aData.balance_view < 1 )
            {
     nRow.querySelector(':nth-child(6)').classList.add('highlight');
            }else{
 nRow.querySelector(':nth-child(6)').classList.add('highlight_normal');
            }
        },
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true, exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7,8,10 ]
                }, },
            { extend: 'pdfHtml5', footer: true }
        ],
		initComplete: function () {
			var api = this.api();
			var $bar = $('#manifestToolbar');
			if ($bar.length) {
				$bar.find('.dt-buttons').remove();
				var $search = $bar.find('.sr-filter-search');
				if ($search.length) {
					$(api.buttons().container()).insertBefore($search);
				} else {
					$(api.buttons().container()).appendTo($bar);
				}
			}
			$('#manifest_search').off('input.manifestSearch keyup.manifestSearch')
				.on('input.manifestSearch keyup.manifestSearch', function () {
					api.search(this.value).draw();
				});
		}
            });
			}

// Delegated row actions — DataTables does not execute <script> tags inside cell HTML.
$(document).off('click.manifestUpdate', '.js-manifest-update').on('click.manifestUpdate', '.js-manifest-update', function () {
	var $btn = $(this);
	var route = $btn.data('route') || '';
	var tripDate = $btn.data('trip-date') || '';
	var bus = $btn.data('bus') || '';
	var id = $btn.data('id') || '';
	$('#id_holder').val(id);
	var $route = $('#choose_route_update');
	if ($route.find('option[value="' + route.replace(/"/g, '\\"') + '"]').length) {
		$route.val(route);
	} else {
		$route.prepend($('<option>', { value: route, text: route, selected: true }));
		$route.val(route);
	}
	$('#route_name_holder').text(route).val(route);
	var $trip = $('#trip_date_update');
	$trip.html($('<option>', { value: tripDate, text: tripDate, selected: true, id: 'trip_date_update_holder' }));
	var $veh = $('#vehicle_update');
	$veh.html($('<option>', { value: bus, text: bus, selected: true, id: 'vehicle_update_holder' }));
});

$(document).off('click.manifestLock', '.js-manifest-lock').on('click.manifestLock', '.js-manifest-lock', function () {
	$('#bus_manifest').text($(this).data('bus') || '');
	$('#trip_date_manifest').text($(this).data('trip-date') || '');
});

$(document).off('click.manifestDelete', '.js-manifest-delete').on('click.manifestDelete', '.js-manifest-delete', function () {
	var $btn = $(this);
	var payload = {
		id: $btn.data('id'),
		bus: $btn.data('bus'),
		trip_date: $btn.data('trip-date'),
		route_name: $btn.data('route')
	};
	var confirmDelete = function () {
		$btn.prop('disabled', true);
		$.ajax({
			url: 'delete/manifest.php',
			method: 'POST',
			data: payload,
			success: function (response) {
				var new_response = $.trim(response || '');
				$btn.prop('disabled', false);
				if (new_response === 'analysed') {
					if (window.TesseraStates) {
						TesseraStates.toastErr('Cannot delete', 'This manifest is already analysed.');
					} else if (window.Swal) {
						Swal.fire({ icon: 'error', title: 'Cannot delete', text: 'Manifest is analysed' });
					} else {
						alert('Manifest is analysed');
					}
					return;
				}
				if (window.TesseraStates) {
					TesseraStates.toastOk('Manifest deleted', 'Vehicle and entries were removed.');
				} else if (window.Swal) {
					Swal.fire({ icon: 'success', title: 'Deleted', text: 'Vehicle and entries were removed.' });
				}
				if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example1')) {
					$('#example1').DataTable().ajax.reload(null, false);
				}
			},
			error: function () {
				$btn.prop('disabled', false);
				if (window.TesseraStates) {
					TesseraStates.toastErr('Delete failed', 'Could not delete this manifest.');
				} else {
					alert('Could not delete this manifest.');
				}
			}
		});
	};

	if (window.Swal) {
		Swal.fire({
			title: 'Are you sure?',
			text: 'It will delete all vehicle entries!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then(function (result) {
			if (result.isConfirmed) {
				confirmDelete();
			}
		});
	} else if (window.confirm('Delete this manifest and all vehicle entries?')) {
		confirmDelete();
	}
});

