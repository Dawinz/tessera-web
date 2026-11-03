<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$id=$_GET['id'];
$trip_date=$_GET['trip_date'];
$route_id=$_GET['route_id'];
$start_location=$_GET['start_location'];
$ending_location=$_GET['ending_location'];

include 'connect.php';
$tenantSql = tessera_sql_tenant_eq();
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' AND $tenantSql ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id_id =$get134['id']; 
			$route_id =$get134['route_id'];
			$bus =$get134['bus'];
	
			$route_active_time =$get134['route_active_time'];

		
	}
}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' AND $tenantSql ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$active_date =$get134['active_date'];
		

		

		
	}
}
?>
<?php
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
.swal2-container.swal2-center>.swal2-popup{
  background-color: #121212 !important;
}
.swal2-html-container, .swal2-title{
  color: white !important;
}

</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Seat selection'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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
        <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
		  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'><link rel="stylesheet" href="./style.css">

<link rel="stylesheet" type="text/css" href="datatable/datatable.css">
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
    	     <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />

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
               

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Seat Selection  - <span id="bus_name_to_change"><?php echo $bus;?></span></h4>
                              <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">Seat Selection</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						<div id="booking_timer"></div>
						
						<script>
						$(document).ready(function(){
						});
						
						$(window).load(function(){
	$("#booking_timer").load("booking_timer.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&route_id=<?php echo $route_id;?>");
setInterval(
function()
{
	$("#booking_timer").load("booking_timer.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&route_id=<?php echo $route_id;?>");
}, 300000);

});
						
						
						</script>
						
						
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
	
$now_time = date('h:i A', time());
$now_time2  = date("H:i", strtotime($now_time));
$departure2  = date("H:i", strtotime($route_active_time));
if($now_time2 < $departure2){
	?>
			<script>
$(document).ready(function(){
	Swal.fire({title:"Trip Day",icon:"info",
	

	html:'complete book until <?php echo "  $route_active_time";?>',
	showCloseButton:!0,

})});

</script>
	
	<?php

}else{


	
	
$trip_date_session=$_GET['trip_date'];
$route_id_session=$_GET['route_id'];

	
?>
			<script>
$(document).ready(function(){
$("#nav_direct").hide();
$("#home2").hide();
$("#profile2").addClass( "active" );
	Swal.fire({title:"Trip Day",icon:"info",
	

	html:'partical trips only ',
	showCloseButton:!0,

})});

</script>

<?php
	
}
		?>

		
		<?php

}	else {

}








}else{
	$now_time = date('h:i A', time());
	$time_now24hours  = date("H:i", strtotime($now_time));
   $select145=mysqli_query($db,"SELECT *  FROM  sub_route WHERE main_route_no='$route_id' ");
$select_num145=mysqli_num_rows($select145);

?>

<script>
$(document).ready(function(){
	$("#nav_direct").hide();
	$("#home2").hide();
$("#profile2").addClass( "active" );
	Swal.fire({title:"Sub-routes",icon:"info",
	


	html:'<?php
	$id_session=$_GET['id'];
	if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $id_sub =$get1345['id'];
			$active_day =$get1345['active_day'];
			$start_route =$get1345['start_route'];
			$end_route =$get1345['end_route'];
		$differnce =  $active_day + $date_difference;
			$a_time =$get1345['d_time'];
			$a_time24hours  = date("H:i", strtotime($a_time));
			$active_sub_route_date =  date("m/d/Y", strtotime($trip_date."+$active_day days"));
	if($differnce >=1 ){
		
			echo "$start_route to $end_route ---> available in  $differnce days only<br>";	


	}else if($differnce ==0 ){
		if($time_now24hours > $a_time24hours){
		

	
		
		
		
echo "$start_route to $end_route --> not available <br>";	
	}else{
	echo "$start_route to $end_route ---> available only today<br>";	
	}
	} else if($differnce < 0 ){
		
		
$select13=mysqli_query($db,"SELECT *  FROM  temp WHERE trip_date='$trip_date' AND route_id='$id_session' and route_day_id='$route_id' and sub_route_name='$start_route'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 ==0){
	
		$insert=mysqli_query($db,"INSERT INTO temp 
(trip_date,route_id,route_day_id,sub_route_id,sub_route_name) VALUES 
('$trip_date','$id_session','$route_id','$id_sub','$start_route')");
	
}else{
	
}
		
echo "$start_route to $end_route $id_session --> not available <br>";	


	}
	


		
	}
	
}
	?>',
	showCloseButton:!0,

})});

