<?php
//index.php
$connect = mysqli_connect("localhost", "root", "Teccross@360", "ticket");
$start_route2 = '';
$query = "SELECT start_route FROM assigned_routes GROUP BY start_route ORDER BY start_route ASC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["start_route"].'">'.$row["start_route"].'</option>';
}
?>

<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Search</title>
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
		  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'><link rel="stylesheet" href="./style.css">


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
  <?php
$start_location = @$_GET['start_location'];
$ending_location = @ $_GET['ending_location'];


?>
                                    <?php
									if(isset($_GET['date'])){
@$date = $_GET['date'];
$date = date("m/d/Y", strtotime($date));
  echo $date_now;


}else{
 header("Location: routes.php");
}
?>

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Routes and Trips</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">Routes and Trips</li>
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
              
<?php
if(isset($_GET['date'])){
@$date = $_GET['date'];
$date_now = date("m/d/Y", strtotime($date));
  $tomorrow2 =  date("m/d/Y", strtotime($date_now."+1 days"));
  $today_date_word = date('l', strtotime($tomorrow2));


}else{
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('m/d/Y', time());
$today_date_word = date('l', strtotime($date_today));

$tomorrowmo =  date("m/d/Y", strtotime($date_today."+1 days"));
}

?>
			  <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="card card-h-100">
                                            <div class="card-body">
                                                <button class="btn btn-outline-warning w-100" data-bs-toggle="modal"
                                            data-bs-target="#myModal"
											
											><i class="uil-search-alt"></i> Search For Furture trip date</button>
                                                

                                            </div>
                                        </div>
                                    </div> <!-- end col-->

                                    <div class="col-xl-9">
                                        <div class="card card-h-100">
                                            <div class="card-body">
<div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
											  		<?php
		include 'connect.php';
		$select14=mysqli_query($db,"SELECT *  FROM  assigned_routes WHERE start_route='$start_location' AND end_route='$ending_location' and username='$username'");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$active =$get134['active']; 
			$active_tab =$get134['active_tab']; 
			
		
echo "$route_id";
		

		?>
<div class="tab-pane fade <?php echo $active_tab;?>" id="v-pills-<?php echo $id;?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $id;?>-tab">
<div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title"><?php 
										echo "$start_route to $end_route";?></h4>
                                     </div><br>
             					 <?php
										
		include 'connect.php';
		$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow'  and route_id ='$route_id' or day ='$today_date_word' and route_id ='$route_id' ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $id2 =$get['id'];
			$arrival =$get['arrival'];
			$departure =$get['departure'];
			$time =$get['12hours'];
			$trip_day =$get['day']; 
			$bus =$get['bus']; 
			$now_time = date('h:i A', time());

				
				
?>
                                                                               <div class="card border shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
                                                                        <h5 class="font-size-15 mb-1"><a href="projects-overview.html" class="text-dark"><?php echo $trip_day;?></a></h5>
                                                                        <p class="text-muted mb-0">
													
														  <p class="text-muted">
														T646DVL - AC LUXURY YUTONG F12 PLUS China
														  </p>
																		</p>
                                                                    </div>
                                                            
                                                                </div>
                                                            </div>
                                                            <!-- end card body -->
                                                            <div class="">
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Departure time</p>
                                                                                <div class="badge badge-soft-primary font-size-12"><?php echo $time;?></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Arrival time</p>
                                                                                <h5 class="font-size-14 mb-0">20 May, 2021</h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Available seats</p>
                                                                                					   <div class="flex-grow-1"style="width:200px;">
                                                          <div class="badge bg-warning font-size-12">45 seats available</div>
                                                            <div class="progress animated-progess custom-progress">
                                                                <div class="progress-bar" role="progressbar" style="width: 82%" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                         <div>
                                                                               
                                    <button type="button" class="btn btn-outline-primary">Book Seats</button>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>
                                                        </div>

