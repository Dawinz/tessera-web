<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasRightLabel">Update Trip</h5>
                                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                   <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               <div style="display:none;"id="id_no"></div>
											   <div style="display:none;"id="bus_prev"></div>
                                                
														     <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Select Route Name</label>
            <select class="form-select " id="route_name_update"
                                                aria-label="Floating label select example">
												<option id="rname"value=""></option>
                                                    <?php echo $route_name; ?>

                                            </select>                                                               
                                                            </div> 
															<div class="mb-3">
                                                                <label class="form-label">Trip days </label>
         <select class="form-select " id="day_of_week_update"
                                                aria-label="Floating label select example">
												<option id="tday"value="">Choose Trip Days</option>
                                                   <option>Monday</option>
												   <option>Tuesday</option>
												   <option>wednesday</option> 
												   <option>Thursday</option>
												   <option>Friday</option>
												   <option>Saturday</option>  
												   <option>Sunday</option>
                                            </select>                                                                     
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Select Bus Name</label>
  <select class="form-select " id="vehicle_name_update"
                                                aria-label="Floating label select example">
												<option id="v_name"value="">Choose Vehicle Name</option>
                                                    <?php echo $vehicle_update; ?>

                                            </select>                                                                 
                                                            </div>
														<div class="mb-3">
                                                                <label class="form-label">Depature Time</label>
                                                                 <input class="form-control"   type="text"  id="d_ptime_update"  >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Arraival Time</label>
                                                                 <input class="form-control" type="type"    id="a_time_update" value="" >
                                                               <div id="test"></div>
                                                            </div>
																<div class="mb-3">
                                                                <label class="form-label">Details</label>
                                                                 <input class="form-control" type="text" id="detail_update" placeholder="Details.." >
                                                               
                                                            </div>

														
															 <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="save_changes">Change</button>
                                                        </div>
                                                    </div>
                                               
                                                </form>          
 
												</div>
												
                                        </div>
                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
