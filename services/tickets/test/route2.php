<!doctype html>
  <style>
		  .dt-button.red {
   color: #fff;
  background:none;
   background-color: #0379BB;
   border-radius: 05px;
   border:none;
    }
  .dataTables_filter input {
        width: 250px;
        height: 32px;
        background: #fcfcfc;
        border: 1px solid #aaa;
        border-radius: 5px;
    
        text-indent: 10px;
    }
 
    .dataTables_filter .fa-search {
        position: absolute;
        top: 10px;
        left: auto;
        right: 10px;
    }
.dataTables_filter {
    display: none;
}
#myInputTextField
{
width:200px;
margin-bottom:4px;
display:none;
}#search_user
{
position: relative; 
left:705px;
}
.toggle_image{
	
	widht:100%;
	background:#043A5A;
	text-align:center;
	color:black;
	ebkit-box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  -moz-box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
}
.trip_loader_panel
{
	min-width:1100px;
	
}

#load_seat_chart{
	ebkit-box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  -moz-box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  padding-top:20px; padding-bottom:20px;
  width: fit-content;
 
}
	  </style>
	    <style>
  #resizable { width: 150px; height: 150px; padding: 0.5em; }
  #resizable h3 { text-align: center; margin: 0; }
  </style>
  
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
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Booking and Routes</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

 <?php
 include 'include_scripts.php';
 ?>
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
                   
						              <div class="row ">
									  <div class="col trip_loader_panel" style="">
									               <div class="card "  >
                                <div class="card-body resizable " style="">
                                									 
                          <table>
						  <tr>
						  <td class="table_padding" >



<select class="js-example-basic-single td_with" id="starting_location" >

    <?php echo $start_route2; ?>
 
</select>
						 </td>  <td class="table_padding" >
					   
<select class="js-example-basic-single td_with" id="ending_location" >
  <option >Choose Ending Point</option>
    <?php echo $start_route2; ?>
</select>
</td>
<td class="table_padding" >
					   

 <input class="form-control dateselect" type="text"  id="starting_date" style="caret-color: transparent;margin-bottom:4px;cursor: pointer;"placeholder="mm/dd/yyyy" value ="<?php echo $tomorrowmo;?>" onkeydown="return false">

 </td>
 
 <td class="table_padding">				   
 <input class="form-control dateselect ending_date" type="text"  id="example-date-input" style="margin-bottom:4px; caret-color: transparent;cursor: pointer;"placeholder="Return date"onkeydown="return false" style="">

                                              
						  </td><td class="table_padding">				   
 										<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control" placeholder="Search....">
		 </form>
      </div> 
                                              
						  </td>
						  </tr>
						  </table>
						  <div class="accordion-content">
						  <div id="load_route_content">
						    <div>
                                                   
                                                    <div class="placeholder-glow" >
                                                        <span class="placeholder col-12"style="height:300px"></span>
                                                    </div>
                                                </div>
						  </div>
		
                                  <!-- end card header -->
                                   
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-pills nav-justified bg-light dnone" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#navpills2-home" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                    <span class="d-none d-sm-block">Go</span> 
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#navpills2-profile" role="tab">
                                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                    <span class="d-none d-sm-block">Return</span> 
                                                </a>
                                            </li>
                                       
                                          
                                        </ul><br>
        
                                        <!-- Tab panes -->
                                        <div class="tab-content ">
                                            <div class="tab-pane active" id="navpills2-home" role="tabpanel">
                                            
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-profile" role="tabpanel">
                                                <p class="mb-0">
                                              
                                                </p>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-messages" role="tabpanel">
                                            <div></div>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-settings" role="tabpanel">
                                                <p class="mb-0">
                                           
                                                </p>
                                            </div><!-- end tab pane -->
                                        </div>
                                  
                       
                            </div>
                                </div>								
								<span class="accordion-toggle toggle_image" style="" > <img class="down_image"src ="images/up-arrows5.png"> </span>


                            </div>
							
							<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
  <div class="carousel-indicators"style="position:relative;">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
	<div class="card">
		
				<div class="card-body">

<audio  id="pop" >
  <source src="audio/sound.mp3" type="audio/mpeg">
