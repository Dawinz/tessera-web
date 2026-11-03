	$(".change_r").click(function(){
			my_id = $(this).attr('id');
			my_name = $(this).attr('data');
			$(".cname_route").text(my_name);
			this_check = $(this).hasClass("btn-indigo");
			
				$( ".first_b" ).removeClass( "btn-indigo" );
				$( ".first_b" ).removeClass( "btn-dark" );
				$( ".first_b" ).addClass( "btn-dark" );
			
			
			//$( ".first_b" ).removeClass( "btn-indigo" );
			//$( ".second_b" ).removeClass( "btn-dark" );
			
			//$( ".first_b" ).addClass( "btn-dark" );
			//$( ".second_b" ).addClass( "btn-dark" );
			
			//$( ".first_b" ).addClass( "btn-dark" );
			$( this).removeClass( "btn-dark" );
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
	     searchPlaceholder: "Search any thing",
        search: "",
     processing: ''
},
                'serverSide': true,
                'serverMethod': 'post',
				"columns": "columns",
				"ordering": "false",
                'ajax': {
                'url':'fetch/fetch_jobcard.php',
								"data": {
        "my_id":my_id
       
    }
                },
				


				'columns': [
                    
				
					                 

                      { data: 'date' },
					{ data: 'name'},
					{ data: 'amount'},
					{ data: 'insert'},
					{ data: 'edit'},
					{ data: 'delete'},

                      
                 
					  
					
				
					  
					
					
              
                ],
				"lengthMenu": [[20, 350, 450, -1], [300, 325, 450, "All"]],
				dom: 'Bfrtip',
	"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            $(nRow).removeClass('sr-row-complete sr-row-half');
            if ( aData.analysed == "Complete" )
            {
                $(nRow).addClass('sr-row-complete');
            }else if ( aData.analysed == "Half Checked" ){
                $(nRow).addClass('sr-row-half');
			}
        },
				
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
        ]
	
            });
			}