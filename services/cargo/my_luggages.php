    <style>
	iframe{
	height:1000px;
	width:100%;
}
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
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 0.25rem !important;
    border: 1px solid #dee2e6 !important;
    background: #fff !important;
    color: #495057 !important;
}
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
<!doctype html>

<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'My luggage'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
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
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
      <link rel="stylesheet" type="text/css" href="css/buttons.css">
      		<script src="js/vendor/jquery-1.12.4.min.js"></script>
       <link rel="stylesheet" href="css/datapicker/datepicker3.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />   
<link rel="stylesheet" type="text/css" href="select_css.css">
        <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />

    </head>

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

            
     <?php
      include 'top_header.php';
      include 'modal/delete_luggage.php';
      include 'includes/all.php';
      ?>
	
  
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
                                    <h4 class="mb-0">All Charged Luggages</h4>

                                 

                                </div>
                            </div>
                        </div>


        

<span id="route_name" style="display:none;"><?php  my_assigned_routes_query($db,$_SESSION['username']);?></span>

						            <div class="parent">
  
</div>
						 <?php
	 $username=$_SESSION['username'];

	 ?>
              
   <div class="row">
                            
                                <div class="card sr-table-panel">
	
								             <div class="row">

                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Select Route</label>
<select name="route" id="route" class="form-select action selectpicker"  data-live-search="true">
    <option>Select Route</option>
    <?php my_assigned_routes($db,$_SESSION['username']);?></select>
                                                    </div>
                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Start Date</label>
<input class="form-control dateselect" type="text" value="" id="start_date" placeholder="yyyy-mm-dd" >

                                                    </div>
                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Start Date</label>
<input  class="form-control dateselect" type="text" value="" id="end_date" placeholder="yyyy-mm-dd" >

                                                    </div>
                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Search Info</label>
<input  class="sr-table-search form-control " type="text" value="" id="search" placeholder="search..." >

                                                    </div>
                                             </div>
                                  
                                    <div class="card-body">
				  <div class="table-responsive">
       
	  <table class="table mb-0" id="empTable">
           <thead class="table-light">
                     <tr>
                   <th>Code</th>  
                    <th>Dispatch</th> 

					<th>Sender Name</th>               
                    <th>Receiver's name</th>
                   
				   <th>Date</th>
				  
  <th>Weight</th>
  <th>Detail</th>
  <th>Quantity</th>
    <th>To paid</th> 
	 <th>Paid</th> 
 <th>Amount</th>
 <th>Print</th> 
 <th>Edit</th>
  <th>Delete</th> 
  
                </tr>
                </thead>
	
                                          <!-- end tbody --> 
                                            </table>
	   
                                    </div>   
                                
                                </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                        
                            <!-- end col -->
                        </div>
			  <!-- end page title -->

     <?php
     
     
     ?>
                      
              <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
<input type="hidden" id="username_id" value="<?php echo $_SESSION['username'];?>">
                
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
                <!-- End Page-content -->

         

				
      
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

  <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
   <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
    <script src="js/delete/delete_luggage.js"></script>

	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  	

		
		<script src="fetch_all_my_luggages.js?<?php echo modified_time("fetch_all_my_luggages.js");?>  "></script>
<script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
  
        <!-- apexcharts init -->

    </body>
</html>
<script>

    $(document).ready(function(){
$('.selectpicker').select2({
    });	

	$('.dateselect').datepicker({
		todayHighlight: true,
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 	

    });
</script>
  
   