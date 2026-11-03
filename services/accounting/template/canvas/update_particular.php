<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">UPDATE PARTICULAR CATEGORY</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  <form id="update_form_particular" enctype="multipart/form-data">
<input type="hidden" id="id_no_update" name ="id_no_update">
  <table class="table table-bordered" id="item_table_update">
  <tr>
<td><select name="category_update" id="category_update" class="form-select  category2" style="" data-live-search="true">
<option id="category_update_value"></option>
<?php echo $name; ?></select></td> 
 <td><input name="particular_update" id="particular_update"  type="text" class="form-control" ></td>
 
<td><select name="account" id="account" class="form-select  account" style="" data-live-search="true"><option value="Choose Account" id="account_text"></option><option value="Income">Income</option><option value="Expenses">Expenses</option></select></td>
 </tr>
  </table>
  <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="update">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
  </div>
</div>