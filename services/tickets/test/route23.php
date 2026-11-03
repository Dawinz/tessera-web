<!doctype html>
  <style>
  input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
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
	min-width:1180px;
	
}

#load_seat_chart{
	ebkit-box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  -moz-box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  box-shadow: 0px -1px 3px rgba(209, 241, 255, 0.24);
  padding-top:20px; padding-bottom:20px;
  width: fit-content;
 
}
.swal-wide{
    width:850px;
}
iframe{
	height:1000px;
	width:100%;
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
                                									 
                          <table class="search_table">
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
																						<span class="accordion-toggle toggle_image" style="" id="to_change_t" > <img class="down_image"src ="images/up-arrows5.png"> </span>

                                </div>								


                            </div>

							<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false" style="position:relative;bottom:40px;">
  <div class="carousel-indicators"style="position:relative;bottom:-40px;">
    <button type="button" id="first_active_cars"data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" id="second_active_cars"data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>

  </div>

   
    <div class="carousel-item active" id="first_casl">
	     <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Books & Reserved & locks</h4>

                           

                            </div>
    <div class="card">
   
	<?php include 'my_ticket_info.php';?>
	
	</div>
    </div>
      <div class="carousel-item" id="second_cars">
	      <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Customer info</h4>

                           

                            </div>
	<div class="card">
		
				<div class="card-body" style="overflow: visible;" >

<audio  id="pop" >
  <source src="audio/sound.mp3" type="audio/mpeg">
</audio>
    <table>
						  <tr>
						  <td class="table_padding" >



<select class="js-example-basic-single td_with picker_select" id="trip_start" >

  
 
</select>
						 </td>  <td class="table_padding" >
					   
<select class="js-example-basic-single td_with picker_select" id="trip_end" >

   
</select>
</td>
<td class="table_padding" style="padding-left:50px;" >
 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch3" switch="bool" class="copy_seat_info" />
                                                            <label for="square-switch3" data-on-label="On"
                                                                data-off-label="copy" class="mb-0"></label>
                                                        </div>
                                                   
</td>
 

						  </tr>
						  </table>

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
									<th>foc</th><th>Trans</th><th >advance</th><th class="display_none">advance</th>
									<th class="display_none">focinfo</th>
									<th class="display_none">tran info</th>
									<th class="display_none">start</th>
									<th class="display_none">end</th>
									<th class="display_none">start</th>
									<th class="display_none">end</th>
									<th class="display_none">route_day_id</th>
									<th class="display_none">trip_date</th>
									<th class="display_none">route_id</th>
									<th class="display_none">Seat_id</th>
									<th class="display_none">adnc</th>
									
									
								</tr>
							</table>
							<div align="center">
								<input type="submit" name="submit" id="submit_button" class="btn btn-primary" value="Continue" />
							</div>
							
						</div>
						  
						
					</form>
					
				</div>
	</div>
     <div class="confm_text"style="display:none;"></div>
	 <span class="t_book_d" style="display:none;"><hr>
  <h3 class="header3"><span data-plugin="counterup"id="total_price_ticket">0</span><span
                                                        class="text-muted d-inline-block fw-normal font-size-15 ms-2">Total</span>
                                                </h3></span>
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5></h5>
        <p></p>
      </div>
    </div>


</div>
							
									  </div> <div id="hover_panel"class="col">
									
									            
										      <div class="p-3 mb-2 bg-dark  bg-gradient text-white alert_class" style="display:none;text-align  :center;">
                                       <h5 class="route_loaded_date "style="color:white;"></h5> 
									 
									  </div>
                                     
                                        <!-- Nav tabs -->
                              	
									 <div style="display:none;"class="spinner-border text-primary m-1 route_selection_spiner " role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
											<div id= "load_seat_chart"class="card center" style="">
											
                                            <div class="card-body" style="text-align:center;"class="center">
										
                                                <div class="mb-4">
<img src="images/no-task3.png">                                                </div>
                                                
                                                <h5 class="font-size-15 text-uppercase">
                                                    No seat chart selected</h5>
                                                <p class="text-muted mb-0">Seat chart panel<br>Select route and the appropriate route seat chart will appear.</p>
                                            </div>
                                    
                                
										
											</div>
											
                            
									  </div>
                 
                    </div>
	<?php include 'modal_collection.php';?>
		  <!-- end page title -->

          <div id="foc_panel" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Foc Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close" id="close_foc_panel">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
													<div id="foc_text"class="display_none"></div>
													
                                               		      <div class="form-floating">
                                            <textarea class="form-control " placeholder="Leave a comment here" id="floatingTextarea2"
                                                style="height: 100px"></textarea>
                                            <label for="floatingTextarea2"class="">Foc details</label>
                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal"id="close_foc_panel2">Close</button>
                                                        <button type="button" class="btn btn-primary " id="save_foc_d">Save changes</button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>   
										<div id="trans_panel" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Tranit Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"id="close_trans_panel"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
													<div id="trans_text"class="display_none"></div>
                                               		      <div class="form-floating">
                                            <textarea class="form-control " placeholder="Leave a comment here" id="transit_details"
                                                style="height: 100px"></textarea>
                                            <label for="floatingTextarea2"class="">Transit details</label>
                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal"id="close_trans_panel2">Close</button>
                                                        <button type="button" class="btn btn-primary " id="save_trans_d">Save changes</button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>		
										<div id="advance_panel" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Advance Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"id="close_advc_panel"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
													
												<h5 class="modal-title" id="myModalLabel"><span class="t_price"></span> <span class="text-muted d-inline-block fw-normal font-size-15 ms-2">Ticket price</span></h5>
<div id="advance_chcek_text"class="display_none"></div>
													   <br> <label class="form-check-label" for="topbar-color-light">Advance Ammount</label>
                                                 <input type="text" name="" class="form-control advance_left"  value=""  />

                                                    </div>
													    <div class="modal-footer">
													<h5 class="modal-title" id="myModalLabel"><span style="color:#fc0335;" class="result_half"> 0</span> <span class="text-muted d-inline-block fw-normal font-size-15 ms-2">Balance</span></h5>
                                                   </div>
												   <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal"id="close_advc_panel2">Close</button>
                                                        <button type="button" class="btn btn-primary save_avcance_d" id="save_avcance_d">Save </button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
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

<div class="display_none country"><?php echo $country;?></div>
          
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
				<script src="toggle.js"></script>
				<script src="insert_ticket.js"></script>
				<script src="save_foc.js"></script>
				<script src="hover_tables.js"></script>
				<script src="add_half_cost.js"></script>
				<script src="adnc.js"></script>
			
				
		<script src="js/sweetalert.js"></script>
	<script src="assets/js/alertify.min.js"></script>

	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>

        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	<script src="add_seat.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	
 <script src="currency.js"></script>


<script>
$(document).ready(function(){

 
    
	  $('.copy_seat_info').on('click',function(){
    
        if($('.copy_seat_info:checked').length == $('.copy_seat_info').length){
        var firstname = $('.firstname').val();
        var lastname = $('.lastname').val();
		var phone_no = $('.phone_no').val();
		var verification = $('.verification').val();
		var age = $('.age').val();

$("input:radio[value=female]").each(function(){
              if(this.checked){

				
				$("input:radio[value=female]").each(function(){
 this.checked = true;
				
					});
				
				
				
				
			  }else{
				  				 

			  }
    
	
			});
			$("input:radio[value=male]").each(function(){
              if(this.checked){

				
				$("input:radio[value=male]").each(function(){
 this.checked = true;
				
					});
				
				
				
				
			  }else{
				  				 

			  }
    
	
			});
		
		$('.firstname').val(firstname);
		$('.lastname').val(firstname);
		$('.phone_no').val(phone_no);
		$('.verification').val(verification);
		$('.age').val(age);
	
		
		
		
        }else{
    
        }
    });

$('#trans_panel').modal({
    backdrop: 'static',
    keyboard: false  // to prevent closing with Esc button (if you want this too)
});
$('#foc_panel').modal({
    backdrop: 'static',
    keyboard: false  // to prevent closing with Esc button (if you want this too)
});
$('#advance_panel').modal({
    backdrop: 'static',
    keyboard: false  // to prevent closing with Esc button (if you want this too)
});

$('.bs-example-modal-xl_manifest').modal({
    backdrop: 'static',
    keyboard: false  // to prevent closing with Esc button (if you want this too)
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
  $("#carouselExampleIndicators_manifest").carousel({
  interval: false,
});
</script>


    </body>
	 

</html>