  <div id="modaldemo2" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title particular_name_holder" ></h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		   <form id="insert_form" enctype="multipart/form-data">

          <div class="modal-body">
            <input type="hidden" name="particular" value ="" id="particular_n"/>
 	<div class="table-repsonsive">
							<span id="error"></span>
							<table class="table table-bordered" id="item_table2">
								<tr>
									<th>Category</th>
									<th>Affect</th>
									<th>Name</th>
									
									<th><button type="button" name="add_new" class="btn btn-success btn-sm add_new "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
						
						</div>
		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save_part">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->
