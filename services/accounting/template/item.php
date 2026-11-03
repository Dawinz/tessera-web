<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Tessera Accounts'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.error_class{
	border:1px solid red;
}

.select2-dropdown {  
  z-index: 10060 !important;/*1051;*/
}




</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

<!-- az-header -->
<?php
include 'includes/top_header.php';
?>
    <div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
      <div class="container">
     
	 <?php
	 include 'includes/left_nav_particular.php';
	 ?>
	 <!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
      

          <div class="az-content-label mg-b-5">Particulars</div>
 
<?php
include 'modal/particular.php';
?>  

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
									<th>Particular</th>
									
									<th><button type="button" name="add_new" class="btn btn-success btn-sm add_new "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
						
						</div>
		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save_part">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->
</form>
          <div><br>
		 
            <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Category</th>
                      <th class="wd-20p">Name</th>
                      <th class="wd-20p">Particulars</th>
                      <th class="wd-20p">Add</th>
                      <th class="wd-20p">Edit</th>
                      <th class="wd-20p">Delete</th>
	
                     
                  
                  </tr>
              </thead>
              
            </table>
          </div>

          <hr class="mg-y-30">

        



          <div class="ht-40"></div>

          <div class="az-footer mg-t-auto">
            <div class="container">
              <span>&copy; 2024 Azia Responsive Bootstrap 5 Dashboard Template</span>
            </div><!-- container -->
          </div><!-- az-footer -->
        </div><!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->
<script src="/accounts/js/template/insert/particular.js"></script>
    <script src="/accounts/js/template/insert/particular_detail.js"></script>
    <script src="/accounts/js/template/update/particular.js"></script>
    <script src="/accounts/js/template/add/particular.js"></script>

    <script src="/accounts/js/template/dependence/route_dependence.js"></script>
	    <script src="/accounts/js/template/fetch/fetch_particular.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
    <script>
        $(document).ready(function(){
			
			
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';

	
		html += '<td><select name="category[]" id="category2" class="form-select  category2" style="" data-live-search="true"><?php echo $name; ?></select></td>';
		html += '<td><input name="name[]" id="particular_amount2"  type="text" class="form-control" ></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#item_table2').append(add_input_field(0));	
			
			
	$(document).on('click', '.add_new', function(){

		count++;

		$('#item_table2').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	});
			$(document).on('click', '.remove', function(){

		$(this).closest('tr').remove();

	});	
			
          $('.select2').select2({
            
            searchInputPlaceholder: 'Search',
			dropdownParent: $("#modaldemo1")
          });
          });
		  
	
		  
	
    </script>
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
