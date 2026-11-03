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
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600"> From</label>
              <br><select class="form-select  " id="choose_first" style="width: 100%;" name="first">
                <option value="Choose Primary Currency">Choose Primary Currency</option>
            
			<?php echo $name_account;?>
              </select>
			  </div><!-- col --> 
			   <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600"> To</label>
              <br><select class="form-select " id="route_second" style="width: 100%;" name="secondary">
                <option value="Choose Secondary Currency">Choose Secondary Currency</option>
             <?php echo $name_account;?>
            
			
              </select>
			  </div><!-- col -->
        <div class="col-lg chmroute" style="">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Value</label>
              <br>
			  <input type="text"class="form-control" id="value" name="value">
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