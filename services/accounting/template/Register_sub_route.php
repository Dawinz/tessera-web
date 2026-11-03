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
	 include 'includes/left_nav.php';
	 ?>
	 <!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Components</span>
            <span>Tables</span>
            <span>DataTable</span>
          </div>

          <div class="az-content-label mg-b-5">Basic DataTable</div>
  	<div class="col-sm-6 col-md-3"style="margin-top:5px;">
	<button class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i class="bx bx-plus"></i> Add Currency</button>
</div>
  <div id="modaldemo1" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title">Add Currency</h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		                                                          <form id="signup" enctype="multipart/form-data">

          <div class="modal-body">
            
 <div class="row row-sm">
            <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Main Route</label>
              <br><select class="form-control select2 " id="choose_main_route" style="width: 100%;" name="route">
                <option value="Opera">Choose Main Route</option>
            
			<?php echo $route_full_name;?>
              </select>
			  </div> <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Sub Route</label>
              <br><select class="form-control select2 " id="choose_route" style="width: 100%;" name="choose_sub_route">
                <option value="Opera">Choose Sub Route</option>
            
			<?php echo $route_full_name;?>
              </select>
			  </div><!-- col --> 
			  <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Currency Name</label>
              <br><select class="form-control select2" id="currecncy_route" style="width: 100%;" name="currency">
                <option value="">Choose Currency</option>
            
			<?php echo $name_account;?>
              </select>
			  </div><!-- col -->
        
			
			
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
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->
</form>
          <div>
            <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Main Route</th>
                      <th class="wd-20p">Starting Point</th>
					  <th class="wd-20p">Ending Point</th>
                      <th class="wd-20p">Initial Name</th>
                      <th class="wd-20p">Fullname</th>
                       <th class="wd-20p">Currency</th>

					  <th class="wd-20p">Detail</th>
                     
                  
                  </tr>
              </thead>
              
            </table>
          </div>

          <hr class="mg-y-30">

          <div class="az-content-label mg-b-5">Responsive DataTable</div>
          <p class="mg-b-20">Responsive is an extension for DataTables that resolves that problem by optimising the table's layout for different screen sizes through the dynamic insertion and removal of columns from the table.</p>

          <div>
  
          </div>



          <div class="ht-40"></div>

          <div class="az-footer mg-t-auto">
            <div class="container">
              <span>&copy; 2024 Azia Responsive Bootstrap 5 Dashboard Template</span>
            </div><!-- container -->
          </div><!-- az-footer -->
        </div><!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->
    <script src="/accounts/js/template/insert/sub_route.js"></script>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
    <script>
        $(document).ready(function(){
          $('.select2').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search',
			dropdownParent: $("#modaldemo1")
          });
          });
	    var dataTable = $('#example1').DataTable({
				'stripeClasses':[],
                'processing': true,
				 'columnDefs': [{ width: '3%', targets: 0 }],
				   "language": {
	     searchPlaceholder: "Search medicine name",
        search: "",
     processing: ''
},
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch/fetch_sub_routes.php',
                },'columns': [
                    
				
					                 

                      { data: 'main_route' },
                      { data: 'start_point' },
					{ data: 'end_point' },
                      { data: 'name' },
                      { data: 'fullname' },
					{ data: 'currency' },

                      { data: 'detail' },
					  
					
				
					  
					
					
              
                ],
				"lengthMenu": [[20, 350, 450, -1], [300, 325, 450, "All"]],
				dom: 'Bfrtip',
				
        buttons: [
		
                { extend: 'copyHtml5'},
            { extend: 'excelHtml5'},
            { extend: 'csvHtml5' },
			{ extend: 'print'},
            { extend: 'pdfHtml5' }
        ], 
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true, exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7,8,10 ]
                }, },
            { extend: 'pdfHtml5', footer: true }
        ],
			
            });
    </script>
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
