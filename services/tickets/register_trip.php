<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$connect = sr_mysqli('ticket');
$route_name = '';
$query = "SELECT fullname,id FROM routes WHERE " . tessera_sql_tenant_eq() . " AND route_type='Main Route' ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $route_name .= '<option value="'.$row["id"].'">'.$row["fullname"].'</option>';
}


?>  
<?php
$vehicle = '';
$query = "SELECT name FROM vehicle WHERE " . tessera_sql_tenant_eq();
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $vehicle .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';

}



$vehicle_filter = '';
$query = "SELECT name FROM vehicle WHERE " . tessera_sql_tenant_eq();
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
	$vehicle_name = $row["name"];
  $vehicle_filter .= "<option value=\"bus='$vehicle_name' AND \">$vehicle_name</option>";

}

$vehicle_update = '';
$query_update = "SELECT name FROM vehicle WHERE " . tessera_sql_tenant_eq();
$result_update = mysqli_query($connect, $query_update);
while($row_update = mysqli_fetch_array($result_update))
{
  $vehicle_update .= "<option >".$row_update["name"]."</option>";

}

?>  


<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Register trip'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
		  <link rel='stylesheet' href='vendors/select2/select2.min.css'><link rel="stylesheet" href="./style.css">
 <link rel="stylesheet" type="text/css" href="css/datatable.css">
<link rel="stylesheet" type="text/css" href="css/buttons.css">	
	<script src="js/vendor/jquery-1.12.4.min.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/bootstrap-jquery-bridge.js"></script>
	<script src="js/timepicker-bs4.js"></script>

<link href="vendors/select2/select2.min.css" rel="stylesheet" />
<script src="vendors/select2/select2.min.js"></script>  	
<link rel="stylesheet" type="text/css" href="select_css.css">

    </head>

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
	</style>
		<style>
/* paginate styled by sr-platform-shell Book-list system */
		  .dt-button.red {
   color: #fff;

   background-color: #0379BB;
   border-radius: 2px;
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
	width:30%;
	 position: absolute;
right:40px;
  z-index: 4;
	margin-bottom:70px;
}
</style>
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
                                    <h4 class="mb-0">Register Trip</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Trips</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						            <div class="parent">
  
</div>
						 <?php
	 $username=$_SESSION['username'];
include 'modal/route_trip.php';
include 'modal/manually_time.php';
$route_assigned = '';
$query = "SELECT route_id,start_route,end_route FROM assigned_routes where username='$username' ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
	$assigned_route = $row["route_id"];
	$start_route = $row["start_route"];
	$end_route = $row["end_route"];
 $route_assigned .= "<option value=\"route_id='$assigned_route' AND \">$start_route to $end_route</option>";
}



	 ?>
              
   <div class="row">
                            
                                <div class="card sr-table-panel">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <div class="card-title">
										<div class="row">
										<div class="col">
										<select class="form-select selectpicker_filter" id="trip_day_filter"
                                                aria-label="Floating label select example" style="width:250px;">
												<option value="">All Days</option>
                                                   <option value="day ='Monday' AND ">Monday</option>
												   <option value="day ='Tuesday' AND ">Tuesday</option>
												   <option value="day ='wednesday' AND ">wednesday</option> 
												   <option value="day ='Thursday' AND ">Thursday</option>
												   <option value="day ='Friday' AND ">Friday</option>
												   <option value="day ='Saturday' AND ">Saturday</option>  
												   <option value="day ='Sunday' AND ">Sunday</option>
                                            </select>
										</div>
										<div class="col">
											<select class="form-select selectpicker_filter" id="route_name_filter"
                                                aria-label="Floating label select example" style="width:250px;">
												<option id="" value="">Choose Route Name</option>
                                          <?php  echo $route_assigned;?>
                                            </select>
										</div>
										<div class="col">
										<select class="form-select selectpicker_filter" id="vehicle_filter"
                                                aria-label="Floating label select example" style="width:200px;">
												<option id=""value="">Choose Vehicle</option>
                                               <?php echo $vehicle_filter;?>
                                            </select>
										</div>
										<div class="col">
										<select class="form-select selectpicker_filter" id="status_filter"
                                                aria-label="Floating label select example" style="width:200px;">
												<option value="">Status</option>
                                                   <option value="status ='active' AND ">Active</option>
												   <option value="status ='Not Active' AND ">Non Active</option>
                                            </select>
										</div>
										</div>
										

											
											
											
											
										</div>
                                    <button type="button" class="btn btn-soft-primary" data-bs-toggle="modal"data-bs-target="#myModal"><i class="uil uil-plus "></i>Regsiter</button>
                                    </div><!-- end card header -->
                                    <div class="card-body">
																					<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search....">
		 </form>
      </div> 
                                 <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 
