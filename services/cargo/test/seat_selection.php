<?php
$id=$_GET['id'];
$trip_date=$_GET['trip_date'];
$route_id=$_GET['route_id'];

include 'connect.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	
		

		
	}
}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$active_date =$get134['active_date'];

		

		
	}
}
?>
<!doctype html>
<style>
iframe{
	height:1000px;
	width:100%;
}
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
        <title>Routes and Trips</title>
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

            
<div id="rout_day_id"style="display:none;"> <?php echo $id;?></div>
		  <div id="bus"style="display:none;"><?php echo $bus;?></div>
<div id="route_id" style="display:none;"><?php echo $route_id;?></div>

		 <div id="trip_date" style="display:none;"><?php echo $trip_date;?></div>
		  <div id="cost"style="display:none;"><?php echo $fare_price;?></div>
		  
		  
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
                                    <h4 class="mb-0">Seat Selection</h4>
                              <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">Seat Selection</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						<?php

date_default_timezone_set('Africa/Dar_es_Salaam');
$today_date = date('m/d/Y', time());
$active_date2 =  date("m/d/Y", strtotime($trip_date."+$active_date days"));

$today_day = date('d', strtotime($today_date));
$today_month = date('m', strtotime($today_date));
$today_year = date('y', strtotime($today_date));

$trip_date_day = date('d', strtotime($trip_date));
$trip_date_month = date('m', strtotime($trip_date));
$trip_date_year = date('y', strtotime($trip_date));

$active_date2_day = date('d', strtotime($active_date2));
$active_date2_month = date('m', strtotime($active_date2));
$active_date2_year = date('y', strtotime($active_date2));


if($today_year == $trip_date_year ){
	

	
	if($today_month == $trip_date_month){
			$date_difference=$trip_date_day-$today_day;
	
if($date_difference >=0){

	
	
	$datetime1 = date_create($today_date);
$datetime2 = date_create($trip_date);




$interval = date_diff($datetime2, $datetime1);
$interval =$interval->format('%d');

if($interval==0){
		echo "partical free $date_difference";

}	else {
	echo "very free $date_difference";
}








}else{
	$now_time = date('h:i A', time());
	$time_now24hours  = date("H:i", strtotime($now_time));
   $select145=mysqli_query($db,"SELECT *  FROM  sub_route WHERE main_route_no='$route_id' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $active_day =$get1345['active_day'];
		$differnce =  $active_day + $date_difference;
			$a_time =$get1345['a_time'];
			$a_time24hours  = date("H:i", strtotime($a_time));
			$active_sub_route_date =  date("m/d/Y", strtotime($trip_date."+$active_day days"));
	if($time_now24hours > $a_time24hours){
			echo "
		only for half trip<br>
		$differnce differnce of $active_day and $date_difference";
		
echo "<br>time is $time_now24hours($now_time)   Not active kabisa $a_time24hours($a_time)";	
	}else{
	echo "<br>time is $time_now24hours($now_time)   active until $a_time24hours($a_time)";	
	}
	


		
	}
}
}
	}else if($today_month < $trip_date_month){
		echo "this is not the same month<br>";
	}
	
	
}else if($trip_date_year > $today_year){
	
	echo "this year is not the same<br>";
}else if($trip_date_year < $today_year){
	
	echo "closed<br>";
}






?>
      
                     <div class="d-xl-flex">
                            <div class="w-100">
                                <div class="d-md-flex">
                                    <div class="card filemanager-sidebar me-md-3">
                                        <div class="card-body">
										 <div class="text-center">
										 <h5 class="font-size-15 mb-4">BOOKED LIST </h5></div>
         <div id ="load_seat_table"></div> 
                                        </div><!-- end cardbody-->
                                    </div><!-- end card -->
                                    <!-- filemanager-leftsidebar -->
            <div class="col">
                                    <div class="w-100">
                                        <div class="card">
                                            <div class="card-body">
											<div class="row">

											  <div class="col">
                            <h5 class="font-size-15 mb-4">SEATNG CHART 
							   <div id="myProfile"></div>
							</h5>   


<div id="load_table"></div>
</div>
											  <div class="col">
											   <BR> <BR>
  <div id="load_status"></div>


    </div>
</div>
        
                
                                            </div><!-- end cardbody-->
                                        </div><!-- end card -->
                                    </div><!-- end w-100 -->
                                
								</div>
								

								</div>
                            </div>

                            <div class="card filemanager-sidebar ms-lg-3">
                                <div class="card-body">
                                    <div class="text-center">
                                        <h5 class="font-size-15 mb-4">BOOKING INFO</h5>
										
										  <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                            <li class="nav-item" style="display:none;">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#home2" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                    <span class="d-none d-sm-block">Direct ticket</span> 
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#profile2" role="tab">
                                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                    <span class="d-none d-sm-block">Partway</span> 
                                                </a>
                                            </li>
                                       
                                       
                                        </ul>
										                   <div class="tab-content p-3 text-muted">
                                            <div class="tab-pane active" id="home2" role="tabpanel">
                                           <div id ="load_booking"></div> 
                                            </div><!-- end tab pane -->
                                            <div class="tab-pane" id="profile2" role="tabpanel">
                                           <div id ="load_booking_sub"></div> 
                                            </div><!-- end tab pane -->
                                         <!-- end tab pane -->
                                         <!-- end tab pane -->
                                        </div>
                                                 


                                    
                                    </div>

               
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>

			  <!-- end page title -->

       
                        
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
   
		                           <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Seat no</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     <div id="first_model_area">
													
													</div>
                                            <div id="second_model_area"> 
													 </div>
                                         

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




                                        <div class="modal fade exampleModalFullscreen" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>


        <!-- JAVASCRIPT -->
		<script src="js/vendor/jquery-1.12.4.min.js"></script>
		        <script src="assets/js/pages/notification.init.js"></script>

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

<?php $route_id = $_GET['route_id'];?>
        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
		<script src='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'>
</script>
<script>
$(document).ready(function(){
	$("#load_table").load("booking_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>");
	$("#load_seat_table").load("load_seat_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>  ");
	$("#load_booking").load("load_booking.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?> ");
	$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?> ");

$("#load_status").load("load_status.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>");


});

</script>
    </body>
</html>
