
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
				  $trip_date_tomorrow = date('l', strtotime($tomorrow));
?>
 <head>
   <meta charset="utf-8" />
        <title>Route</title>
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
     		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>   
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">
              <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

<!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" type="text/css" href="css/buttons.css">
	  <link rel="stylesheet" type="text/css" href="select_css.css">
   </head>



         <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php 
	 include 'top_header.php';
	 include 'include_location_names.php';
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
                        <div class="col-xl-8">
                            <div class="card card-h-100">
                                <div class="card-body">
                                									 
                          <table>
						  <tr>
						  <td class="table_padding" >


<label class="font-size-14" for="">From</label><br>
<select class="js-example-basic-single td_with" name="state" >
  <option >Choose starting Point</option>
    <?php echo $start_route2; ?>
 
</select>
						 </td>  <td class="table_padding" >
	<label class="font-size-14" for="">To</label><br>					   
<select class="js-example-basic-single td_with" name="state" >
  <option >Choose Ending Point</option>
    <?php echo $start_route2; ?>
</select>
</td>
<td class="table_padding" >
	<label class="font-size-14" for="">Go date</label><br>					   

 <input class="form-control dateselect" type="text"  id="example-date-input" style="margin-bottom:15px;"placeholder="mm/dd/yyyy">

 </td>
 
 <td class="table_padding"><label class="font-size-14" for="">Return date</label><br>					   
 <input class="form-control dateselect" type="text"  id="example-date-input" style="margin-bottom:15px;"placeholder="mm/dd/yyyy">

                                              
						  </td><td class="table_padding">				   
 <br>
                                    <button type="button" id="search" class="btn  btn-soft-light" style="margin-bottom:10px;"><i class="uil uil-search-alt"></i> Search</button>
                                              
						  </td>
						  </tr>
						  </table>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-nowrap mb-0 align-middle table-check">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="rounded-start" style="width: 15px;">
                                                  Route 
                                                    </th>
                                                    
													<th>Bus</th>
													<th>Dept</th>
                                                    <th>Arrival</th>
                                                    <th>Cost</th>
                                                    <th>Available</th>
                                              <th >Book</th> 
											  <th>lock</th> <th ></th>
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
                                            <!-- end thead -->
                                            <tbody>
                                           
												  		<?php
	$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow'   and status='active' ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
			$route_id =$get['route_id'];
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
			
$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$fare_price =$get134['fare_price'];
			$currency_symbol =$get134['currency_symbol'];
			$detail =$get134['detail']; 
			$route_name =$get134['route_name']; 

		

}}
			
			
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
     <tr>                                                   
												   <td>
                                                   <?php echo "".base64_decode($detail)." (".$route_name.")";?>
                                                    </td>   <td>
                                                   <?php echo $bus;?>
                                                    </td>
                                                    <td class="fw-medium">
                                                        <?php echo $departure;?>
                                                    </td>
                                                    <td>
                                                  <?php
												  echo $arrival;
												  ?>
                                                    </td>

                                                    <td>
                                                 <?php echo ''.number_format($fare_price).' '.$currency_symbol.''?>
                                                    </td>
                                                    <td>
                                                   <?php echo $seats;?>
                                                    </td>
                                                    <td>
 <button type="button" class="btn btn-outline-primary">Book </button>                                                      </td>

                                                    <td>
                                                 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch3" switch="bool"  />
                                                            <label for="square-switch3" data-on-label="Open "
                                                                data-off-label=" Lock" class="mb-0"></label>
                                                        </div>
                                                    </td>            <td>
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
													
<?php }} ?>
													
                                                
                                                <!-- end /tr -->
                                               
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
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

</script>