</script>

<?php



}
	}else if($today_month < $trip_date_month){
		echo "";
	}
	
	
}else if($trip_date_year > $today_year){
	/////next month
	echo "";
}else if($trip_date_year < $today_year){
	
	?>
	
			<script>
$(document).ready(function(){
let timerInterval

Swal.fire({
  title: ' close alert!',
  html:
    ' in <strong></strong> 4 seconds.',
	
  timer: 5000,
  didOpen: () => {
    const content = Swal.getHtmlContainer()
    const $ = content.querySelector.bind(content)

    const stop = $('#stop')
    const resume = $('#resume')
    const toggle = $('#toggle')
    const increase = $('#increase')

    Swal.showLoading()

    function toggleButtons () {
      stop.disabled = !Swal.isTimerRunning()
      resume.disabled = Swal.isTimerRunning()
    }

    stop.addEventListener('click', () => {
      Swal.stopTimer()
      toggleButtons()
    })

    resume.addEventListener('click', () => {
      Swal.resumeTimer()
      toggleButtons()
    })

    toggle.addEventListener('click', () => {
      Swal.toggleTimer()
      toggleButtons()
    })

    increase.addEventListener('click', () => {
      Swal.increaseTimer(5000)
    })

    timerInterval = setInterval(() => {
      Swal.getHtmlContainer().querySelector('strong')
        .textContent = (Swal.getTimerLeft() / 1000)
          .toFixed(0)
    }, 100)
  },
  willClose: () => {
    clearInterval(timerInterval)
	window.location="routes.php";
  }
})


});

</script>
	<?php
	

}






?>
    
  <div class="row">
    <div class="col" style="">
    <div class="card filemanager- me-md-3">
									  <div class="card-body">
                                    <div class="text-center">
                                        
										 <div style="display:none;"class="spinner-border text-warning m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
									
									<div id ="load_booking"></div> 
                                                 


                                    
                                    </div>

               
                                </div>
                                        <!-- end cardbody-->
                                    </div>
    </div>
    <div class="col">

     	<div class="card ">
		<div class=" filemanager- me-md-3">
									  <div class="card-body">
									  <div class="text-center">
	                            <h5 class="font-size-18 mb-3">Seat Chart - <?php echo date("l jS \of F Y", strtotime($trip_date));?></h5>
</div>
<hr/>
<div id="load_table"></div>
</div>
    </div></div>
    </div>
    <div class="col">
	<div class="card ">
		<div class=" filemanager- me-md-3">
									  <div class="card-body">
	 		 <div class="text-center">
										
										  <div style="display:none;"class="spinner-border text-warning m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
											
											
         <div id ="load_seat_table"></div> 

</div>
    </div>
  </div>   </div>
  </div>

			  <!-- end page title -->

		                    
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
  <!-- end slimscroll-menu-->
        </div>
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
										
										
										         <div id="myModal2" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Select Bus Name</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
											 <p>Do you want to change the vehicle</p>
                                       				<div class="mb-3">
                                                                <label class="form-label">Select Bus Name</label>
  <select class="form-select" id="vehicle_name_update_date"
                                                aria-label="Floating label select example">
												<option><?php echo $bus;?></option>
                                                    <?php echo $vehicle; ?>

                                            </select>                                                                 
                                                            </div>
 <button id="change_date" class="btn btn-primary" type="button" id="button-email">Change Vehicle<span id="ti_response2"></span> <i class="uil  uil-arrow-random "></i></button>

															<div style="display:none;"id="idRoute"><?php echo $route_id_id;?></div>
															<div style="display:none;"id="bus_route"><?php echo $bus;?></div>
															<div style="display:none;"id="trip_date_change"><?php echo $trip_date;?></div>
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
<div id="load_model_change_print"></div>
  <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
                                        aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Transfer Seat</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
												<div style="display:none;" id="prev_id"></div>
							  
                                          <div class="input-group countdown-input-group mx-auto my-5">
										 
                                    <input type="number" id="ticket_no"class="form-control border-light shadow" placeholder="Enter ticket no">
                                    <button id="check_transfer" class="btn btn-primary" type="button" id="button-email">Transfer <span id="ti_response"></span> <i class="uil  uil-arrow-random "></i></button>
                                </div>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div>
									
        <!-- JAVASCRIPT -->
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
        <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>


        <!-- Calendar init -->
        <script src="assets/js/pages/calendar.init.js"></script>
       

  <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <!-- Sweet alert init js-->
        <script src="assets/js/pages/sweet-alerts.init.js"></script>
