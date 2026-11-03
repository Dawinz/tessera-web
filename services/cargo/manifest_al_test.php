					  <table class="table mb-0" id="empTable">
           <thead class="table-light">
        <tr>
                   
					
					<th>Name of Owner</th>
					<th>Description of Goods</th>
                  
                    <th>AMMOUNT</th>
					<th>To be paid</th>
					<th>Paid</th>
					<th>Vehicle</th>
					<th>Date</th>
					<th>Cargo rec.no</th>
					  <th>Exported Quantity</th>
					  <th>Checkout</th> 
					  <th>Print</th>  <th>Id no</th> 
                </tr>
                </thead>   </tr> 
        </tfoot>
                                          <!-- end tbody --> 
                                            </table>					
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	  <link rel="stylesheet" type="text/css" href="datatable/datatable.css">


	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
									<script>
        $(document).ready(function(){
	
		
			

	
		
	
		
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 11, "desc" ]],
                'ajax': {
                'url':'fetch_clear_manifest_test.php',
	          },'columns': [
                   
                    
								

					{ data: 'username' },
					{ data: 'detail' },
                    
					{ data: 'amount' },
					{ data: 'to_be' },	
					{ data: 'paid' },
					{ data: 'bus' },
					{ data: 'date' },
					{ data: 'code' },
                     { data: 'left' }, 
					 { data: 'action' }, 
					 { data: 'print' }, 
                  { data: 'id' }, 
                ],
"lengthMenu": [[20, 20, 20, -1], [20, 20, 20, "All"]],
				dom: 'Bfrtip',
        buttons: [
		
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true, exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7 ]
                }, },
            { extend: 'pdfHtml5', footer: true }
        ], rowCallback: function(row, data, index) {
      if (data.status == "Due payment") {
    	
$(row).css('background-color','#ffe8f0');

      } 
	  if (data.status == "To be Payed"){
	$(row).css('background-color','#ffffff');	  

	  } if (data.status == "paid"){
	  	$(row).css('background-color','#99ff9c');


	  }if (data.checked == "out"){
$(row).css('text-decoration','line-through'); 
$(row).css('text-decoration-thickness','2px');
 $(row).css('text-decoration-color','red'); 



	  }
    },
        columnDefs: [ {
         "targets": [ 5,6 ], 
      createdCell: function (td, cellData, rowData, row, col) {
   
		  
      
		        $(td).css('border-bottom', '2px solid #c4c4c4');
		
      }
    },

	],       
  });

			
        });
        </script>