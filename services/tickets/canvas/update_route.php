<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Update Route</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  					    <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">click to show</label><br>
                                    <button type="button" id="show_update" class="btn btn-soft-success"><i class="uil uil-plus "></i>Add route location</button>

                                            </div>
                                            </div>   
                                           <span id="route_add_area_update" style="display:none;">
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">Location Name</label><br>
   <input class="form-control" type="text" value="" id="l_name_update">
                                            </div>
                                            </div>   
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input"></label><br>
                                    <button type="button" id="save_location_update" class="btn btn-soft-success">Save</button>

                                            </div>
                                            </div>   
											</span>
               <form class="needs-validation" name="event-form" id="form-event" novalidate>

	<div id="id_no"style="display:none;"></div>
                                                            <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Starting point</label>
                                                            <select class="form-select" id="starting_point_update"
                                                aria-label="Floating label select example">
												<option id="s_update">Choose Starting Point</option>
                                                    <?php echo $start_route2; ?>

                                            </select>
                                                               
                                                            </div> 
															
																<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Ending point</label>
                                                          <select class="form-select" id="end_point_update"
                                                aria-label="Floating label select example">
												<option id="e_update">Choose Ending Point</option>
                                                    <?php echo $start_route2; ?>
                                                 
                                            </select>
                                                            </div>
																	<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Currecncy</label>
                                                          <select class="form-select" id="currency_update"
                                                aria-label="Floating label select example">
												<option id="update_currecny"></option>
                                                    <?php echo $curerncy; ?>
                                                 
                                            </select>
                                                            </div>
															
																<div class="mb-3">
                                                                <label class="form-label">Active Days</label>
                                                                 <input class="form-control" type="number"  id="active_day_update" placeholder="Enter active day"value="" >
                                                               
                                                            </div>	
															
															
															<div class="mb-3">
                                                                <label class="form-label">Fair</label>
                                                                 <input class="form-control" type="text" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"  id="fair_update" placeholder="Enter fair" >
                                                               
                                                            </div>
													
															<div class="mb-3">
                                                                <label class="form-label">initial name</label>
                                                                 <input class="form-control" type="text"  id="initial_update" placeholder="Dar to lub" >
                                                               
                                                            </div>	<div class="mb-3">
                                                                <label class="form-label">Position name</label>
                                                                 <input class="form-control" type="text"  id="position_update" placeholder="" >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Detail</label>
                                                                 <input class="form-control" type="text"  id="details_update" placeholder="" >
                                                               
                                                            </div>	
																		<button type="button" class="btn btn-primary" id="save_update">Change</button>

  
  </form>
  </div>
</div>