</audio>


					<form method="post" id="insert_form">
						<div class="table-repsonsive">
							<span id="error"></span>
							<table class="table table-bordered" id="item_table">
								<tr>
									<th>firstanme</th>
									<th>Surname</th>
									<th>Mobile</th>
									<th>Gender</th>
									<th>Age </th>
									<th>Verification</th>
									<th>foc</th><th>Trans</th>
									
									
								</tr>
							</table>
							<div align="center">
								 
								       <button type="submit" class="btn btn-success check_cred" 
                                               >Large modal</button>
						   <div class="modal fade bs-example-modal-lg" id="modal_check" tabindex="-1" role="dialog"
                                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myLargeModalLabel">Confirmation Panel</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                              <h4 class="font-size-16 mb-0">You are about to complete booking proccess please clarify all the information first before clicking book .</h4><br>
       <div class="confm_text"></div>
	   <h3 class="">$<span data-plugin="counterup">3.85k</span><span
                                                        class="text-muted d-inline-block fw-normal font-size-15 ms-2">Total</span>
                                                </h3>
												        <div class="col-xl-6">
												      <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                                                style="height: 100px"></textarea>
                                            <label for="floatingTextarea2">Foc details</label>
                                        </div></div>
										
                                                    </div>
													 <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary ">Save changes</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
						
							</div>
							
						</div>
						  
						
					</form>
													<input type="submit" name="submit" id="submit_button" class="btn btn-primary" value="Insert" />

				</div>
	</div>
    
    </div>
    <div class="carousel-item">
    <div class="card">
     <div class="table-responsive">
	 <div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="search_user" class="form-control" placeholder="Search....">
		 </form>
      </div>
                                            <table class="table mb-0" id="passenger_info"style="width:100%;">
                                                              <thead class="table-light">
<tr>                 

<th>Seat id</th>
<th>Bus</th>
<th>seat no</th>
<th>Fullname</th>
<th>Cost</th>
<th>Booking time </th>

<th>start_location </th>
<th>ending_location</th>
<th>Issure</th>

</tr>
				
                          </thead>
						                 	 
                                          <!-- end tbody --> 
                                            </table><!-- end table --> 
                                        </div></div>
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5></h5>
        <p></p>
      </div>
    </div>
  </div>

</div>
							
									  </div> <div class="col">
									  
									               <div class="card  "style="resize: none;">
										      <div class="alert alert-danger   border-0 d-flex align-items-center" role="alert">
                                        <i class="uil uil-exclamation-triangle font-size-16 text-primary  me-2"></i>
                                        <div class="flex-grow-1 text-truncate">
                                            Your free trial expired in <b>21</b> days. 
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="pricing-basic.html" class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                                        </div>
                                    </div>
                                        <!-- Nav tabs -->
										<div class="row">
                              	<div class="col"style="min-width:350px;">
											<div id= "load_seat_chart"class="card" style=""></div>
											</div>
											<div class="col"style="p">kjh</div></div>
                            </div>
									  </div>
                 
                    </div>
	
		  <!-- end page title -->



<div class="display_none country"><?php echo $country;?></div>
              <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Modal Heading</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5 class="font-size-16">Overflowing text to show scroll behavior
                                                        </h5>
                                                 

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary ">Save changes</button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
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
		<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>

    
     
        <script src="assets/js/app.js"></script>
	
		   		<script src="search_route_trip.js"></script>
		   		<script src="load_passenger_info.js"></script>
				<script src="toggle.js"></script>
				<script src="insert_ticket.js"></script>
				
		<script src="js/sweetalert.js"></script>
	<script src="assets/js/alertify.min.js"></script>

	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>

        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	<script src="add_seat.js"></script>
	


<script>
$(document).ready(function(){


$('#submit_button').click(function(){
$('#myModal').modal('show');
});




  document.getElementById("example-date-input").min = new Date().getFullYear() + "-" +  parseInt(new Date().getMonth() + 1 ) + "-" + new Date().getDate()



	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "mm/dd/yyyy",
 onSelect: function(dateText) {
	alert();
        console.log("Selected date: " + dateText + "; input's current value: " + this.value);
    }
	
		//container: console.log($(this).datepicker())
}); 





});
</script>
<script>
$(".dateselect")
.datepicker({
    onSelect: function(dateText) {
	alert();
    }
})
.on("change", function() {
    console.log("Got change event from field");
});
</script>


    </body>
	 

</html>