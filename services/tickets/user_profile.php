<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>User profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
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
		  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'><link rel="stylesheet" href="./style.css">

<link rel="stylesheet" type="text/css" href="css/datatable.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">
    </head>

    
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php include 'top_header.php';?>
	
    				  <?php
include 'connect.php';
$id= $_GET['u'];
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $seat_no =$get13['seat_no']; 
 $cost =$get13['cost']; 
 $id= $get13['id']; 
 $trip_date= $get13['trip_date']; 
 $status= $get13['status'];
 $firstname= $get13['firstname'];
 $lastname= $get13['lastname'];
 $phone_no= $get13['phone_no'];
$validation= $get13['validation'];
}}?>

    				  <?php
include 'connect.php';
$id= $_GET['u'];
$select14=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (phone_no='$phone_no')");
$select_num14=mysqli_num_rows($select14);


?>
            <!-- ========== Left Sidebar Start ========== -->

            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
             <div class="mx-n4 mt-n4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card sr-table-panel mb-0">
                                        <div class="card-body p-0">
                                            <div class="profile-bg position-relative overflow-hidden">
                                                <div class="bg-overlay bg-dark "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card -->
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- start page title -->
            
						            <div class="parent">
  
</div>

			  <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                <div class="card-body">
                 <div class="row">
                            <div class="col-xl-3">
                                <div class="card mt-n5">
                                    <div class="card-body text-center">
                                        <div class="text-end">
                                   
                                        </div>
                                        <div class="avatar-xl mx-auto mb-4">
                                            <img src="assets/images/users/user.png" alt="" class="rounded-circle img-thumbnail">
                                        </div>
                                        <h5 class="mb-1"><?php echo "$firstname $lastname";?></h5>
                                        <p class="text-muted">Phone number <?php echo $phone_no;?></p>

                                        <div><i class="mdi mdi-wallet text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Total Tickets Booked : <b class="fw-semibold font-size-16"><?php echo $select_num14;?></b></span></div>
                                    </div>
                  
                                </div>
                                <!-- end card -->


                                <!-- end card -->

 
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                            <div class="col-xl-9">
                                <div class="mt-4">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-4">List of all books</h5>
                                    <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 
<th>T no</th>
<th>Trip date</th>
<th>Trip </th>
<th>Start Route </th>
<th>End Route</th><th>Print</th>
</tr>
				
                          </thead>
                                          <!-- end tbody --> 
                                            </table>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->

                                            <!-- end card -->

                   
                                            <!-- end card -->
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-4">Total Number of trips</h5>
    				  <?php

$id= $_GET['u'];
$select145=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$id') GROUP BY route_id ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get145=mysqli_fetch_array($select145)){
 $seat_no =$get145['seat_no']; 

 $route_start= $get145['route_start'];
$route_end= $get145['route_end'];

?>
                                                    <div class="d-flex flex-wrap gap-2 font-size-16">
                                                                <div  class="list-group-item list-group-item-action">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar flex-shrink-0 me-3">
                                                                    <img src="assets/images/users/user.png" alt="" class="img-thumbnail rounded-circle">
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div>
                                                                        <h5 class="font-size-14 mb-1"><?php echo "$route_start to $route_end"; ?></h5>
                                                                        <p class="font-size-13 text-muted mb-0">Total number of trip : <?php echo $select_num145;?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
													
<?php
}}
?>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->

                                 
                                            <!-- end card -->

                                        <!-- end card -->
                                        </div>
                                    </div><!-- end row -->
                                </div>
                            </div><!-- end col -->
                        </div>

                                            </div>
                                </div> 

            
                                <!-- end modal-->
                            </div>
                        </div>
                        
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
        <script src="assets/libs/fullcalendar/main.min.js"></script>
		<script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

        <!-- Calendar init -->
        <script src="assets/js/pages/calendar.init.js"></script>
        <script src="assets/js/app.js"></script>
		 <script src="assets/js/pages/form-advanced.init.js"></script>
		  <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		   <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		   		<script src="search_routes.js"></script>
		<script src="js/sweetalert.js"></script>
			<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
		<script src='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'>
</script>
    <script>
        $(document).ready(function(){
			var phone_no = "<?php echo  $phone_no;?>";
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 0, "desc" ]],
				
                'ajax': {
                'url':'fetch_booking_info.php',
								 type:"POST","data": {
        "phone_no":phone_no
    }
                },'columns': [
                    
				
					
                      { data: 'id' },
					  { data: 'trip_date' },
							                     
					  { data: 'trip' }, 
					  { data: 'start_location' },
					  { data: 'ending_location' }, 
					  { data: 'print' }, 
					  
					
					
              
                ],
				"lengthMenu": [[5, 20, 20, -1], [20, 20, 20, "All"]],
				dom: 'Bfrtip',
        buttons: [
		
  
			{ extend: 'print'},
            { extend: 'pdfHtml5' }
        ], 
				dom: 'Bfrtip',
        buttons: [
        
			{ extend: 'print', footer: true},
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
