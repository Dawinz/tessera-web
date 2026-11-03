
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Route</title>
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
 
	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		

		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	     <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />

	  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />   

        <!-- One of the following themes -->
   <!-- 'nano' theme -->

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" type="text/css" href="css/buttons.css">
   </head>
    
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
                        <div class="col-xl-8">
                            <div class="card card-h-100">
                                <div class="card-body">
                                

                          <table>
						  <tr>
						  <td>
						        <select name="route" id="route" class="form-control selectpicker"  data-live-search="true">
									  <option>adsf</option><option>asd</option>

									  </select>
						  </td>
						  </tr>
						  </table>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-nowrap mb-0 align-middle table-check">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="rounded-start" style="width: 15px;">
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="checkAll">
                                                            <label class="form-check-label" for="checkAll"> </label>
                                                        </div>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Customer</th>
                                                    <th>Purchased</th>
                                                    <th colspan="2" class="rounded-end">Revenue</th>
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
                                            <!-- end thead -->
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="flexCheckexampleone">
                                                            <label class="form-check-label" for="flexCheckexampleone">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="fw-medium">
                                                        #DK1018
                                                    </td>
                                                    <td>
                                                        1 Jun, 11:21
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <i class="mdi mdi-check-circle-outline text-success"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0">Paid</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="assets/images/users/avatar-2.jpg"
                                                                    class="avatar-sm img-thumbnail h-auto rounded-circle"
                                                                    alt="Error">
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 text-truncate mb-0"><a href="#"
                                                                        class="text-reset">Alex Fox</a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Wireframing Kit for Figma
                                                    </td>

                                                    <td>
                                                        $129.99
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- end /tr -->
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="flexCheckexamplethree">
                                                            <label class="form-check-label" for="flexCheckexamplethree">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="fw-medium">
                                                        #DK1017
                                                    </td>
                                                    <td>
                                                        29 May, 18:36
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <i class="mdi mdi-check-circle-outline text-success"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0">Paid</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="assets/images/users/avatar-3.jpg"
                                                                    class="avatar-sm img-thumbnail h-auto rounded-circle"
                                                                    alt="Error">
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 text-truncate mb-0"><a href="#"
                                                                        class="text-reset">Joya Calvert</a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Mastering the Grid <span class="text-muted">+2 more</span>
                                                    </td>

                                                    <td>
                                                        $228.88
                                                    </td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- end /tr -->
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="flexCheckexamplefour">
                                                            <label class="form-check-label" for="flexCheckexamplefour">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="fw-medium">
                                                        #DK1016
                                                    </td>
                                                    <td>
                                                        25 May , 08:09
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <i
                                                                    class="mdi mdi-arrow-left-thin-circle-outline text-warning"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0">Refunded</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="assets/images/users/avatar-4.jpg"
                                                                    class="avatar-sm img-thumbnail h-auto rounded-circle"
                                                                    alt="Error">
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 text-truncate mb-0"><a href="#"
                                                                        class="text-reset">Gracyn Make</a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Wireframing Kit for Figma
                                                    </td>

                                                    <td>
                                                        $9.99
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- end /tr -->
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="flexCheckexamplefive">
                                                            <label class="form-check-label" for="flexCheckexamplefive">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="fw-medium">
                                                        #DK1015
                                                    </td>
                                                    <td>
                                                        19 May , 14:09
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <i class="mdi mdi-check-circle-outline text-success"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0">Paid</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="assets/images/users/avatar-5.jpg"
                                                                    class="avatar-sm img-thumbnail h-auto rounded-circle"
                                                                    alt="Error">
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 text-truncate mb-0"><a href="#"
                                                                        class="text-reset">Monroe Mock</a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Spiashify 2.0
                                                    </td>

                                                    <td>
                                                        $44.00
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- end /tr -->
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="flexCheckexamplesix">
                                                            <label class="form-check-label" for="flexCheckexamplesix">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="fw-medium">
                                                        #DK1014
                                                    </td>
                                                    <td>
                                                        10 May , 10:00
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <i class="mdi mdi-check-circle-outline text-success"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0">Paid</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="assets/images/users/avatar-6.jpg"
                                                                    class="avatar-sm img-thumbnail h-auto rounded-circle"
                                                                    alt="Error">
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 text-truncate mb-0"><a href="#"
                                                                        class="text-reset">Lauren Bond</a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Mastering the Grid
                                                    </td>

                                                    <td>
                                                        $75.87
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- end /tr -->
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="flexCheckexamplenine">
                                                            <label class="form-check-label" for="flexCheckexamplenine">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="fw-medium">
                                                        #DK1011
                                                    </td>
                                                    <td>
                                                        29 Apr , 12:46
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <i class="mdi mdi-close-circle-outline text-danger"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0">Changeback</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="assets/images/users/avatar-9.jpg"
                                                                    class="avatar-sm img-thumbnail h-auto rounded-circle"
                                                                    alt="Error">
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 text-truncate mb-0"><a href="#"
                                                                        class="text-reset">Ricardo Schaefer</a> </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Spiashify 2.0
                                                    </td>

                                                    <td>
                                                        $63.99
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- end /tr -->
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="flexCheckDefaultexample">
                                                            <label class="form-check-label" for="flexCheckDefaultexample">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="fw-medium">
                                                        #DK1010
                                                    </td>
                                                    <td>
                                                        27 Apr , 10:53
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <i class="mdi mdi-check-circle-outline text-success"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0">Paid</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="assets/images/users/avatar-10.jpg"
                                                                    class="avatar-sm img-thumbnail h-auto rounded-circle"
                                                                    alt="Error">
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 text-truncate mb-0"><a href="#"
                                                                        class="text-reset">Arvi Hasan</a> </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Wireframing Kit for Figma
                                                    </td>

                                                    <td>
                                                        $51.00
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- end /tr -->

                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card card-h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mb-4">Sales by County</h4>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="fw-semibold">Report By:</span> <span
                                                    class="text-muted">Monthly<i
                                                        class="mdi mdi-chevron-down ms-1"></i></span>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Yearly</a>
                                                <a class="dropdown-item" href="#">Monthly</a>
                                                <a class="dropdown-item" href="#">Weekly</a>
                                                <a class="dropdown-item" href="#">Today</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="world-map-markers" style="height: 242px;"></div>

                                    <div class="pt-3 pb-2">
                                        <p class="mb-0 fw-medium">USA <span class="float-end">75%</span></p>
                                        <div class="progress animated-progess custom-progress mt-2">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                                            </div>
                                        </div>

                                        <p class="mt-4 mb-0 fw-medium">Russia <span class="float-end">55%</span></p>
                                        <div class="progress animated-progess custom-progress mt-2">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="55">
                                            </div>
                                        </div>

                                        <p class="mt-4 mb-0 fw-medium">Australia <span class="float-end">85%</span></p>
                                        <div class="progress animated-progess custom-progress mt-2">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="85">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
	
		  <!-- end page title -->

           
						

 
               <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/" target="_blank" class="text-reset">Pichforest</a>
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
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
        <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- plugin js -->


        <!-- Calendar init -->
     
        <script src="assets/js/app.js"></script>
	
		   		<script src="search_routes.js"></script>
		<script src="js/sweetalert.js"></script>

	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>

        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	
</script>
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

</script>
<script>
$(document).ready(function(){

  document.getElementById("example-date-input").min = new Date().getFullYear() + "-" +  parseInt(new Date().getMonth() + 1 ) + "-" + new Date().getDate()


$("#load_pinned").load("pinned_few.php");
$("#load_pinned_number").load("load_pinned_number.php");

	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "mm/dd/yyyy",
	
		//container: console.log($(this).datepicker())
}); 




});
</script>

      </script>
    </body>
</html>
