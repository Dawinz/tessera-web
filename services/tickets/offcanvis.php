 <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
                                    
                                            <div class="offcanvas-body">
											
											      <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title"> Advance Search </h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">            
                                    <div class="row">
                                            <div class="col">
                                                <label class="" for="specificSizeInputName">Start location</label>
                                               <select class="form-control td_with" id="starting_location" >
<option >Choose Start Point</option>
    <?php echo $start_route2; ?>
 
</select>
                                            </div>    
											<div class="col">
                                                <label class="" for="specificSizeInputName">End Location</label>
 <select class="form-control td_with" id="ending_location" >
  <option >Choose Ending Point</option>
    <?php echo $start_route2; ?>
</select>
                                            </div>	<div class="col">
                                                <label class="" for="specificSizeInputName">Vehicle</label>
<select class="form-control td_with" id="bus" >
  <option >Vehicle</option>
    <?php echo $bus; ?>
</select>
                                            </div>	<div class="col">
                                                <label class="" for="specificSizeInputName">Username</label>
<select class="form-control td_with" id="username" >
  <option >Username</option>
    <?php echo $username_users; ?>
</select>
                                            </div>
											
											<div class="col">
                                                <label class="" for="specificSizeInputName">Start Date</label>
 <input class="form-control dateselect" type="text"  id="starting_date" style="caret-color: transparent;margin-bottom:4px;cursor: pointer;"placeholder="mm/dd/yyyy" value ="" onkeydown="return false">

                                            </div>	
											<div class="col">
                                                <label class="" for="specificSizeInputName">End Date</label>
 <input class="form-control dateselect" type="text"  id="end_date" style="caret-color: transparent;margin-bottom:4px;cursor: pointer;"placeholder="mm/dd/yyyy" value ="" onkeydown="return false">

                                            </div>	
											<div class="col">
											                                                <label class="" for="specificSizeInputName">Zero Fare Tickets</label><br><br>

											
                                                                                                    <label class="form-check-label" for="horizontal-customCheck">Foc</label>

   <input type="checkbox" class="form-check-input" id="foc">
                                                                          <label class="form-check-label" for="horizontal-customCheck">Transit</label>
                    
<input type="checkbox" class="form-check-input" id="transit">
                                                         
										  </div>	
									
                                    
                                           </div>
                                         
                                       <!-- end form -->
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div>
											
                                  
                                            </div>
                                        </div>