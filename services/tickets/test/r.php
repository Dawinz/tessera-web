
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
 
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		

		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	     <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />

	  
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
 <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                        Pinned trips  <span class="badge bg-success ms-1"><div id="load_pinned_number">0</div></span>
                                    </button>
</div><br>
						 <?php
	 $username=$_SESSION['username'];

	 ?>
             <?php
//index.php
$connect = mysqli_connect("localhost", "root", "Teccross@360", "ticket");
$start_route2 = '';
$query = "SELECT name FROM locations_names ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
 $seats=0;
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
                                                
                                                <div id="external-events">
                                                    <br>
                                                    <p class="text-muted">
													List of your assgined late today and tommorow's trips (<?php 	  
				  date_default_timezone_set('Africa/Dar_es_Salaam');
				  $tomorrow = date("m/d/Y", strtotime("+1 day")); 
				  $day_after = date("m/d/Y", strtotime("+2 day")); 
				  $day_2 = date("m/d/Y", strtotime("+3 day"));
				  $day = date("m/d/Y");
				  $tommorw_day = date('l', strtotime($tomorrow));
				  $today_day = date('l', strtotime($day));
				  $tommorw = date('F l Y', strtotime($tomorrow));
				  $day_aft = date('F l Y', strtotime($day_after));
				  $day_23 = date('F l Y', strtotime($day_2));
				  echo date('F/Y/l', strtotime($tomorrow));
				   
				  ?>).
													</p>
														<?php
			
				  $trip_date_tomorrow = date('l', strtotime($tomorrow));
			?>
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
											
												  		<?php

		include 'connect.php';
		$username=$_SESSION['username'];
		$select14=mysqli_query($db,"SELECT *  FROM  assigned_routes WHERE username='$username'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$active =$get134['active']; 
			$active_tab =$get134['active_tab']; 
			$fare_price =$get134['fare_price']; 


		?>
  <a class="nav-link mb-2 <?php echo $active; ?>" id="v-pills-<?php echo $id;?>-tab" data-bs-toggle="pill" href="#v-pills-<?php echo $id;?>" role="tab" aria-controls="v-pills-<?php echo $id;?>" aria-selected="<?php echo $selected; ?>"><?php  echo "$start_route to $end_route";?></a>
 
                                                   <?php
}}
	   ?>    
												
												</div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> <!-- end col-->

                                    <div class="col-xl-9">
                                        <div class="card card-h-100">
                                            <div class="card-body">
<div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
											  		<?php
		include 'connect.php';
		$username=$_SESSION['username'];
		$select14=mysqli_query($db,"SELECT *  FROM  assigned_routes WHERE username='$username'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$active =$get134['active']; 
			$active_tab =$get134['active_tab']; 
			$fare_price =$get134['fare_price'];
			$currency_symbol =$get134['currency_symbol'];
			$detail =$get134['detail']; 

		

		

		?>
<div class="tab-pane fade <?php echo $active_tab;?>" id="v-pills-<?php echo $id;?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $id;?>-tab">
<div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title"><?php 
										echo "$start_route to $end_route";?></h4>
                                     </div><br>
             					 <?php
										
		include 'connect.php';
		$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow'  and route_id ='$route_id' and status='active' ORDER BY day ");
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

			if($trip_day == $today_day){
				$trip_date = $day;
			
			}	else{
				$trip_date = $tomorrow;
				
			}
			
					$select1730=mysqli_query($db,"SELECT *  FROM  vehicle Where name='$bus' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $seats_bus= $get13730['seats'];



}
}
			
			$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$bus' and trip_date='$trip_date'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
	   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $seats= $get1373['seats'];


}
}else{
$seats= $seats_bus;	
}
		


				
?>
                                                                               <div class="card border shadow-none">
                                                            <div class="card-body">
															
																						 <ul class="wizard-nav ">
										<li class="wizard-list-item">
                                                <div class="list-item">
                                               <?php 
											 echo "<span style='color:white;font-weight:bold;'>$start_route</span>";
											   ?> &nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp
                                                </div>
                                            </li>

																						 <?php
$select14=mysqli_query($db,"SELECT *  FROM   sub_route_list  WHERE start_route='$start_route' AND end_route='$end_route' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    
			$location =$get134['location'];
			

																						 ?>

	                                            <li class="wizard-list-item">
                                                <div class="list-item">
                                               <?php 
											   
											   	 
												    echo "$location &nbsp&nbsp&nbsp&nbsp";
											
											   ?>
                                                </div>
                                            </li>
                                         
											<?php
}}
											?>
											                                            <li class="wizard-list-item">
                                                <div class="list-item">
&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp<?php
										   echo "<span style='color:white;font-weight:bold;'>$end_route</span>";
 ?>
                                                </div>
                                            </li>

                                        </ul><br>
															
															
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
                                                                        <h5 class="font-size-18 mb-1"><a href="projects-overview.html" class="text-dark"><?php echo date("l jS \of F Y", strtotime($trip_date));?> -  <?php echo number_format($fare_price,2)?> <?php echo $currency_symbol;?></a></h5>
                                                                        <p class="text-muted mb-0">
													
														  <p class="text-muted">
														<?php echo $bus;?> - <?php echo base64_decode($detail);?>
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
                                                                                <div class="badge badge-soft-primary font-size-12"><?php echo $departure;?></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Arrival time</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $arrival;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Available seats</p>
                                                                                					   <div class="flex-grow-1"style="width:200px;">
                                                          <div class="badge bg-warning font-size-12"><?php  if($seats == $seats_bus){
															  echo $seats_bus;
															  }else{
																  echo $seats_bus-$seats;
																  }?> seats available</div>
                                                            <div class="progress animated-progess custom-progress">
                                                                <div class="progress-bar" role="progressbar" style="width:	<?php
																$seats_bus;
																$seats_remain= $seats_bus- $seats;
																$new_width = ($seats_remain / 100) * $seats_bus;
																$percent = 100-round($new_width,2);
																echo "$percent%";?>" aria-valuenow="<?php echo "$percent%";?>" aria-valuemin="<?php echo "$percent%";?>" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
													
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                         <div>
                                                                        <a href="seat_selection.php?id=<?php echo $id2;?>&trip_date=<?php echo $trip_date;?>&route_id=<?php echo $route_id;?>">
                                  
                                    <button type="button" class="btn btn-outline-primary">Book Seats</button>
									</a>
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
																	<option value="">Choose Ending Point</option>
                                                                  <?php echo $start_route2; ?>
                                                                </select>
                                                                <div class="invalid-feedback">Please select a valid event category</div>
                                                            </div>
                                                        </div>
														     <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Trip date</label>
                                                                 <input class="form-control dateselect" type="text"  id="example-date-input" placeholder="mm/dd/yyyy">
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
     <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                                             <div class="offcanvas-header">
                                                <h5 class="offcanvas-title" id="offcanvasExampleLabel"></h5>
                                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                           <div id="load_pinned"></div>
                                            </div>
                                        </div>

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
