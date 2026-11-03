<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
	 $route = @$_GET['route'];
$route = preg_replace('/_/',' ', $route);

	 ?>
	 
	 <div class="table-responsive">
                                            <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 

<th>Trip date</th>
<th>Bus</th>
<th>Driver</th>
<th>Conductor</th>
<th>Start Location</th>
<th>End Location</th>
<th>Start time</th>
<th>Ticket </th>
<th>Booked Seats</th>
<th>Sales Summary </th>
<th>View</th>
<th>Change</th>
<th>PSG <BR>Manifest</th>

<th>Manifest<br>A4/POS</th>

<th>Delete</th>
<th>Ref no</th>
</tr>
				
                          </thead>
                                          <!-- end tbody --> 
                                            </table><!-- end table --> 
                                        </div>
    <script>
        $(document).ready(function(){
				var route = "<?php echo $route;?>";
				
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
 "order":  [ 15, "desc" ],
				language: (window.TesseraStates && TesseraStates.listLanguage)
					? TesseraStates.listLanguage('No manifests yet', 'No manifests match your filters')
					: undefined,
                'ajax': {
                'url':'fetch_manifest.php',
	type:"POST","data": {
        "route":route
    },
				error: (window.TesseraStates && TesseraStates.loadError)
					? TesseraStates.loadError('Could not load manifests')
					: undefined
                },'columns': [
                    
				
					
                
					  { data: 'trip_date' },
					  { data: 'bus' },
					  { data: 'driver' },
					  { data: 'conductor' },
					  { data: 'start_location' }, 
					  { data: 'end_location' },			
					  { data: 'start_time' },
                     { data: 'amount' },
									  
					  { data: 'seats' },
					   { data: 'summarry' },	
					  { data: 'view' },
					  { data: 'update' },
					  { data: 'p_print' },
					  
					  { data: 'print' }, 
				  
					  { data: 'delete' },
					
					        { data: 'sort' }, 
					
					
              
                ],
				"lengthMenu": [[50, 350, 450, -1], [50, 325, 450, "All"]],
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
        ], rowCallback: function(row, data, index) {
      if (data.status == "Due payment") {
    	
$(row).css('background-color','#ffe8f0');

      } 
	  if (data.status == "To be Payed"){
	$(row).css('background-color','#ffffff');	  

	  } if (data.status == "paid"){
	  	$(row).css('background-color','#99ff9c');


	  }
    },
            });

        });
        </script>