<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Profile'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
    </head>

    
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php include 'top_header.php';?>
	
    				  <?php
include 'connect.php';
$u= $_GET['u'];
$select1=mysqli_query($db,"SELECT *  FROM  users WHERE username='$u'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
$image = $get13['image']; 
$firstname = $get13['firstname']; 
$surname = $get13['surname']; 
$phone_no = $get13['number']; 
}}

date_default_timezone_set('Africa/Dar_es_Salaam');
$query_date = date('m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));
$month =  date('m', strtotime($query_date));
$year =  date('Y', strtotime($query_date));

$sql = $db->query("SELECT SUM(cost) AS  cost FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >= '$date1' and trip_date <= '$date2' and month='$month' and year='$year' and booker='$u')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['cost'];
			
		}}else{
			echo "0.00";
		}

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
                                    <div class="card mb-0">
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
                                            <img style="width:100px"src="<?php
											$imgFile = trim((string) ($image ?? ''));
											if ($imgFile === '' || $imgFile === 'user.png') {
												echo 'assets/images/users/user.png';
											} else {
												echo 'uploads/' . rawurlencode($imgFile);
											}
											?>" alt="" class="rounded-circle img-thumbnail" id="preImg">
                                        </div>
                                        <h5 class="mb-1"><?php echo "$firstname $surname";?></h5>
                                        <p class="text-muted">Phone number <?php echo $phone_no;?></p>

             
                                    </div>
<form action="upload.php" id="form" method="post"enctype="multipart/form-data">
                   <div class="d-flex border-top p-2">
                                        <div class="flex-grow-1">
                                            <div class="p-2 ps-3"> <input class="form-control form-control-sm" type="file"id="myImage"></div>
                                        </div>
                                        <div class="flex-shrink-0">
                                         <button  class="btn btn-outline-warning" type="submit"id="submit" ><i class="uil  uil-upload "></i></button>
                                        </div>
                                    </div>
									</form>
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
                                                    <h5 class="card-title mb-4">List of assigned Routes</h5>
    	<ul class="list-group list-group-flush">
								<?php
												include 'connect.php';
											



$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where   username='$u'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){

 $start_route= $get132['start_route']; 
 $end_route= $get132['end_route']; 
 $fare_price= $get132['fare_price']; 

 $detail= $get132['detail']; 

												?>		
     <li class="list-group-item py-3 px-0">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-3">
                              
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="font-size-14 mb-1 text-truncate"><a href="#" class="text-dark"><?php echo base64_decode($detail);?></a></h5>
                                                                        <ul class="list-inline font-size-13 text-muted">
                                                                            <li class="list-inline-item">
                                                                                <a href="#" class="text-muted">
                                                                                    <i class="uil uil-user me-1"></i> <?php echo $start_route;?>
                                                                                </a>
                                                                            </li>
                                                                            <li class="list-inline-item">
                                                                                <i class="uil uil-calender me-1"></i><?php echo "$end_route";?>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                 
                                                                </div>
                                                            </li>
															

  

															<?php
															}}
															?>
															</ul>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->

                                            <!-- end card -->

                   
                                            <!-- end card -->
                                        </div>

                                        <div class="col-lg-4">
                                           <div class="card">
                                          
                                                <div>

                                                    <div class="list-group list-group-flush mb-2">
                                                        <a href="#" class="list-group-item list-group-item-action">
                                                            <div class="d-flex align-items-center">
                                                          
														  
														  
														  
                                                                <div class="flex-grow-1">
                                                                    <div>
                                                                        <h5 class="font-size-14 mb-1">Total Monthly Charge</h5>
                                                                        <p class="font-size-13 text-muted mb-0">
<?php echo ''.number_format($amount,2).' Tsh';

										
										?>
																		</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>    <a href="#" class="list-group-item list-group-item-action">
                                                            <div class="d-flex align-items-center">
                                                          
														  
														  
														  
                                                                <div class="flex-grow-1">
                                                                    <div>
                                                                        <h5 class="font-size-14 mb-1">TOTAL FOC SEAT</h5>
                                                                        <p class="font-size-13 text-muted mb-0">
																		    				  <?php

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >= '$date1' and trip_date <= '$date2' and month='$month' and year='$year' and cost='0' and status !='booked' and issuer='$u')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
echo $select_num1;
}
?>
																		</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="list-group-item list-group-item-action">
                                                            <div class="d-flex align-items-center">
                                                            
                                                                <div class="flex-grow-1">
                                                                    <div>
                                                                        <h5 class="font-size-14 mb-1">Total booked</h5>
                                                                        <p class="font-size-13 text-muted mb-0">  <?php

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >= '$date1' and trip_date <= '$date2' and month='$month' and year='$year'  and status !='booked' and issuer='$u')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
echo $select_num1;
}

?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                            
                                               
                                                    </div>
                                                </div><br><br><br><!-- end card body -->
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
       <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
								 <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Yearly Sales Chart</h4>
                                     </div>
                                              <?php include 'yearly_charts.php';?>    

                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div>
            	       <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
								 <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Daily Ticket Sales</h4>
                                     </div>
                                              <?php include 'daily_sales.php';?>    

                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div>
                                <!-- end modal-->
                            </div>
                        </div>
                        
                    </div>
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
		   		<script src="image_upload_profile.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
		<script src='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'>
</script>
   

      </script>
    </body>
</html>