<?php
}}else{
	?>
      <div class="text-center py-5">
                            <div class="mb-4 mb-md-5">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                            <i class="uil uil-annoyed-alt "></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted">
                                    <h4 class="">Sorry No Trip found!</h4>
                                    <p>No trip found at <span class="fw-semibold">at this time</span></p>
                                
                                </div> 
                            </div>
	
	<?php
	
}
?>
					
 </div>
             
                                                   <?php
}}
	   ?>                                    
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
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
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center bg-dark p-3">

                    <h5 class="m-0 me-2 text-white">Theme Customizer</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle-close ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="m-0" />

                <div class="p-4">
                    <h6 class="mb-3">Layout</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout"
                            id="layout-vertical" value="vertical">
                        <label class="form-check-label" for="layout-vertical">Vertical</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout"
                            id="layout-horizontal" value="horizontal">
                        <label class="form-check-label" for="layout-horizontal">Horizontal</label>
                    </div>

                    <h6 class="mt-4 mb-3">Layout Mode</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-mode"
                            id="layout-mode-light" value="light">
                        <label class="form-check-label" for="layout-mode-light">Light</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-mode"
                            id="layout-mode-dark" value="dark">
                        <label class="form-check-label" for="layout-mode-dark">Dark</label>
                    </div>

                    <h6 class="mt-4 mb-3">Layout Width</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-width"
                            id="layout-width-fluid" value="fluid" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                        <label class="form-check-label" for="layout-width-fluid">Fluid</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-width"
                            id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                        <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                    </div>

                    <h6 class="mt-4 mb-3">Layout Position</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-position"
                            id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                        <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-position"
                            id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                        <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                    </div>

                    <h6 class="mt-4 mb-3">Topbar Color</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="topbar-color"
                            id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                        <label class="form-check-label" for="topbar-color-light">Light</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="topbar-color"
                            id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                        <label class="form-check-label" for="topbar-color-dark">Dark</label>
                    </div>

                    <div id="sidebar-setting">
                    <h6 class="mt-4 mb-3 sidebar-setting">Sidebar Size</h6>

                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                        <label class="form-check-label" for="sidebar-size-default">Default</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                        <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                        <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                    </div>

                    <h6 class="mt-4 mb-3 sidebar-setting">Sidebar Color</h6>

                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                        <label class="form-check-label" for="sidebar-color-light">Light</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                        <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                        <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                    </div>
                </div>

                    <h6 class="mt-4 mb-3">Direction</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-direction"
                            id="layout-direction-ltr" value="ltr">
                        <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-direction"
                            id="layout-direction-rtl" value="rtl">
                        <label class="form-check-label" for="layout-direction-rtl">RTL</label>
                    </div>

                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
		                           <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Choose Trip date</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                      <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                                   
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Starting Area</label>
                                                                <select class="form-select shadow-none action" name="category"
                                                                    id="event-category country" required>
																	<option>Choose starting Point</option>
                                                                     <?php echo $start_route2; ?>
                                                                </select>
                                                                <div class="invalid-feedback">Please select a valid event category</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Destination</label>
                                                                <select class="form-select shadow-none" name="category"
                                                                    id="state" required>
                                                                  
                                                                </select>
                                                                <div class="invalid-feedback">Please select a valid event category</div>
                                                            </div>
                                                        </div>
														     <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Trip date</label>
                                                                 <input class="form-control" type="date"  id="example-date-input">
                                                                <div class="invalid-feedback">Please provide a valid event name</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               
                                                </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="search">Search</button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
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
		   		<script src="search_routes.js"></script>
		<script src="js/sweetalert.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
		<script src='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'>
</script>
<script>
$(document).ready(function(){
 $('.action').change(function(){
  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';
   if(action == "event-category country")
   {
    result = 'state';
   }
  
   $.ajax({
    url:"fetch_dependence.php",
    method:"POST",
    data:{action:action, query:query},
    success:function(data){
     $('#'+result).html(data);
    }
   })
  }
 });
   document.getElementById("example-date-input").min = new Date().getFullYear() + "-" +  parseInt(new Date().getMonth() + 1 ) + "-" + new Date().getDate()

});
</script>

      </script>
    </body>
</html>
