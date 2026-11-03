<?php
$route = $_GET['route'];
$route = preg_replace('/_/',' ', $route);

?>					

<?php
  include_once 'config2.php';

?>
    

			
				
 <div id="spinner"class="spinner-border text-primary m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
						<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search Code">
		 </form>
      </div>        <br>    <br>                  

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
        </tfoot></div>
                                          <!-- end tbody --> 
                                            </table>
									
											 <script>
        $(document).ready(function(){
	
		
			

			$(".spinner-border").hide();
		
			
			var route = "<?php echo $route;?>";
		
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 11, "desc" ]],
                'ajax': {
                'url':'fetch_clear_manifest.php',
									 type:"POST","data": {
        "route":route,
    }
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
				"lengthMenu": [10],
		
				dom: 'Bfrtip',
 rowCallback: function(row, data, index) {
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