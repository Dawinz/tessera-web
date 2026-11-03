
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
											  <th>lock</th> <th ></th><th></th>
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
                                            <!-- end thead -->
                      <tbody > 
					
			<?php

include 'include_first_route.php';


?>
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
									<th>Starting location</th>
									<th>Ending location</th>
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


		 $('#example').DataTable({
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
  
	
});

</script>
