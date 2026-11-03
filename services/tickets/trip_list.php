<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
//index.php
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT name FROM locations_names ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
 $seats=0;
if (!isset($_GET['date']) || trim((string) $_GET['date']) === '') {
	header('Location: /tickets/schedule.php');
	exit;
}
?>

<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Search</title>
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
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">

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
										  		<?php
		include 'connect.php';
		 

		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$start_location' AND end_route='$ending_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
		$fare_price =$get134['fare_price']; 
			$detail =$get134['detail']; 

		$trip_date = date('l', strtotime($date));
		
		
		
	
}}else{
	
	
			$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE start_route='$start_location' AND end_route='$ending_location' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $id =$get1345['id'];
			$start_route =$get1345['start_route'];
			$end_route =$get1345['end_route'];
			$route_id = $get1345['main_route_no']; 
		$fare_price =$get1345['fare_price']; 
			$detail =$get1345['detail']; 

		$trip_date = date('l', strtotime($date));
		
		
		
	
}}
	
}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    
			$start_route_le =$get134['start_route'];
$end_route =$get134['end_route'];

}}


		?>
		
		
		     					 <?php
										
		include 'connect.php';
		$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date'  and route_id ='$route_id' and status='active'  ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $id2 =$get['id'];
			$arrival =$get['arrival'];
			$departure =$get['departure'];
			$time =$get['12hours'];
			$trip_day =$get['day']; 
			$bus =$get['bus']; 
			$route_id =$get['route_id']; 
			
			$now_time = date('h:i A', time());

									$select1730=mysqli_query($db,"SELECT *  FROM  vehicle Where name='$bus' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $seats_bus= $get13730['seats'];



}
}
				$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$bus' and trip_date='$date_now'");
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
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
																						 <ul class="wizard-nav ">
										<li class="wizard-list-item">
                                                <div class="list-item">
                                               <?php 
											   if($start_location==$start_route){
												   echo "<span style='color:white;font-weight:bold;'>$start_route</span>";
											   }else{
												   echo $start_route;
											   }
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
											   
											   	   if(($ending_location== $location)or ($start_location== $location)){
												    echo "<span style='color:white;font-weight:bold;'>$location &nbsp&nbsp&nbsp&nbsp</span>";
											   }else{
echo "<span >$location &nbsp&nbsp&nbsp&nbsp</span>";
											   }
											   ?>
                                                </div>
                                            </li>
                                         
											<?php
}}
											?>
											                                            <li class="wizard-list-item">
                                                <div class="list-item">
&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp<?php
											   if($ending_location== $end_route){
												    echo "<span style='color:white;font-weight:bold;'>$end_route</span>";
											   }else{
											echo $end_route;

											   }

 ?>
                                                </div>
                                            </li>

                                        </ul><br>
																	
                                                                        <h5 class="font-size-18 mb-1"><a href="projects-overview.html" class="text-dark"><?php echo date("l jS \of F Y", strtotime($date));?> -  <?php echo number_format($fare_price,2)?> Tsh</a></h5>
                                                                        <p class="text-muted mb-0">
								
										<p class="text-muted">
														<?php echo $bus;?> - <?php echo base64_decode($detail);?>
														  </p>
																		</p>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <div class="d-flex gap-2">
                                                                            <div>
																			<?php
																			$select1=mysqli_query($db,"SELECT *  FROM  pin WHERE username='".$_SESSION['username']."' and route_id='$route_id' and date='$date_now'  and day='$trip_day'");
                                                                      $select_num1=mysqli_num_rows($select1);
                                                                   if($select_num1 ==0){
																	   ?>
											<span class="response<?php echo $id;?>"></span>  <img style="cursor: pointer;"id="pin<?php echo $id;?>"src="images/pin.png">

																	   <?php
																   }else{
																	   ?>
									<span class="badge badge-soft-primary">Saved</span>

																	   <?php
																	   
																   }
																			
																			?>
																			
																			
                                                                            </div>
                                                                        
                                                                        </div>
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
                                                                                <div class="badge badge-soft-primary font-size-12"><?php echo $departure;?> </div>
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
                                                                                   <a href="seat_selection.php?id=<?php echo $id2;?>&trip_date=<?php echo $date_now;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo $start_location?>&ending_location=<?php echo $ending_location;?>">
             
                                    <button type="button" class="btn btn-outline-primary">Book Seats</button></a>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>
                                                        </div>
														<span style="display:none;"id="day<?php echo $id?>"><?php echo $trip_date?></span>
														<span style="display:none;"id="date<?php echo $id?>"><?php echo $date_now?></span>
														<span style="display:none;"id="route_id<?php echo $id?>"><?php echo $route_id?></span>
														<span style="display:none;"id="route_day_id<?php echo $id?>"><?php echo $id2?></span>

														<script>
														$(document).ready(function(){
															
												$("#pin<?php echo $id;?>").click(function(){
											var day = $("#day<?php echo $id?>").text();
															var date = $("#date<?php echo $id?>").text();
															var route_id = $("#route_id<?php echo $id?>").text();
															var route_day_id = $("#route_day_id<?php echo $id?>").text();
							$(".response<?php echo $id;?>").text("saving...");								
															 $.ajax({
				url:"insert_pin.php",
				method:"POST",
				data:{day:day,date:date,route_id:route_id,route_day_id:route_day_id},
				success:function(response){
				$(".response<?php echo $id;?>").text("");		
if($.trim(response) == "succesfull"){
								  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });

}else{
	swal("", "already saved !", "warning");

}
	}
			
			});
												});					
														});
											
														</script>

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
                                        </div>
                                    </div> <!-- end col -->
                                </div> 

            
                                <!-- end modal-->
                            </div>
                        </div>
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
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

      
        <!-- Calendar init -->
  
		   		<script src="search_routes.js"></script>
		<script src="js/sweetalert.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>
</script>
<script>
$(document).ready(function(){
 	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "mm/dd/yyyy",
	
		//container: console.log($(this).datepicker())
}); 

   document.getElementById("example-date-input").min = new Date().getFullYear() + "-" +  parseInt(new Date().getMonth() + 1 ) + "-" + new Date().getDate()

});
</script>

      </script>
    </body>
</html>
