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
    <div class=" ">
      <div style="padding-right:10px;">
     

	 
	 <!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
      
<br>
          <div class="az-content-label mg-b-5">Particulars Category</div>
		  	<button style="width:200px;"class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i class="bx bx-plus"></i> Add Category</button>

 
<?php
include 'modal/particular.php';
include 'modal/simple_particular_detail.php';
include 'canvas/particular.php';
?>  



          <div><br>
		 
            <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Category</th>
                      <th class="wd-20p">Name</th>
                      <th class="wd-20p">Type</th>
                      <th class="wd-20p">Particulars</th>
                      <th class="wd-20p">Add Particulars</th>
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
<script src="/accounts/js/template/add/particular.js"></script>
 <script src="/accounts/js/template/insert/particular.js"></script>
    <script src="/accounts/js/template/insert/particular_detail.js"></script>
    <script src="/accounts/js/template/update/particular.js"></script>
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

	
		html += '<td><select name="category[]" id="category2" class="form-select  category2" style="" data-live-search="true"><option class="category2_hoder">Choose Category</option><?php echo $name; ?></select></td>';
		html += '<td><select name="affect[]" id="affect" class="form-select  affect" style="" data-live-search="true"><option class="affect_holder">Select Affect</option><option value="Paid">Cash</option><option value="To be Paid">To be Paid</option></select></td>';
		html += '<td><input name="name[]" id="particular_name2"  type="text" class="form-control part_name" ></td>';

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
