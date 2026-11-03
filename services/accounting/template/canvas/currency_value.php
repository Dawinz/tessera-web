	<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="height:350px;">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">Update Currency</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
<form id="update_currency_update2" enctype="multipart/form-data">
<input type="hidden" id="id_no"name="id_no">
          <div class="modal-body">
            
       <div class="row row-sm">
            <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600"> From</label>
              <br><select class="form-select  " id="choose_first_update" style="width: 100%;" name="first_update">
                <option value="Choose Primary Currency" id="choose_first_update_text">Choose Primary Currency</option>
            
			<?php echo $name_account;?>
              </select>
			  </div><!-- col --> 
			   <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600"> To</label>
              <br><select class="form-select " id="secondary_update" style="width: 100%;" name="secondary_update">
                <option value="Choose Secondary secondary_update_text">Choose Secondary Currency</option>
             <?php echo $name_account;?>
            
			
              </select>
			  </div><!-- col -->
        <div class="col-lg chmroute" style="">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Value</label>
              <br>
			  <input type="text"class="form-control" id="value_update" name="value_update">
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