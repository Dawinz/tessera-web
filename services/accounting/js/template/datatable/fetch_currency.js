    var dataTable = $('#example1').DataTable({
				'stripeClasses':[],
                'processing': true,
				 'columnDefs': [{ width: '3%', targets: 0 }],
				   "language": {
	     searchPlaceholder: "Search medicine name",
        search: "",
     processing: ''
},
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch/fetch_purchase_order.php',
                },'columns': [
                    
				
					                 

                      { data: 'name' },
					{ data: 'symbol' },
                      { data: 'status' },
                      { data: 'value_no' },
					                        { data: 'Evaluation' },

                      { data: 'detail' },
					  
					
				
					  
					
					
              
                ],
				"lengthMenu": [[20, 350, 450, -1], [300, 325, 450, "All"]],
				dom: 'Bfrtip',
				
        buttons: [
		
                { extend: 'copyHtml5'},
            { extend: 'excelHtml5'},
            { extend: 'csvHtml5' },
			{ extend: 'print'},
            { extend: 'pdfHtml5' }
        ], 
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true, exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7,8,10 ]
                }, },
            { extend: 'pdfHtml5', footer: true }
        ],
			
            });