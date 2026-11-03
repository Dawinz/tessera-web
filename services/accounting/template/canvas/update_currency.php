	<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="height:350px;">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">Update Currency</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
<form id="update_currency" enctype="multipart/form-data">
<input type="hidden" id="id_no"name="id_no">
          <div class="modal-body">
            
 <div class="row row-sm">
            <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Currency Name</label>
              <input class="form-control" placeholder="Name" type="text" name="name_update" id="name_update">
            </div><!-- col -->
            <div class="col-lg mg-t-10 mg-lg-t-0"style="display:none;">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Value </label>

              <input class="form-control" placeholder="Value" type="text" name="value_update" data-type="currency" id="value_update">
            </div><!-- col -->
            <div class="col-lg mg-t-10 mg-lg-t-0">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Symbol</label>

              <input class="form-control" placeholder="Symbol" type="text" name ="symbol_update" id="symbol_update">
            </div> <!-- col -->
          </div>         

 <div class="form-group mb-2">
                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Details</label>
                  <textarea class="form-control" placeholder="Enter any details" name="detail_update"id="detail_update"></textarea>
                </div>
		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save"><span id="text_change">Save changes</span></button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>  </div>
</div>