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
				 'columnDefs': [{ width: '3%', targets: 0 }],
				   "language": {
	     searchPlaceholder: "Search...",
        search: "",
     processing: ''
},
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch/fetch_particular.php',
								"data": {
        "my_id":my_id
       
    }
                },
				


				'columns': [
                    
				
					                 

                      { data: 'category' },
                      { data: 'cat_group' },
                      { data: 'name' },
					{ data: 'par_details' },
					{ data: 'particular' },
					{ data: 'edit' },
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