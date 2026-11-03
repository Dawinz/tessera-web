	                           <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog"
                                            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Register Route</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                      <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               
                                                
														     <div class="col-12">
															    <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">click to show</label><br>
                                    <button type="button" id="show" class="btn btn-soft-success"><i class="uil uil-plus "></i>Add route location</button>

                                            </div>
                                            </div>   
                                           <span id="route_add_area" style="display:none;">
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">Location Name</label><br>
   <input class="form-control" type="text" value="" id="l_name">
                                            </div>
                                            </div>   
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input"></label><br>
                                    <button type="button" id="save_location" class="btn btn-soft-success">Save</button>

                                            </div>
                                            </div>   
											</span>
											<form id="rgform">
											
												<table class="table table-bordered" id="item_table">
								<tr>
									<th>Starting point</th>
									<th>Ending point</th>
									<th>Currecncy</th>
									<th>Fair</th>
									<th>initial name</th>	
									
									
									
									
									<th>Active Days</th>
																	
									<th>Detail</th>
																		<th>Value</th>

									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
											
											
											
											
											
											
                                                            <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Starting point</label>
                                                            <select class="form-select" id="starting_point"
                                                aria-label="Floating label select example">
												<option value="">Choose Starting Point</option>
                                                    <?php echo $start_route2; ?>

                                            </select>
                                                               
                                                            </div> 
															<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Ending point</label>
                                                          <select class="form-select" id="end_point"
                                                aria-label="Floating label select example">
												<option value="">Choose Ending Point</option>
                                                    <?php echo $start_route2; ?>
                                                 
                                            </select>
                                                            </div>		
															<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Currecncy</label>
                                                          <select class="form-select" id="currency"
                                                aria-label="Floating label select example">
												<option value="">Choose Currency</option>
                                                    <?php echo $curerncy; ?>
                                                 
                                            </select>
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Fair</label>
                                                                 <input class="form-control" type="text" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"  id="fair" placeholder="" >
                                                               
                                                            </div>
													
															<div class="mb-3">
                                                                <label class="form-label">initial name</label>
                                                                 <input class="form-control" type="text"  id="initial" placeholder="" >
                                                               
                                                            </div>		
															
                                                            <div class="mb-3">
                                                                <label class="form-label">First Contact number </label>
                                                                 <input class="form-control" type="text"  id="contact_no1" placeholder="" >
                                                               
                                                            </div>	
                                                                <div class="mb-3">
                                                                <label class="form-label">Second Contact number </label>
                                                                 <input class="form-control" type="text"  id="contact_no2" placeholder="" >
                                                               
                                                            </div>	
															<div class="mb-3">
                                                                <label class="form-label">Active Days</label>
                                                                 <input class="form-control" type="number"  id="active_day" placeholder="" >
                                                               
                                                            </div>	
															<div class="mb-3">
                                                                <label class="form-label">Detail</label>
                                                                 <input class="form-control" type="text"  id="details" placeholder="" >
                                                               
                                                            </div>	
                                                        </div>
														</form>
                                                    </div>
                                               
                                                </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="search">Register</button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>