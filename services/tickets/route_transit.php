<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Route transits'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

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
/* paginate styled by sr-platform-shell Book-list system */
		  .dt-button.red {
   color: #fff;

   background-color: #0379BB;
   border-radius: 2px;
   border:none;
    }
  .dataTables_filter input {
        width: 250px;
        height: 32px;
        background: #fcfcfc;
        border: 1px solid #aaa;
        border-radius: 5px;
    
        text-indent: 10px;
    }
 
    .dataTables_filter .fa-search {
        position: absolute;
        top: 10px;
        left: auto;
        right: 10px;
    }
.dataTables_filter {
    display: none;
}
#myInputTextField
{
	width:30%;
	 position: absolute;
right:40px;
  z-index: 4;
	margin-bottom:70px;
}
</style>
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

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
                 

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Register Route</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Routs</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						            <div class="parent">
  
</div>
						 <?php
	 $username=$_SESSION['username'];
include 'modal/regiser_route.php';
include 'modal/route_contact.php';
include 'modal/regiser_transit.php';
	 ?>
              
   <div class="row">
                            
                                <div class="card">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Route lists</h4>
                                    <button type="button" class="btn btn-soft-primary" data-bs-toggle="modal"data-bs-target="#myModal"><i class="uil uil-plus "></i>Regsiter</button>
                                    </div><!-- end card header -->
                                    <div class="card-body">
																				<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search....">
		 </form>
      </div> 
                                           <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 
<th>Start Location</th>
<th>End Location</th>
<th>Route Name</th>
<th>Fair Price</th>
<th>Active Days</th>
<th>Currency</th>
<th>Detail</th>
<th>Redirect</th>
<th>Transit</th>
<th>Contact</th>
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

     
                        
                   <!-- container-fluid -->
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



        <!-- /Right-bar -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasRightLabel">Update Route</h5>
                                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                                      <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               
                                                
														     <div class="col-12">
															    <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">click to show</label><br>
                                    <button type="button" id="show_update" class="btn btn-soft-success"><i class="uil uil-plus "></i>Add route location</button>

                                            </div>
                                            </div>   
                                           <span id="route_add_area_update" style="display:none;">
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">Location Name</label><br>
   <input class="form-control" type="text" value="" id="l_name_update">
                                            </div>
                                            </div>   
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input"></label><br>
                                    <button type="button" id="save_location_update" class="btn btn-soft-success">Save</button>

                                            </div>
                                            </div>   
											</span>
											<div id="id_no"style="display:none;"></div>
                                                            <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Starting point</label>
                                                            <select class="form-select" id="starting_point_update"
                                                aria-label="Floating label select example">
												<option id="s_update">Choose Starting Point</option>
                                                    <?php echo $start_route2; ?>

                                            </select>
                                                               
                                                            </div> 
															<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Ending point</label>
                                                          <select class="form-select" id="end_point_update"
                                                aria-label="Floating label select example">
												<option id="e_update">Choose Ending Point</option>
                                                    <?php echo $start_route2; ?>
                                                 
                                            </select>
                                                            </div>
															
																	<div class="mb-3">
                                                                                <label class="form-label" for="formrow-password-input">Currecncy</label>
                                                          <select class="form-select" id="currency_update"
                                                aria-label="Floating label select example">
												<option id="update_currecny"></option>
                                                    <?php echo $curerncy; ?>
                                                 
                                            </select>
                                                            </div>
																<div class="mb-3">
                                                                <label class="form-label">Active Days</label>
                                                                 <input class="form-control" type="number"  id="active_day_update" placeholder="Enter active day"value="" >
                                                               
                                                            </div>	
															
															<div class="mb-3">
                                                                <label class="form-label">Fair</label>
                                                                 <input class="form-control" type="text" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"  id="fair_update" placeholder="Enter fair" >
                                                               
                                                            </div>
													
															<div class="mb-3">
                                                                <label class="form-label">initial name</label>
                                                                 <input class="form-control" type="text"  id="initial_update" placeholder="Dar to lub" >
                                                               
                                                            </div>	<div class="mb-3">
                                                                <label class="form-label">Contact one</label>
                                                                 <input class="form-control" type="text"  id="contact1_update" placeholder="" >
                                                               
                                                            </div>	<div class="mb-3">
                                                                <label class="form-label">Contact two</label>
                                                                 <input class="form-control" type="text"  id="contact2_update" placeholder="" >
                                                               
                                                            </div>	
															<div class="mb-3">
                                                                <label class="form-label">Detail</label>
                                                                 <input class="form-control" type="text"  id="details_update" placeholder="" >
                                                               
                                                            </div>	
																		<button type="button" class="btn btn-primary" id="save_update">Change</button>
                                                        </div>
						

                                                    </div>
                                               
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
	<script src="js/insert/route_insert.js"></script>
	<script src="js/insert/add_transit.js"></script>

				<script src="update_route.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

	<script src="currency_multiple.js"></script>
	<script src="js/insert/add_location.js"></script>
	<script src="js/insert/add_contact.js"></script>
</script>
    <script>
        $(document).ready(function(){
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch_route.php',
                },'columns': [
                    
				
					
                      { data: 'start_route' },
					  { data: 'end_route' },
					  { data: 'route_name' },   
					
					 
					  { data: 'fare_price' },
					
						{ data: 'active_date' },  
					   { data: 'currency' }, 
					  { data: 'detail' },
					  { data: 'badge' },
					  { data: 'transit' },
					  { data: 'contact' },
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
	
				   "fnDrawCallback": function (oSettings) {
        $(".dataTables_filter").each(function () {
            $(this).appendTo($(this).parent().siblings(".panel-body"));
        });
    }, preDrawCallback: function() {
                let el = $('div.dataTables_filter label');
                if(!el.parent('form').length) {
                    el.wrapAll('<form></form>').parent()
                    .attr('autocomplete', false)
                    .attr('autofill', false);
                }
            } 
            });
		 oTable = $('#example').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 
        });
        </script>
      </script>
    </body>
</html>
