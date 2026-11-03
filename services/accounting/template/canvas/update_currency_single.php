	<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="height:350px;">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">Update Currency</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
<form id="update_currency_update" enctype="multipart/form-data">
<input type="hidden" id="id_no"name="id_no">
          <div class="modal-body">
            
    <div class="row row-sm">
            <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Route Name</label>
              <br><select class="form-control form-select " id="choose_route_update" style="width: 100%;" name="route_update">
                <option id="choose_route_update_text"value="Choose Route">Choose Route</option>
            
			<?php echo $route_full_name;?>
              </select>
			  </div><!-- col --> 
			   <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Route Currency</label>
              <br><select class="form-select " id="route_currency_update" style="width: 100%;" name="route_currency_update">
                <option id="route_currency_update2"value="Choose Currency">Choose Currency</option>
             <?php echo $name_account;?>
            
			
              </select>
			  </div><!-- col -->
        <div class="col-lg chmroute" style="">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Choose Status</label>
              <br><select class="form-select" id="Main_route_update" style="width: 100%;" name="status_update">
                <option value="Choose Status"class="Main_route_update2">Choose Status</option>
                <option value="Main">Main Route</option>
                <option value="Sub route">Sub route</option>
             
            
			
              </select>
			  </div>
			<input type="hidden" id="id_update" name="id_update">
			
			<!-- col -->
          <!-- col -->
          </div> 
		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save_update"><span id="text_change">Save changes</span></button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>  </div>
</div>