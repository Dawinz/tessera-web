<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
?>
<!doctype html>

  

<html lang="en">

    <head>

        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Book list'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
<?php
 include 'include_scripts.php';
 ?>
    </head>

    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

<style>
iframe{
	height:1000px;
	width:100%;
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
        border-radius: 5px;
        text-indent: 10px;
    }
    #myInputTextField.sr-table-search {
        width: min(350px, 100%);
        min-width: 200px;
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
width:350px;
}
	</style>
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
				<div id="complete_formula"></div>
				      <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <div>
									<table style="margin:5px;">
				<tr>
				<td>
<button type="button" class="btn btn-danger"data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="uil uil-align-alt"></i> Advance Search</button>

				</td>
				<td>
<button type="button" class="btn btn-soft-success"id="search_complete"><i class="uil uil-align-center-h" ></i> Complete Tickets</button>
				</td>	
				<td>
<button type="button" class="btn btn-soft-warning"id="search_sub"><i class="uil uil-check me-2" ></i>Sub routes</button>
				</td>
				<td>
<button type="button" class="btn btn-soft-info"id="search_booked"><i class="uil uil-clock-five" ></i> Reserved </button>
				</td><td>
<button type="button" class="btn btn-soft-primary"id="Due_payment"><i class="uil uil-dollar-alt" ></i> Due Tickets </button>
				</td>
				</tr>
				</table>
								</div>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                            		<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search....">
		 </form>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
				
				
				
				<div class="card sr-table-panel" data-sr-loading-label="Loading bookings...">
					<div class="card-body p-0">
   <table class="table mb-0 table-hover table-nowrap align-middle" id="passenger_info" style="width:100%;">
                                                              <thead class="table-light">
<tr>                 
<th>Seat no</th>
<th>Ticket number</th>


<th>Fullname</th>
<th>Trip date </th>
<th>Bus </th>
<th>Seller </th>
<th>Cost</th>

<th>options</th>
<th>Reseverd</th>
<th>Clear</th>





</tr>
				
                          </thead>
						                 	 
                                          <!-- end tbody --> 
                                            </table>
					</div>
 
                </div></div>
				
			
                <!-- End Page-content -->
	<?php include 'modal_collection.php';?>

                
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
			<?php include 'offcanvis.php';?>
			
        <!-- JAVASCRIPT -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
        <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
		<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>

    
     
        <script src="assets/js/app.js"></script>
	
	
			
				
		<script src="js/sweetalert.js"></script>
	<script src="assets/js/alertify.min.js"></script>

	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>

        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	<script src="add_seat.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 






    </body>
	 

</html>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "mm/dd/yyyy",
  autoclose: true
 });

 fetch_data('no');

 function fetch_data(is_date_search, start_date='', end_date='')
 {
  var dataTable = $('#passenger_info').DataTable({
   "processing" : true,
   "serverSide" : true,
   dom: 'Bfrtip',
   "lengthMenu": [50],
    "order": [1, 'desc'],
   language: (window.TesseraStates && TesseraStates.listLanguage)
     ? TesseraStates.listLanguage('No bookings yet', 'No bookings match your search')
     : undefined,
   "ajax" : {
    url:"fetch_user_booking_info_all.php",
    type:"POST",
    data:{
     is_date_search:is_date_search, start_date:start_date, end_date:end_date
    },
    error: (window.TesseraStates && TesseraStates.loadError)
      ? TesseraStates.loadError('Could not load bookings')
      : undefined
   }
  });
 }

 $('#search_complete').click(function(){
  var start_date = "status = 'complete' AND ";
  var end_date = $('#end_date').val();
$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
 });  
 
 $('#search_sub').click(function(){
  var start_date = "status = 'half_complete' AND ";
  var end_date = $('#end_date').val();
$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
 });
 
 $('#search_booked').click(function(){
  var start_date = "status = 'booked' AND ";
  var end_date = $('#end_date').val();
$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
 }); 

 $('#Due_payment').click(function(){
  var start_date = "amount_status = 'yes' AND ";
  var end_date = $('#end_date').val();
$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
 }); 
 
 const fruits = [];
$("#starting_location").change(function(){ 

var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();
push_start = "start_route = '"+starting_location+"'  AND ";

fruits_extra = fruits.filter(e => e !== push_start); 
if(fruits_extra==""){
fruits.push(push_start);


  var start_date = "";
  var end_date = push_start;
  
$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);	
}else{
		fruits.pop();

fruits.push(push_start);


  var start_date = "";
  var end_date = push_start;
  
$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);	
}


});

$("#ending_location").change(function(){ 


var ending_location = $( "#ending_location option:selected" ).text();
push_start = "end_route = '"+ending_location+"'  AND ";
fruits_extra = fruits.filter(e => e !== push_start); 

if(fruits_extra==""){
	fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
   

}else{
		fruits.pop();
fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
   
	
}



});


$("#bus").change(function(){ 


var bus = $( "#bus option:selected" ).text();
push_start = "bus = '"+bus+"'  AND ";

fruits_extra = fruits.filter(e => e !== push_start); 

if(fruits_extra==""){
fruits.push(push_start);


  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
   
	
	
}else{
	fruits.pop();
fruits.push(push_start);


  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
   
	
}



});

$("#username").change(function(){ 


var username = $( "#username" ).val();
push_start = "issuer = '"+username+"'  AND ";


 fruits_extra = fruits.filter(e => e !== push_start); 
 
if(fruits_extra==""){
fruits.push(push_start);


  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
   
}else{
	fruits.pop();
	fruits.push(push_start);


  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);

}



});

	$("#foc").change(function(){ 

if(this.checked){
	push_start = "reason != ' '  AND ";


fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
  
}else{
fruits.pop();

}


});	$("#transit").change(function(){ 

if(this.checked){
push_start = "transit = 'checked'  AND ";


fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
  
}else{
	fruits.pop()

}




});
	
 
  $("#starting_date").change(function(){
starting_date = $("#starting_date").val();

push_start = "trip_date >= '"+starting_date+"'  AND ";
fruits_extra = fruits.filter(e => e !== push_start); 
if(fruits_extra==""){
fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);	
}else{
	fruits.pop();
	fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
}

 }); 
 
 $("#end_date").change(function(){
ending_date = $("#end_date").val();

push_start = "trip_date <= '"+ending_date+"'  AND ";
fruits_extra = fruits.filter(e => e !== push_start); 
if(fruits_extra==""){
	fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
}else{
		fruits.pop();
	fruits.push(push_start);

  var start_date = "";
  fruits_with_out = (fruits.join(""));
  var end_date = fruits_with_out;

$('#passenger_info').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
}

 });
 
 $('.js-example-basic-single').select2();
 
 		 oTable = $('#passenger_info').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
});
 

 
 
});
</script>
<script>
$(".dateselect").datepicker({
    onSelect: function(dateText) {
	
    }
})
.on("change", function() {
    console.log("Got change event from field");
});
  $("#carouselExampleIndicators_manifest").carousel({
  interval: false,
});
</script>
<script src="search_avnce.js"></script>