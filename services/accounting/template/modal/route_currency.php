 <div id="modaldemo1" class="modal">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title">Add Route</h6>
            <button type="button" class="close order-1" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
<form id="signup" enctype="multipart/form-data">

          <div class="modal-body">
    <div class="row row-sm">
            <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Route Name</label>
              <br><select class="form-control select2 " id="choose_route" style="width: 100%;" name="route">
                <option value="Choose Route">Choose Route</option><?php echo $route_full_name;?></select>
			  </div><!-- col --> 
			   <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Route Currency</label>
              <br><select class="form-control select2" id="route_currency" style="width: 100%;" name="route_currency">
                <option value="Choose Currency">Choose Currency</option>
             <?php echo $name_account;?>
            
			
              </select>
			  </div><!-- col -->
        <div class="col-lg chmroute" style="">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Choose Status</label>
              <br><select class="form-control select2" id="Main_route" style="width: 100%;" name="status">
                <option value="Choose Status">Choose Status</option>
                <option value="Main">Main Route</option>
                <option value="Sub route">Sub route</option>
             
            
			
              </select>
			  </div>
			
			
			<!-- col -->
          <!-- col -->
          </div>   


		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->