<th>Trip Day</th>
<th>Route Name</th>
<th>Bus</th>
<th>Details</th>
<th>Status</th>
<th>Departure </th>
<th>Arrival </th>
<th>Change</th>
<th>Delete</th>
</tr>
				
                          </thead>
                                          <!-- end tbody --> 
                                            </table>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                        
                            <!-- end col -->
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


        <!-- /Right-bar -->

   <!-- end row -->
   <?php
   include 'canvas/update_trip.php';
   ?>
     <!-- end row -->

              <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

     <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog"
                                            aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="mySmallModalLabel">Check manifest date</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
													<p>You have change the Vehicle name.Choose manifest date to make effect, if its neccessary<p>
                                                      <div id="check_bus"></div>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
             
            </div>

        <!-- JAVASCRIPT -->
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
				<script src="js/filter/route_trip.js"></script>
				<script src="update_trip.js"></script>
		<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
               <script src="js/insert/route_trip.js"></script>

	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>

</script>
    <script>
	
        $(document).ready(function(){
				     $('.selectpicker').select2({
        dropdownParent: $('#myModal')
    });			 

    $('.selectpicker_filter').select2({
		
    });
	
		$("#trip_day_filter").change(function(){
	    day = $(this).val();
		route_name = $("#route_name_filter").val();
		vehicle = $("#vehicle_filter").val();
		status = $("#status_filter").val();
		$('#example').DataTable().destroy();
      
	fetch_data(day,route_name,vehicle,status);
	})	


	$("#route_name_filter").change(function(){
		day = $("#trip_day_filter").val();
		vehicle = $("#vehicle_filter").val();
	route_name = $(this).val();
	status = $("#status_filter").val();

    $('#example').DataTable().destroy();
	fetch_data(day,route_name,vehicle,status);
	
	})
	
	$("#vehicle_filter").change(function(){
		vehicle = $(this).val();
	day = $("#trip_day_filter").val();
	route_name = $("#route_name_filter").val();
	status = $("#status_filter").val();
	
    $('#example').DataTable().destroy();
	fetch_data(day,route_name,vehicle,status);
	
	})	
	
	$("#status_filter").change(function(){
	status = $(this).val();
	day = $("#trip_day_filter").val();
	route_name = $("#route_name_filter").val();
	vehicle = $("#vehicle_filter").val();

    $('#example').DataTable().destroy();
	fetch_data(day,route_name,vehicle,status);
	})
	
	day = "";
	route_name = "";
	vehicle = "";
	status = "";
	 fetch_data(day,route_name,vehicle,status);
	
			 function fetch_data(day,route_name,vehicle,status)
 {
	
	
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
								   "language": {
	     searchPlaceholder: "Search....",
        search: "",
     processing: '<i style="position:relative;left:50%;"class="fa fa-spinner fa-spin fa-3x fa-fw"></i>Processing...'
},
                'ajax': {
                'url':'fetch_trip.php',
				'data': {
                'day':day,
                'route_name':route_name,
                'vehicle':vehicle,
                'status':status
                  }
                },'columns': [
                    
				
				
                      { data: 'day' },
					  { data: 'route_name' },
					  { data: 'bus' },

					 { data: 'detail' },
					 { data: 'status' },
                    { data: 'time' },					  
                    { data: 'arrival' },					  
					 { data: 'update' },					  
					  { data: 'delete' }, 
					
					
					
              
                ],
				"lengthMenu": [[300, 350, 450, -1], [300, 325, 450, "All"]],
				dom: 'Bfrtip',
        buttons: [
		
                { extend: 'copyHtml5'},
            { extend: 'excelHtml5'},
            { extend: 'csvHtml5' },
			{ extend: 'print'},
            { extend: 'pdfHtml5' }
        ], 
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true, exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7,8,10 ]
                }, },
            { extend: 'pdfHtml5', footer: true }
        ], rowCallback: function(row, data, index) {
      if (data.status == "Due payment") {
    	
$(row).css('background-color','#ffe8f0');

      } 
	  if (data.status == "To be Payed"){
	$(row).css('background-color','#ffffff');	  

	  } if (data.status == "paid"){
	  	$(row).css('background-color','#99ff9c');


	  }
    },
			   "fnDrawCallback": function (oSettings) {
        $(".dataTables_filter").each(function () {
            $(this).appendTo($(this).parent().siblings(".panel-body"));
        });
    }, preDrawCallback: function() {
                let el = $('div.dataTables_filter label');
                if(!el.parent('form').length) {
                    el.wrapAll('<form></form>').parent()
                    .attr('autocomplete', false)
                    .attr('autofill', false);
                }
            } 
            });
		 oTable = $('#example').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 



 }
        });
		
        </script>
		<script>
document.addEventListener('DOMContentLoaded', function () {
	jQuery('#start_time, #end_time, #appt_time, #meet_time, #odd_time').timepicker({

	});
});
</script>
  
    </body>
</html>
