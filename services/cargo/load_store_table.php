<?php
$route = $_GET['route'];
?>						
 <div id="spinner"class="spinner-border text-primary m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
						<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search Code">
		 </form>
      </div>                          

						  <table class="table mb-0" id="empTable">
           <thead class="table-light">
          <tr>
                    
                  
                    <th>Receiver's name</th>
                     <th>Date</th>
<th>Detail</th> <th>Ammount</th><th>paid</th><th>To Be Payed</th><th>Loan</th> <th>Store Status</th> 
<th>Code</th> 
<th>q'left</th>
<th>action</th> <th>check</th> <th>id</th>
                </tr>
                </thead><div>
				               	  <tfoot>
                   <tr style="font-weight:bold;">
                <th></th>
                
				
              
			
		<th>Total </th><th>
						<div id="load_total_amount">
						<div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:25px;"></span>
                                                    </div>
						</div>
								</th><th>To Be Payed</th><th>
								
								<div id="load_to_be_paid">
										<div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:25px;"></span>
                                                    </div>
								</div>
										
								</th><th>Paid</th><th>
					<div id="load_paid_store">
							<div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:25px;"></span>
                                                    </div>
					</div>
								</th>
                          </tr> 
        </tfoot></div>
                                          <!-- end tbody --> 
                                            </table>
									
											 <script>
        $(document).ready(function(){
			var route = "<?php echo $route;?>";
		
			

			$(".spinner-border").hide();
		
			
		
	
			 
            $('#empTable').DataTable({
				  'autoFill': false,
                'processing': true,
                'serverSide': true,
				
                'serverMethod': 'post',
				 "order": [[ 12, "asc" ]],
                'ajax': {
                'url':'fetch_see_all.php',
			type:"POST","data": {
        "route":route
    }
				
                },'columns': [
                    
				
					
                    { data: 'r_name' },
					 { data: 'date' },
					  { data: 'detail' }, 
					  { data: 'amount' },			
					  { data: 'paid' },			
					  { data: 'to_be' },
					  { data: 'loan' },
					  { data: 'store' },
					  { data: 'code' },
					  { data: 'left' }, 
					  { data: 'quantity' },
					  { data: 'check' }, 
					  { data: 'id' }, 
					
					
              
                ],
				"lengthMenu": [300],
				dom: 'lfprtip',
        buttons: [
		
             { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print',footer: true, className: 'red'},
            { extend: 'pdfHtml5',footer: true, className: 'red' }
        ], 
				dom: 'Bfrtip',
        buttons: [
               { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print', className: 'red',footer: true,

   autoPrint: true,
                title: ' <img style="margin:10px;" src ="http://www.redetrade.com/sr.png" width="80" height="80" /><p style="font-size:25px;margin:10px;"> Store inventory</p><div style="font-size:14px;margin:20px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Date : <?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
echo $date_insert;
	?></div>',

                //For repeating heading.
                repeatingHead: {
                    logo: 'images/sr.jpg',
                    logoPosition: 'right',
                    logoStyle: '',
                    title: '<h3>Sample Heading</h3>'
                },
			exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7,9,10 ]
                }, },
            { extend: 'pdfHtml5', footer: true , className: 'red'}
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
				   "fnDrawCallback": function (oSettings) {
        $(".dataTables_filter").each(function () {
            $(this).appendTo($(this).parent().siblings(".panel-body"));
        });
    }, preDrawCallback: function() {
                let el = $('div.dataTables_filter label');
                if(!el.parent('form').length) {
                    el.wrapAll('<form></form>').parent()
                    .attr('autocomplete', false)
                    .attr('autofill', false);
                }
            }   
            });
		 oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
});  

			$("#load_quantity").load("load_quantity.php?route="+route);
			$("#load_to_be_paid").load("load_to_be_paid.php?route="+route);
			$("#load_paid_store").load("load_paid_store.php?route="+route);
			$("#load_total_amount").load("load_total_amount.php?route="+route);
        });
        </script>