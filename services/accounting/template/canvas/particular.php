<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">UPDATE PARTICULAR CATEGORY</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  <form id="update_form" enctype="multipart/form-data">
<input type="hidden" id="id_no" name ="id_no">
  <table class="table table-bordered" id="item_table_update">
  <tr>
<td><select name="category2" id="category2" class="form-select  category2" style="" data-live-search="true"><option value="Choose Account" id="choose_account">Choose Account</option><option value="Income">Income</option><option value="Expenses">Expenses</option></select></td> 
 <td><select name="group_cat" id="group_cat" class="form-select  group_cat" style="" data-live-search="true"><option value="Choose group" id="choose_group">Choose Account</option><option>Neutral</option><option>Vehicle</option><option>Garage</option><option>Office</option></select></td> 

 <td><input name="name2" id="particular_amount2"  type="text" class="form-control" ></td>
  </tr>
  </table>
  <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="update">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
  </div>
</div>