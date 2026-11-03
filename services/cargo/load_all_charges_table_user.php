    	   <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>

<?php
session_start();

$route = $_GET['route'];

?>						
 <div id="spinner"class="spinner-border text-primary m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
						<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search Code">
		 </form><br><br>
      </div>                          

						       <table class="table mb-0" id="empTable">
           <thead class="table-light">
                <tr>
                   
					<th>Sender Name</th>               
                    <th>Receiver's name</th>
                   <th>Code</th>  
				   <th>Date</th>
				  
  <th>Detail</th>
  <th>Quantity</th>
   <th>Amount</th> 
    <th>To paid</th> 
	 <th>paid</th> 
 <th>Store Status</th>  
 <th>Edit</th>
 <th>Print</th>  
 <th>Delete</th> 
 <th>id</th> 
                </tr>
                </thead>
	
                                          <!-- end tbody --> 
                                            </table>
									
											 <script>
        $(document).ready(function(){
			var route = "<?php echo $route;?>";
		var username  = "<?php echo $_SESSION['username'];?>";
			

			$(".spinner-border").hide();
		
			
		
	
			 $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 13, "desc" ]],
                'ajax': {
                'url':'fetch_my_store.php',
							type:"POST","data": {
        "route":route,"username":username
    }
                },'columns': [
         
				
					{ data: 's_name' },
                    { data: 'r_name' },
                    { data: 'code' },
					   { data: 'date' },
					  { data: 'detail' }, 
					  { data: 'quantity' },
					   { data: 'amount' },
					  { data: 'to_be' },
					
					  { data: 'paid' },
              { data: 'store' },
			    { data: 'edit' }, 
			    { data: 'print' }, 
			    { data: 'delete' }, 
			  { data: 'id' },
                ],
				"lengthMenu": [[50]],
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
			{ extend: 'print', footer: true,

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
                    columns: [ 0, 1, 2, 3,4,5,6,7,8,10 ]
                }, },
            { extend: 'pdfHtml5', footer: true }
        ], 	 columnDefs: [ 
{
       targets: 2,
      createdCell: function (td, cellData, rowData, row, col) {
          $(td).css('border-left', '1px solid red');  
		  $(td).css('border-right', '1px solid red');
		          $(td).css('background', 'white'); 
				  $(td).css('width', '3px');
      }
    }
	], 
	rowCallback: function(row, data, index) {
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