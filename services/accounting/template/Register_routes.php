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


.nav button{
	margin:5px;
}

</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

<!-- az-header -->
<?php
include 'includes/top_header.php';
?>
<div class="row" style="padding-top:10px;">
<div class="col-md-2 az-content-left-mail">
    <div class="component-item" style="margin:30px;">
            <nav class="nav flex-column">
              
	<button class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i class="bx bx-plus"></i> Add Route</button>

            </nav>

            <label>Select Route Option</label>
            <nav class="nav flex-column">
<button class="btn btn-indigo  btn-block change_r first_b" id="">All Routes</button>
<button class="btn btn-dark  btn-block change_r second_b" id="status='Main' and">Main Route</button>
<button class="btn btn-dark  btn-block change_r third_b" id="status='Sub route' and">Subroute</button>
            </nav>


          </div>
</div>
<div class="col-md-9">

        <div class="az-content-label mg-b-5">All Routes</div>
 
  <div id="modaldemo1" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title">Add Route</h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		                                                          <form id="signup" enctype="multipart/form-data">

          <div class="modal-body">
            
 <div class="row row-sm">
            <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Route Name</label>
              <br><select class="form-control select2 " id="choose_route" style="width: 100%;" name="route">
                <option value="Choose Route">Choose Route</option>
            
			<?php echo $route_full_name;?>
              </select>
			  </div><!-- col --> 
			   <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Route Category</label>
              <br><select class="form-control select2" id="route_category" style="width: 100%;" name="category">
                <option value="Choose Route Category">Choose Route Category</option>
                <option value="Main Route">Main Route</option>
                <option value="Sub Route">Sub Route</option>
            
			
              </select>
			  </div><!-- col -->
        <div class="col-lg chmroute" style="display:none;">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Choose Main Route</label>
              <br><select class="form-control select2" id="Main_route" style="width: 100%;" name="Main_route">
                <option value="Main Route">Choose Main Route</option>
             
            
			
              </select>
			  </div>
			
			
			<!-- col -->
          <!-- col -->
          </div>         

 <div class="form-group mb-2">
                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Details</label>
                  <textarea class="form-control" placeholder="Enter any details" name="detail"></textarea>
                </div>
		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
        </div>
      </div><!-- modal-dialog -->
    </div>  <div id="modaldemo2" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title route_name_holder" ></h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		   <form id="insert_form" enctype="multipart/form-data">

          <div class="modal-body">
            <input type="hidden" name="currency_r" value ="" id="currency_r"/>
            <input type="hidden" name="r_status" value ="" id="r_status"/>
 	<div class="table-repsonsive">
							<span id="error"></span>
							<table class="table table-bordered" id="item_table">
								<tr>
									<th>Currency</th>
									
									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
						
						</div>
		  </div>
          <div class="modal-footer">
            <button type="button"class="btn btn-indigo" id="add_currency">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->
	
	<?php include 'includes/route_edit_canvas.php';?>
	
</form>
          <div><br>
		 
            <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Starting Point</th>
					  <th class="wd-20p">Ending Point</th>
                      <th class="wd-20p">Initial Name</th>
                      <th class="wd-20p">Fullname</th>
                      <th class="wd-20p">Currency</th>
                       <th class="wd-20p">Route</th>

					  <th class="wd-20p">Detail</th>
					  <th class="wd-20p">currency</th>
					  <th class="wd-20p">Edit</th>
					  <th class="wd-20p">Delete</th>
                     
                  
                  </tr>
              </thead>
              
            </table>
          </div>

          <hr class="mg-y-30">

        
          <div>
  
          </div>



          <div class="ht-40"></div>

          <div class="az-footer mg-t-auto">
            <div class="container">
              <span>&copy; 2024 Azia Responsive Bootstrap 5 Dashboard Template</span>
            </div><!-- container -->
          </div>
</div>

</div>


<!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->
<script src="/accounts/js/template/insert/route.js"></script>
    <script src="/accounts/js/template/insert/route_currency.js"></script>
    <script src="/accounts/js/template/dependence/route_dependence.js"></script>
	    <script src="/accounts/js/template/fetch/fetch_route.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
    <script>
        $(document).ready(function(){
			
			
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';

	
		html += '<td><select name="route_name[]" class="form-select  " style="" data-live-search="true"><option value="">Select Unit</option><?php echo $name_account; ?></select></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#item_table').append(add_input_field(0));	
			
			
	$(document).on('click', '.add', function(){

		count++;

		$('#item_table').append(add_input_field(count));

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
