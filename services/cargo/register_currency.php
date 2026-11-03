<?php
require_once __DIR__ . '/includes/sr_env.php';
$connect = sr_mysqli('cargo');
$start_route2 = '';
$query = "SELECT name FROM country";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
?>  


<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Register currency'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
<!-- fullcalendar css -->
        <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

        <!-- One of the following themes -->
        <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/classic.min.css"/> <!-- 'classic' theme -->
        <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/monolith.min.css"/> <!-- 'monolith' theme -->
        <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/nano.min.css"/> <!-- 'nano' theme -->

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
		  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'><link rel="stylesheet" href="./style.css">
 <link rel="stylesheet" type="text/css" href="css/datatable.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">

    </head>

    <style>
	input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
	</style>
	
	    <style>

button.dt-button, div.dt-button, a.dt-button, input.dt-button {
    border-radius: 2px;
    color: black;
    
}

</style>
	
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php include 'top_header.php';?>
	
    
            <!-- ========== Left Sidebar Start ========== -->

            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Register currency</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of currencies</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						            <div class="parent">
  
</div>
						 <?php
	 $username=$_SESSION['username'];

	 ?>
              
   <div class="row">
                            
                                <div class="card sr-table-panel">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">List of Currencies</h4>
										                                    <button type="button" class="btn btn-soft-primary" data-bs-toggle="modal"data-bs-target="#myModal"><i class="uil uil-plus "></i>Regsiter</button>

                                    </div><!-- end card header -->
									
                                    <div class="card-body">
									
                                           <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 
<th>Currency Name</th>
<th>Country</th>
<th>Initial name</th>
<th>Status</th>
<th>rate</th>
<th>Change</th>

<th>Deleted </th>

</tr>
				
                          </thead>
                                          <!-- end tbody --> 
                                            </table>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                        
                            <!-- end col -->
                        </div>
			  <!-- end page title -->

     
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Tessera.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="#" class="text-reset">Tessera</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->


		                           <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Register Route</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                      <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               
                                                
														     <div class="col-12">
															    <div class="btn-group me-2 mb-2 mb-sm-0">
 
                                            </div>   
                           
                                                            <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Currency Name</label>
                <input class="form-control" type="text"  id="currecy_name" placeholder="Currency Name" >
                                                               
                                                            </div> 
															<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Country</label>
                                                          <select class="form-select" id="Country"
                                                aria-label="Floating label select example">
												<option value="">Choose Country</option>
                                                    <?php echo $start_route2; ?>
                                                 
                                            </select>
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Initial</label>
					 <input class="form-control" type="text"  id="initial" placeholder="Tsh" >

                                                               
                                                            </div>				
															<div class="mb-3">
                                                                <label class="form-label">Currency Status</label>
	               <select class="form-select" id="c_status"
                                                aria-label="Floating label select example">
												<option value="">Choose Currency Status</option>
<option>Normal </option>                                                   
												   <option>Main </option>
													
                                                 
                                            </select>

                                                               
                                                            </div>
													
													
															<div class="mb-3">
                                                                <label class="form-label">Exchange Rate </label>
                                                                 <input class="form-control" type="text" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="1" data-type="currency"  id="fair" placeholder="Exchange Rate 1 for Main" >
                                                               
                                                            </div>	
                                                        </div>
                                                    </div>
                                               
                                                </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="search">Register</button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
        <!-- /Right-bar -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasRightLabel">Update Currency</h5>
                                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                                       <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               
                                                
														     <div class="col-12">
															    <div class="btn-group me-2 mb-2 mb-sm-0">
 
                                            </div>   
                           <div id ="id_no" style="display:none;"></div>
                                                            <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Currency Name</label>
                <input class="form-control" type="text"  id="currecy_name_update" placeholder="Currency Name" >
                                                               
                                                            </div> 
															<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Country</label>
                                                          <select class="form-select" id="Country_update"
                                                aria-label="Floating label select example">
												<option id="update_country"></option>
                                                    <?php echo $start_route2; ?>
                                                 
                                            </select>
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Initial</label>
					 <input class="form-control" type="text"  id="initial_update" placeholder="Tsh" >

                                                               
                                                            </div>				
															<div class="mb-3">
                                                                <label class="form-label">Currency Status</label>
	               <select class="form-select" id="c_status_update"
                                                aria-label="Floating label select example">
												<option id="update_status"></option>
													<option>Normal</option>
                                                    <option>Main</option>

                                            </select>

                                                               
                                                            </div>
													
													
															<div class="mb-3">
                                                                <label class="form-label">Exchange Rate </label>
                                                                 <input class="form-control" type="text" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"  id="fair_update" placeholder="Exchange Rate" >
                                                               
                                                            </div>	
                                                        </div>
                                                    </div>
<button type="button" class="btn btn-primary" id="save_update">Change</button>

                                                </form>
												</div>
												
                                        </div>
   <!-- end row -->

     <!-- end row -->

              <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


             
            </div>

        <!-- JAVASCRIPT -->
		<script src="js/vendor/jquery-1.12.4.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
        <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- plugin js -->
        <script src="assets/libs/fullcalendar/main.min.js"></script>
		<script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

        <!-- Calendar init -->
        <script src="assets/js/pages/calendar.init.js"></script>
        <script src="assets/js/app.js"></script>
		 <script src="assets/js/pages/form-advanced.init.js"></script>
		  <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		   <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		   		<script src="insert_currecy.js"></script>
				<script src="update_currecny_info.js"></script>
		<script src="js/sweetalert.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
	<script src="currency.js"></script>

	
</script>
    <script>
        $(document).ready(function(){
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 0, "desc" ]],
				
                'ajax': {
                'url':'fetch_currency.php',
                },'columns': [
                    
				
					
                      { data: 'name' },
					  { data: 'country' },
					  { data: 'initial' }, 
					  { data: 'status' },
					  { data: 'rate' },
					  { data: 'update' },			
					
					 { data: 'delete' },					  
			
					
					
              
                ],
				"lengthMenu": [[300, 350, 450, -1], [300, 325, 450, "All"]],
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
        ], rowCallback: function(row, data, index) {
      if (data.status == "Due payment") {
    	
$(row).css('background-color','#ffe8f0');

      } 
	  if (data.status == "To be Payed"){
	$(row).css('background-color','#ffffff');	  

	  } if (data.status == "paid"){
	  	$(row).css('background-color','#99ff9c');


	  }
    },
            });

        });
        </script>
      </script>
    </body>
</html>
