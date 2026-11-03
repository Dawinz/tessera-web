<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$trip_date= @$_GET['date'];
$vehicle= @$_GET['date3'];
$driver= @base64_decode($_GET['driver']);
$Conductor= @base64_decode($_GET['Conductor']);
$route= @base64_decode($_GET['route']);
$code= @base64_decode($_GET['code']);
include 'connect.php';
include 'dependence/all_dependency.php';

?>



<!doctype html>
<style>

</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Luggages</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
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
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
   <link rel="stylesheet" href="css/datapicker/datepicker3.css">
   <link rel="stylesheet" type="text/css" href="css/buttons.css">
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">

    </head>
<style>

button.dt-button, div.dt-button, a.dt-button, input.dt-button {
    border-radius: 2px;
    color: black;
    background-color: #F5F6F8;
}
.stock{
	text-decoration:line-through;
	text-decoration-thickness :2px;
	text-decoration-color:red;

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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Manfest luggage list</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Luggages</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						            <div class="parent">
                                    
  
</div>
								
              
   <div class="row">
                            
                                <div class="card">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Vehicle lists</h4>
                                 
								   </div><!-- end card header -->
                                    <div class="card-body">
                                        							<div style="font-size:14px;">
	<span>Manifest Id : <span style="font-weight:bold;"><u><?php echo $code;?></u></span><br>
	Vehicle Name : <span style="font-weight:bold;"><?php echo $vehicle;?></span><br>
	Trip Date : <span style="font-weight:bold;"><?php echo date('jS M Y', strtotime($trip_date)) ;?></span>
	<p>Driver's Name: <span style="font-weight:bold;"><?php echo $driver;?></span><br>
	Conductor's Name : <span style="font-weight:bold;"><?php echo $Conductor;?></span><br>

	</p>
	</div>
 
										<div class="row">

	<div class="col">
	  <div class="">
                                                    
                                                    <div class="col-md-10">
											<label class="form-label" for="validationCustom02">Route</label>

													
<select class="form-select" id="route_name">
    <option value="">All Routes</option>
    <option value="<?php echo $route;?>"><?php echo $route;?></option>
   <?php
    transit_route($db,$route);
    ?>
</select>
</div>
	</div>
	</div>
	<div class="col">
	  <div class="">
                                                    
                                                    <div class="col-md-10">
											<label class="form-label" for="validationCustom02">Status</label>

													
<select class="form-select" id="status_luggage">
<option value="">All Status</option>
<option value="To be Payed">To be paid</option>
<option value="paid">Paid</option>
<option value="Due payment">Due payment</option>
</select>
</div>
	</div>
	</div>
	<div class="col">
	  <div class="">
                                                    
                                                    <div class="col-md-10">
											<label class="form-label" for="validationCustom02">Luggage Type</label>

													
<select class="form-select" id="type">
    <option value="">All Type</option>
    <option value="Direct">Direct</option>
    <option value="transit">Tranist</option>
</select>
</div>
	</div>
	</div>
	<div class="col">
	  <div class="">
                                                    
                                                    <div class="col-md-10">
											<label class="form-label" for="validationCustom02">Starting Date</label>

													
<input type="text" id="start_date"class="form-control dateselect " placeholder="date"value ="">
</div>
	</div>
	</div>
	<div class="col">
	  <div >
                                                    
                                                    <div >
<label class="form-label" for="validationCustom02">Ending Date</label>

  <input type="text" id="end_date"class="form-control dateselect" placeholder="date"value =""/>
</div>
	</div>
	</div>

	
                      
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                   <div id="load_manifest_view"></div>
                            <!-- end col -->
                        </div>
			  <!-- end page title -->

     
                        
                   <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
      
            </div>
                <!-- End Page-content -->
                 <input type="hidden"value="<?php echo $trip_date;?>" id="trip_date"/>
                 <input type="hidden"value="<?php echo $vehicle;?>"id="vehicle"/>
              

										
							
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/" target="_blank" class="text-reset">Sr classic</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
    
		                   
        <!-- /Right-bar -->

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
		<script src="js/sweetalert.js"></script>
		<script src="manifest_view.js"></script>
  <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
   <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>

<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>

  
        <!-- apexcharts init -->

    </body>
</html>
  
    <script>
        $(document).ready(function(){
			
			


		 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "top left",
		 format: "yyyy-mm-dd",
         autoclose: true
		 
	
		//container: console.log($(this).datepicker())
}); 
			
     	
            $('#empTable').DataTable({
			
				"order": [],
				"lengthMenu": [[300, 325, 450, -1], [300, 325, 450, "All"]],
                   dom: 'Bfrtip',
				   buttons: [
                { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print', 
			className: 'red',
			 autoPrint: true
              
                 }],
            });
     });

        </script>