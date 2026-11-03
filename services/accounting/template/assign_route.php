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
      <div class="row" style="padding-right:80px;padding-left:20px;">
     
	
	 <!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
       

          <div class="az-content-label mg-b-5" style="color:grey;">Assign Route</div>
 
  <div id="modaldemo1" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title">Assign Route <span id="holder_name"></span></h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		                                                          <form id="signup" enctype="multipart/form-data">

          <div class="modal-body">
   <div class="load_route"></div>
		  </div>
       
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->
</form>
          <div>
            <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Username</th>
                      <th class="wd-20p">Firstname</th>
					  <th class="wd-20p">Surname</th>
                      <th class="wd-20p">Role</th>
					  					                         <th class="wd-20p">Email</th>

                       <th class="wd-20p">Number</th>

					  <th class="wd-20p">User Status</th>
					  <th class="wd-20p">Assign route</th>
					 
                     
                  
                  </tr>
              </thead>
              
            </table>
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
				 'columnDefs': [{ width: '10%', targets: 0 }],
				   "language": {
	     searchPlaceholder: "Search....",
        search: "",
     processing: ''
},
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch/fetch_users.php',
                },'columns': [
                    
				
			  { data: 'username' },
					  { data: 'firstname' },
					  { data: 'surname' }, 
					  { data: 'role' },			
                     { data: 'email' },
					 { data: 'number' },
					 { data: 'user_status' },
					 { data: 'route' },
					
				
					  
					
					
              
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
