<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$connect = sr_mysqli('ticket');
$vehicle = '';
$query = "SELECT name FROM vehicle WHERE " . tessera_sql_tenant_eq();
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $vehicle .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
?>  

<!doctype html>
<style>
.table{
	
	border:none;
}
#seat_no{
	font-size:13px;
	
	color:white;
	border-radius:11px;
	padding:2px;
	font-weight:bold;
}
#td{
	width: 18%;
}
.backseats{
	background:green;
}
</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Manifest</title>
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
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
    </head>

    
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php include 'top_header.php';?>
	
    
            <!-- ========== Left Sidebar Start ========== -->

            <!-- Left Sidebar End -->

            <style>
			button{
				color:white;
			}
			</style>

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
                                    <h4 class="mb-0">Manifest</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">Manifest list</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                  
                    </div> 
					                   <div class="row">
									        <div class="card">
										                               <div class="col-xl-3 col-sm-6"><br>
						 <label class="form-label" for="validationCustom02">Select Route</label>

                                                   <select name="route" id="route" class="form-select"   data-live-search="true"><?php

	
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' and route_name !='' order by id asc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){

}else if($select_num12 ==1){
	
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_name']; 
echo "  <option>".$route_full_name."</option>";
		}
}else if($select_num12 >=2){

	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_name']; 
echo "  <option>".$route_full_name."</option>";
}
}
	
									   ?></select>
                                                    </div>
                            <div class="col-lg-12">
							
                  <div id="load_manifest_table">
				     <div class="placeholder-glow">
                                                        <span class="placeholder col-12"style="height:50px;"></span>
                                                    </div> <br>
					    <div class="placeholder-glow">
                                                        <span class="placeholder col-12"style="height:150px;"></span>
                                                    </div> 
				  </div>
				  <!-- end card --> 
                            </div><!-- end col --> 
                        </div>
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
      <script src="update_manifest.js"></script>
<script type="text/javascript" charset="utf8" src="js/datatable.js"></script>
<script src="load_manifest_route.js"></script>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasRightLabel">Update Manifest</h5>
                                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                                      <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               
                                                
														     <div class="col-12">
															    <div class="btn-group me-2 mb-2 mb-sm-0">
   
                                            </div>   
                                       
											<div id="id_no"style="display:none;"></div>
											<div id="route_day_id"style="display:none;"></div>
											<div id="trip_date"style="display:none;"></div>
											<div id="bus"style="display:none;"></div>
                                                            <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Vehicle's Name</label>
                                                            <select class="form-select" id="vehicle"aria-label="Floating label select example">
												<option id="v_update"></option>
                                                    <?php echo $vehicle; ?>

                                            </select>
                                                               
                                                            </div> 
											
															
												
											
															<div class="mb-3">
                                                                <label class="form-label">Drivers Name</label>
                                                                 <input class="form-control" type="text"  id="drivers_name" placeholder="drivers" >
                                                               
                                                            </div>	
															<div class="mb-3">
                                                                <label class="form-label">Conductor's</label>
                                                                 <input class="form-control" type="text"  id="conductor_update" placeholder="conductors" >
                                                               
                                                            </div>	
																		<button type="button" class="btn btn-primary" id="save_changes">Change</button>
                                                        </div>
						

                                                    </div>
                                               
                                                </form>
												</div>
												
                                        </div>
  
        <!-- apexcharts init -->

    </body>
</html>

						<?php
	
	$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	  
			$level =$get12['role']; 
echo $level;

		
	}
}else{

}
if($level=="Agent"){
		?>
		
		    <script>
        $(document).ready(function(){
			var route = $( "#route option:selected" ).text().replace(/ /g,"_");

  $("#load_manifest_table").load("load_manifest_table.php?route="+route);
        });
        </script>
		<?php
}else{
	?>
	    <script>
        $(document).ready(function(){
			var route = $( "#route option:selected" ).text().replace(/ /g,"_");

  $("#load_manifest_table").load("load_manifest_table.php?route="+route);
        });
        </script>
	<?php
	
}
	?>
					

