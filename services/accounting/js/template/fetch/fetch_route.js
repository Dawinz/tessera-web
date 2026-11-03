	$(".change_r").click(function(){
			my_id = $(this).attr('id');
			$( ".first_b" ).removeClass( "btn-indigo" );
			$( ".second_b" ).removeClass( "btn-indigo" );
			$( ".third_b" ).removeClass( "btn-indigo" );
			
			$( ".first_b" ).addClass( "btn-dark" );
			$( this).addClass( "btn-indigo" );
			$('#example1').DataTable().destroy();
	fetch_data(my_id);
		});      	 
my_id= "";
		 fetch_data(my_id);
		 function fetch_data(my_id)
 {
	 
	 

	var dataTable = $('#example1').DataTable({
				'stripeClasses':[],
                'processing': true,
				   "language": {
	     searchPlaceholder: "Search medicine name",
        search: "",
     processing: ''
},
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch/fetch_routes.php',
								"data": {
        "my_id":my_id
       
    }
                },
				


				'columns': [
                    
				
					                 

                      { data: 'start_point' },
					{ data: 'end_point' },
                      { data: 'name' },
                      { data: 'fullname' },
                      { data: 'currency_name' },
					{ data: 'badge' },

                      { data: 'detail' },
                      { data: 'currency' },
                      { data: 'active' },
                      { data: 'delete' },
					  
					
				
					  
					
					
              
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
			}