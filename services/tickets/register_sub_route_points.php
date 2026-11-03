<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT name FROM sub_route group by name";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
?>  



<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Sub-route points'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Register Route points</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Route Points</li>
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
              
   <div class="row">
                            
                                <div class="card sr-table-panel">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Vehicle lists</h4>
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
<th>Name</th>
<th>Point</th>
<th>Route Name</th>
<th>Time</th>
<th>Delete</th>

<th>Delete</th>
<th>Ref no</th>
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

     
                        
                    </div> <!-- container-fluid -->
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
                                                                <label class="form-label">Select Route Name</label>
            <select class="form-select" id="route_name"
                                                aria-label="Floating label select example">
												<option value="">Choose Route Name</option>
                                                    <?php echo $start_route2; ?>

                                            </select>                                                               
                                                            </div> 
															<div class="mb-3">
                                                                <label class="form-label">Select Point</label>
         <select class="form-select" id="point_start"
                                                aria-label="Floating label select example">
												<option value="">Choose Point</option>
                                                   <option>starting</option>
												   <option>end</option>
												   
                                            </select>                                                                     
                                                            </div>
											
		  <div class="mb-3">
                                                                <label class="form-label">Place Name</label>
                                                                 <input class="form-control" type="text" id="place_name" placeholder="Place Name.." >
                                                               
                                                            </div>
		  
		  
														
															<div class="mb-3">
                                                                <label class="form-label">Arraival Time</label>
                                                                 <input class="form-control" type="time"  name="appt" min="09:00" max="18:00" required type="text"  id="a_time"  >
                                                               
                                                            </div>	

													
                                                        </div>
                                                    </div>
                                               
                                                </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="search">Register</button>
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

        <!-- JAVASCRIPT -->
		<script src="js/vendor/jquery-1.12.4.min.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/bootstrap-jquery-bridge.js"></script>
	<script src="js/timepicker-bs4.js"></script>
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
		   		<script src="insert_route_points_sub.js"></script>
		<script src="js/sweetalert.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	
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
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 6, "desc" ]],
				
                'ajax': {
                'url':'fetch_route_points_sub.php',
                },'columns': [
                    
				
					
                      { data: 'name' },
					  { data: 'point' },
					  { data: 'route_name' },
					  { data: 'time' }, 
					  { data: 'detail' },			                     
					  { data: 'delete' },
					  { data: 'id' }, 
					  
					
					
              
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
            });
		 oTable = $('#example').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 
        });
        </script>
		<script>
document.addEventListener('DOMContentLoaded', function () {
	jQuery('#start_time, #end_time, #appt_time, #meet_time, #odd_time').timepicker({

	});
});
</script>
      </script>
    </body>
</html>
