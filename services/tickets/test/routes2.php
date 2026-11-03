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
	width:85%;
	 position: absolute;
right:40px;
  z-index: 4;
	margin-bottom:70px;
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

        <!-- plugin css -->
        <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>   
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="css/buttons.css">
	  <link rel="stylesheet" type="text/css" href="select_css.css">
        <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />
<link href="https://code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>


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
									  <div class="col" style="height: fit-content;" >
									               <div class="card "  >
                                <div class="card-body resizable" style="resize: vertical;
  overflow:scroll; height: fit-content;">
                                									 
                          <table>
						  <tr>
						  <td class="table_padding" >


<label class="font-size-14" for="">From</label><br>
<select class="js-example-basic-single td_with" id="starting_location" >

    <?php echo $start_route2; ?>
 
</select>
						 </td>  <td class="table_padding" >
	<label class="font-size-14" for="">To</label><br>					   
<select class="js-example-basic-single td_with" id="ending_location" >
  <option >Choose Ending Point</option>
    <?php echo $start_route2; ?>
</select>
</td>
<td class="table_padding" >
	<label class="font-size-14" for="">Go date</label><br>					   

 <input class="form-control dateselect" type="text"  id="starting_date" style="caret-color: transparent;margin-bottom:4px;cursor: pointer;"placeholder="mm/dd/yyyy" value ="<?php echo $tomorrowmo;?>" onkeydown="return false">

 </td>
 
 <td class="table_padding"><label class="font-size-14" for="">Return date</label><br>					   
 <input class="form-control dateselect ending_date" type="text"  id="example-date-input" style="margin-bottom:4px; caret-color: transparent;cursor: pointer;"placeholder="MM/DD/YYYY"onkeydown="return false" style="">

                                              
						  </td>
						  </tr>
						  </table>
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
                                                   <div class="table-responsive">
									
									
                                        <table id="example"  class="table table-hover table-nowrap mb-0 align-middle table-check">
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
                         <!-- end tbody --><tbody id="first_preloader"> 
						 <tr>
						 <td></td>
						 <td></td>
						 <td></td>
						 <td></td>
						 <td>  <div class="spinner-border text-light m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div></td>
						 <td></td>
						 <td></td>
						 <td></td><td></td>
						 
						 </tr>
						 </tbody>
                                        </table><!-- end table -->
                                    </div>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-profile" role="tabpanel">
                                                <p class="mb-0">
                                                    Food truck fixie locavore, accusamus mcsweeney's marfa nulla
                                                    single-origin coffee squid. Exercitation +1 labore velit, blog
                                                    sartorial PBR leggings next level wes anderson artisan four loko
                                                    farm-to-table craft beer twee. Qui photo booth letterpress,
                                                    commodo enim craft beer mlkshk aliquip jean shorts ullamco ad
                                                    vinyl cillum PBR. Homo nostrud organic, assumenda labore
                                                    aesthetic magna 8-bit.
                                                </p>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-messages" role="tabpanel">
                                                <p class="mb-0">
                                                    Etsy mixtape wayfarers, ethical wes anderson tofu before they
                                                    sold out mcsweeney's organic lomo retro fanny pack lo-fi
                                                    farm-to-table readymade. Messenger bag gentrify pitchfork
                                                    tattooed craft beer, iphone skateboard locavore carles etsy
                                                    salvia banksy hoodie helvetica. DIY synth PBR banksy irony.
                                                    Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh
                                                    mi whatever gluten-free.
                                                </p>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-settings" role="tabpanel">
                                                <p class="mb-0">
                                                    Trust fund seitan letterpress, keytar raw denim keffiyeh etsy
                                                    art party before they sold out master cleanse gluten-free squid
                                                    scenester freegan cosby sweater. Fanny pack portland seitan DIY,
                                                    art party locavore wolf cliche high life echo park Austin. Cred
                                                    vinyl keffiyeh DIY salvia PBR, banh mi bag before banksy hoodie 
                                                    helvetica they sold out farm-to-table.
                                                </p>
                                            </div><!-- end tab pane -->
                                        </div>
                                  
                       
                            
                                </div>
                            </div>
									  </div> <div class="col">
									               <div class="card "style="resize: none;
  ">
                                     <div class="card-body">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-pills nav-justified bg-light" role="tablist">
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
                                          
                                          
                                        </ul>
        
                                        <!-- Tab panes -->
                                        <div class="tab-content p-3 text-muted">
                                            <div class="tab-pane active" id="navpills2-home" role="tabpanel">
                                                <p class="mb-0">
                                                    Raw denim you probably haven't heard of them jean shorts Austin.
                                                    Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache
                                                    cliche tempor, williamsburg carles vegan helvetica. Reprehenderit
                                                    butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi,
                                                    qui irure terry richardson ex squid. Aliquip placeat salvia cillum
                                                    iphone. Seitan quis cardigan american apparel, voluptate nisi qui.
                                                </p>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-profile" role="tabpanel">
                                                <p class="mb-0">
                                                    Food truck fixie locavore, accusamus mcsweeney's marfa nulla
                                                    single-origin coffee squid. Exercitation +1 labore velit, blog
                                                    sartorial PBR leggings next level wes anderson artisan four loko
                                                    farm-to-table craft beer twee. Qui photo booth letterpress,
                                                    commodo enim craft beer mlkshk aliquip jean shorts ullamco ad
                                                    vinyl cillum PBR. Homo nostrud organic, assumenda labore
                                                    aesthetic magna 8-bit.
                                                </p>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-messages" role="tabpanel">
                                                <p class="mb-0">
                                                    Etsy mixtape wayfarers, ethical wes anderson tofu before they
                                                    sold out mcsweeney's organic lomo retro fanny pack lo-fi
                                                    farm-to-table readymade. Messenger bag gentrify pitchfork
                                                    tattooed craft beer, iphone skateboard locavore carles etsy
                                                    salvia banksy hoodie helvetica. DIY synth PBR banksy irony.
                                                    Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh
                                                    mi whatever gluten-free.
                                                </p>
                                            </div> <!-- end tab pane -->
                                            <div class="tab-pane" id="navpills2-settings" role="tabpanel">
                                                <p class="mb-0">
                                                    Trust fund seitan letterpress, keytar raw denim keffiyeh etsy
                                                    art party before they sold out master cleanse gluten-free squid
                                                    scenester freegan cosby sweater. Fanny pack portland seitan DIY,
                                                    art party locavore wolf cliche high life echo park Austin. Cred
                                                    vinyl keffiyeh DIY salvia PBR, banh mi bag before banksy hoodie 
                                                    helvetica they sold out farm-to-table.
                                                </p>
                                            </div><!-- end tab pane -->
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
		<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>

        <!-- plugin js -->


        <!-- Calendar init -->
     
        <script src="assets/js/app.js"></script>
	
		   		<script src="search_route_trip.js"></script>
		<script src="js/sweetalert.js"></script>
	<script src="assets/js/alertify.min.js"></script>

	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>

        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	
</script>
<script>
$(document).ready(function() {
	
	  $.ajax({
        url:'load_first_route_table.php', // URL of where AJAX should POST the data
        type: 'POST', // Send a POST to server
        data: {'id': 3243}, // Data to be POSTED to server. In PHP use echo $_POST['id']; to see what the AJAX passed in
        dataType: 'HTML', // Expected data from server
        success: function(data){
            console.log(data); // <-- view what the server sent back to AJAX
            // OR

            $("#first_preloader").html(data);
        }
    });
	
	
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