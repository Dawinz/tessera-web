
   
   <table id="example"  class="table table-hover table-nowrap mb-0 align-middle table-check">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="rounded-start" style="width: 15px;">
                                                  Route 
                                                    </th>
                                                    
													<th>Bus</th>
													<th>Dept</th>
                                                    <th>Arrival</th>
                                                    <th>Cost</th>
                                                    <th>Available</th>
                                              <th >Book</th> 
											  <th>lock</th> <th ></th><th></td>
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
                                            <!-- end thead -->
                      <tbody > 
					
						       <?php
 $starting_location = $_GET['starting_location'];
$ending_location = $_GET['ending_location'];
$start_location = preg_replace('/_/',' ', $starting_location);
$ending_location = preg_replace('/_/',' ', $ending_location);
$date= $_GET['starting_date'];


				  $trip_date_tomorrow = date('l', strtotime($date));
?>
		
                                           
												  		<?php
														include 'connect.php';
	$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow'   and status='active' ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
			$route_id =$get['route_id'];
$id2 =$get['id'];
			$arrival =$get['arrival'];
			$departure =$get['departure'];
			$time =$get['12hours'];
			$trip_day =$get['day']; 
			$bus =$get['bus']; 
			$now_time = date('h:i A', time());

		
			
$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$fare_price =$get134['fare_price'];
			$currency_symbol =$get134['currency_symbol'];
			$detail =$get134['detail']; 
			$route_name =$get134['route_name']; 

		

}}
			
			
						$select1730=mysqli_query($db,"SELECT *  FROM  vehicle Where name='$bus' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $seats_bus= $get13730['seats'];



}
}
			
			$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$bus' and trip_date='$date'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
	   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $seats= $get1373['seats'];


}
}else{
$seats= $seats_bus;	
}
		

		
		


		?>
     <tr>                                                   
												   <td><?php?>
                                                   <span ><?php echo "".base64_decode($detail)." (".$route_name.")";?></span>
                                                    </td>   <td>
                                                   <?php echo $bus;?>
                                                    </td>
                                                    <td class="fw-medium">
                                                        <?php echo $departure;?>
                                                    </td>
                                                    <td>
                                                  <?php
												  echo $arrival;
												  ?>
                                                    </td>

                                                    <td>
                                                 <?php echo ''.number_format($fare_price).' '.$currency_symbol.''?>
                                                    </td>
                                                    <td>
                                                   <?php echo $seats;?>
                                                    </td>
                                                    <td>
 <button type="button" class="btn btn-outline-primary"id="book<?php echo $id2;?>">Book </button>                                                      </td>

                                                    <td>
                                                 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch<?php echo $id2;?>" switch="bool" class="open_booking_switch"  />
                                                            <label for="square-switch<?php echo $id2;?>" data-on-label="Open "
                                                                data-off-label=" Lock" class="mb-0"></label>
                                                        </div>
                                                    </td>            <td>
                                                      <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Manifest</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Seat Chart Manifest</a></li><li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Edit Bus</a></li><li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Charge Summary</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $id2;?></td>
													</tr>
													
													<script>
													$(document).ready(function(){
														$("#book<?php echo $id2;?>").click(function(){
														$(".route_selection_spiner").show();
															id = "<?php echo $id2;?>";
															trip_date = "<?php echo $date;?>";
															route_id = "<?php echo $route_id;?>";
															bus = "<?php echo $bus;?>";
															fare_price = "<?php echo $fare_price;?>";
															start_location = "<?php echo str_replace(" " , "_", $start_route);?>";
															ending_location = "<?php echo  str_replace(" " , "_", $end_route);?>";
															
                                                         $(".alert_class").show();
                                                         $(".route_loaded_date").text("<?php echo date("l jS \of F Y", strtotime($date));?>");
                                                         $(".load_route_bus").text("<?php echo $bus;?>");
													
														 $.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{start_location:start_location,route_id:route_id},
				success:function(response){
		$('#trip_start').html(response);
				
	}
			
			});		

		
			$.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{ending_location:ending_location,route_id:route_id},
				success:function(response){
				
		$('#trip_end').html(response);
				
	}
			
			});
$("#load_seat_chart").load("load_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&start_location="+start_location+"&ending_location="+ending_location+"&bus="+bus+"&fare_price="+fare_price);

														
														});
																$("#square-switch<?php echo $id2;?>").click(function(){
																	id = "<?php echo $id2;?>";
															trip_date = "<?php echo $date;?>";
															route_id = "<?php echo $route_id;?>";
															bus = "<?php echo $bus;?>";
															fare_price = "<?php echo $fare_price;?>";
															start_location = "<?php echo str_replace(" " , "_", $start_route);?>";
															ending_location = "<?php echo  str_replace(" " , "_", $end_route);?>";
															
														$(".bs-example-modal-xl").modal("show");
														$("#load_booking_seat_chart").load("load_booking_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&start_location="+start_location+"&ending_location="+ending_location+"&bus="+bus+"&fare_price="+fare_price);

														});	
												
													});
													
													</script>
													
<?php }} ?>
					
						 </tbody>
                                        </table>
										     <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog"
                                            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myExtraLargeModalLabel">Lock seat </h5>
                                                        <button type="button" class="btn-close closebtnmodal" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
													<div class="row">
													<div class="col">
													<div id="load_booking_seat_chart"class="rotated"></div>
													</div>
													<div class="col">
													 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch1" switch="bool" class="copy_switch" checked />
                                                            <label for="square-switch1" data-on-label="copy"
                                                                data-off-label="Off" class="mb-0"></label>
                                                        </div><br>
														<form method="post" id="insert_form_lock">
						<div class="table-repsonsive">
							<span id="error2"></span>
							<table class="table table-bordered" id="load_lock_table">
								<tr>
									<th>Reson</th>
									
									<th class="display_none">route_day</th>
									<th class="display_none">Route id</th>
									<th class="display_none">Trip date</th>
									<th class="display_none">Seat no</th>
								</tr>
							</table>
							<div align="center">
								<input type="submit" name="submit" id="submit_button" class="btn btn-primary" value="Lock seat" />
							</div>
						</div>
					</form>
													
													</div>
													</div>
													
                                                    
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										<script>
$(document).ready(function() {


	var table =	 $('#example').DataTable({
			 dom: 'Bfrtip',
			 "lengthMenu": [ 3 ],
			 order: [[8, 'desc']],
			  columnDefs: [
        {
            target: 9,
            visible: false,
            searchable: false
        }
    ]
	});
  

var length = table.page.info().recordsTotal;
				 oTable = $('#example').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 

if(length==3){
	 $("#myInputTextField").show();
}


$('.bs-example-modal-xl').modal({
    backdrop: 'static',
    keyboard: false  // to prevent closing with Esc button (if you want this too)
});

$(".closebtnmodal").click(function(){
$(".open_booking_switch").prop('checked', false);
$('#insert_form_lock').find('tr:gt(0)').remove();

});
});

</script>
  