<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
$connect = sr_mysqli('ticket');
$username_names = '';
$query = "SELECT username FROM users where status ='active'";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $username_names .= '<option value="'.$row["username"].'">'.$row["username"].'</option>';
}
?>  


<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Assign route'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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
		  		<script src="js/vendor/jquery-1.12.4.min.js"></script>

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
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Edit roles</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of roles</li>
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
                            
                                <div class="card">
								<div class="card-header justify-content-between d-flex align-items-center">
                       <div class="form-floating mb-3">
                                             <div class="form-floating mb-3">
                                            <select class="form-select" id="username_names"
                                                aria-label="Floating label select example">
                                                <option selected>List of user in the system</option>
                                             <?php echo $username_names;?>
                                            </select>
                                            <label for="floatingSelectGrid">Please choose user</label>
                                        </div>
                                        </div>
                                    </div>
								                 <div >
                                               <div id="load_username_roles"></div>
											   
											   </div>
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
                                                        <h5 class="modal-title" id="myModalLabel">Register Vehicle</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                      <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               
                                                
														     <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Vehicle Name</label>
                                                                 <input class="form-control" type="text"  id="Vehicle_name" placeholder="Vehicle name" >
                                                               
                                                            </div> 
															<div class="mb-3">
                                                                <label class="form-label">Driver's Name</label>
                                                                 <input class="form-control" type="text"  id="driver_name" placeholder="Driver's Name" >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Conductor's Name</label>
                                                                 <input class="form-control" type="text"  id="conductor_name" placeholder="Conductor's Name" >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Total seats</label>
                                                                 <input class="form-control" type="number"  id="seat_no" placeholder="Total seats" >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Details</label>
                                                                 <input class="form-control" type="text"  id="detail" placeholder="Details" >
                                                               
                                                            </div>	<div class="mb-3">
                                                            
                                                    <label class="form-check-label" for="horizontal-customCheck">Active</label>
                                                    <input type="checkbox" class="form-check-input" id="check" checked>

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
                                              <h5 id="offcanvasRightLabel">Update Bus</h5>
                                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
       <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               <div id ="id_no"style="display:none;"></div>
                                                
														     <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Vehicle Name</label>
                                                                 <input class="form-control" type="text"  id="Vehicle_name_update" placeholder="Vehicle name" >
                                                               
                                                            </div> 
															<div class="mb-3">
                                                                <label class="form-label">Driver's Name</label>
                                                                 <input class="form-control" type="text"  id="driver_name_update" placeholder="Driver's Name" >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Conductor's Name</label>
                                                                 <input class="form-control" type="text"  id="conductor_name_update" placeholder="Conductor's Name" >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Total seats</label>
                                                                 <input class="form-control" type="number"  id="seat_no_update" placeholder="Total seats" >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Details</label>
                                                                 <input class="form-control" type="text"  id="detail_update" placeholder="Details" >
                                                               
                                                            </div>	<div class="mb-3">
                                                            
                                                    <label class="form-check-label" for="horizontal-customCheck">Active</label>
                                                    <input type="checkbox" class="form-check-input" id="check_update" checked>

                                                            </div>
                                                        </div>
                                                    </div>
                                               <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="change_btn">Change</button>
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
		   		
		<script src="js/sweetalert.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	

	<script>
	$(document).ready(function(){
	$('#username_names').change(function() {
				var username_names = $("#username_names option:selected" ).text();
		if(username_names=="List of user in the system"){
 swal("", "Choose username !", "warning");

			 control.disabled="disabled";	}

		$("#load_username_roles").text("loading...");
			 $("#load_username_roles").load("user_routes.php?username="+username_names);
});
	});
	</script>

    </body>
</html>
