   <?php
$date_vehicle= @$_GET['date'];
$name_vehicle= @$_GET['date3'];
include 'connect.php';


?>
						
														<?php
  include_once 'config2.php';

?>
    
                     <div id="date1_exp"style="display:none;"><?php
$date1= @$_GET['date'];
echo $date1;
?></div>
					 <div id ="date2_exp"style="display:none;"><?php
$date2= @$_GET['date2'];
echo $date2;
?></div>					
 <div id ="bus"style="display:none;"><?php
$date3= @$_GET['date3'];
echo $date3;
?></div>

<?php
$amount_array=array();
$to_be_array=array();
$paid_array=array();





$select222=mysqli_query($db,"SELECT amount,paid,to_be  FROM  manifest  where trip_date >= '$date1' and trip_date <= '$date2' and bus='$date3'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $amount =$get122['amount']; 
			$paid =$get122['paid']; 
			$to_be =$get122['to_be']; 
array_push($amount_array, $amount);
array_push($to_be_array, $to_be);
array_push($paid_array, $paid);
		
	}
}



?>

   <div class="row">
                 <style>
				 body{
					 autocomplete="false" ;
				 }
				 </style>           
                                <div class="card">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Luggages</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                           <table class="table mb-0" id="empTable">
           <thead class="table-light">
               <tr>
                   
					
					<th>Name of Owner</th>
					<th>code</th>
                  
                    <th>AMMOUNT</th>
					<th>To be paid</th>
					<th>Paid</th>
				
					  <th>Action</th>  <th>Action</th> 
                </tr>
                </thead>
				      	  <tfoot>
              <tr>
                <th></th>
            									
				
              
				<th>To Be </th>
				<th>		
<?php

$to_be_total = array_sum($to_be_array);
echo number_format($to_be_total,2);
?>
				</th>
                          </tr> 
						  
						  
						  <tr>
                <th></th>
                
				
              
				<th>Paid</th>
				<th>	
				<?php
				
				$paid_total = array_sum($paid_array);
				echo number_format($paid_total,2);
				
				?>
				</th>
                          </tr>
					
						  
						  <tr>
                <th></th>
                
				
              
				<th>Total</th>
				<th>
				<?php
				$total_amount = array_sum($amount_array);
				echo number_format($total_amount,2);
				?>
				</th>
                          </tr>
        </tfoot> 
				
				
                                          <!-- end tbody --> 
                                            </table>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                        
                            <!-- end col -->
                        </div>
						 <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
<script type="text/javascript" charset="utf8" src="js/datatable.js"></script>
						 <script>
        $(document).ready(function(){
			var date1_exp = $('#date1_exp').text();
			var date2_exp = $('#date2_exp').text();
			var date3exp = $('#bus').text();
			
            $('#empTable').DataTable({
								

                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 6, "desc" ]],
                'ajax': {
                'url':'fetch_area.php',
									 type:"POST","data": {
        "date1_exp":date1_exp,"date2_exp":date2_exp,"date3exp":date3exp,
    }
                },'columns': [
                   
				
					
					{ data: 'username' },
					{ data: 'code' },
                    
					{ data: 'amount' },
					{ data: 'to_be' },	
					{ data: 'paid' },
					
					 { data: 'delete' }, 
					 { data: 'id' }, 
                 
                ],
"lengthMenu": [[5, 15, 35, -1], [5, 15, 35, "All"]],
				dom: 'Bfrtip',
        buttons: [
		
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
       preDrawCallback: function() {
                let el = $('div.dataTables_filter label');
                if(!el.parent('form').length) {
                    el.wrapAll('<form></form>').parent()
                    .attr('autocomplete', false)
                    .attr('autofill', false);
                }
            }    
  });
        });

        </script>