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
 <td><select name="particular_update" id="particular_update" class="form-select  particular_update" style="" data-live-search="true"><option value="Choose group" id="category_update_text"></option><?php echo $name;?></select></td> 

 <td><input name="name_update" id="name_update"  type="text" class="form-control" ></td>
  </tr>
  </table>
  <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="update">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
  </div>
</div>