<?php $route_id = $_GET['route_id'];?>
        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
		
</script>
				<script src="change_trip_date.js"></script>
	<script src="assets/js/alertify.min.js"></script>
			<script type="text/javascript" src="lib/jquery-backward-timer.min.js"></script>


<span></span>
<script>


$(document).ready(function(){
	
						$("#load_model_change_print").load("load_model_change_print.php");

	$("#load_table").load("booking_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_seat_table").load("load_seat_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_booking").load("load_booking.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");

$("#load_status").load("load_status.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");


$("#check_transfer").click(function(){
var ticket_no = $("#ticket_no").val();
var prev_id = $("#prev_id").text();

$("#load_model_change_print").load("load_model_change_print.php");

if(ticket_no==""){
	 swal("", "Please Enter Ticket No !", "warning");

			 control.disabled="disabled";	
}
$("#ti_response").text("checking....");
  $.ajax({
				url:"check_ticket.php",
				method:"POST",
				data:{ticket_no:ticket_no,prev_id:prev_id},
				success:function(response){
					if($.trim(response) == "past"){
	$("#ti_response").text("");
 swal("Date", "Check date and proceed !", "warning");

						}else if($.trim(response) == "nfound"){
				swal("Ticket not found", "cannot find ticket no!", "warning");	
				$("#ti_response").text("");
					 }else{
						 
										$("#ti_response").text("");
						$('.bs-example-modal-center').modal('hide');
	$("#load_table").load("booking_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_seat_table").load("load_seat_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_booking").load("load_booking.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
$("#load_status").load("load_status.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");

$('.exampleModalFullscreen_change').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print_change");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket.php?id="+ticket_no;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	

$("#ticket_no").val("");

	 
					 }
					
					
	

	}
			
			});


});


$(document).on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
	var ticket_no = $("#ticket_no").val();
var prev_id = $("#prev_id").text();

$("#load_model_change_print").load("load_model_change_print.php");

if(ticket_no==""){
	 swal("", "Please Enter Ticket No !", "warning");

			 control.disabled="disabled";	
}
$("#ti_response").text("checking....");
  $.ajax({
				url:"check_ticket.php",
				method:"POST",
				data:{ticket_no:ticket_no,prev_id:prev_id},
				success:function(response){
					if($.trim(response) == "past"){
	$("#ti_response").text("");
 swal("Date", "Check date and proceed !", "warning");

						}else if($.trim(response) == "nfound"){
				swal("Ticket not found", "cannot find ticket no!", "warning");	
				$("#ti_response").text("");
					 }else{
						 
										$("#ti_response").text("");
						$('.bs-example-modal-center').modal('hide');
	$("#load_table").load("booking_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_seat_table").load("load_seat_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_booking").load("load_booking.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
$("#load_status").load("load_status.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");

$('.exampleModalFullscreen_change').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print_change");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket.php?id="+ticket_no;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	

$("#ticket_no").val("");

	 
					 }
					
					
	$("#load_table").load("booking_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo $start_location;?>&ending_location=<?php echo $ending_location;?>");
	$("#load_seat_table").load("load_seat_table.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo $start_location;?>&ending_location=<?php echo $ending_location;?> ");
	$("#load_booking").load("load_booking.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo $start_location;?>&ending_location=<?php echo $ending_location;?> ");
$("#load_status").load("load_status.php?id=<?php echo $id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo $start_location;?>&ending_location=<?php echo $ending_location;?>");


	}
			
			});  
  }
});
});

</script>
    </body>
</html>

