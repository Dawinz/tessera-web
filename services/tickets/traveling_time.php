<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT route_name,route_type FROM routes WHERE " . tessera_sql_tenant_eq();
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["route_name"].'">'.$row["route_name"].'('.$row["route_type"].')</option>';
}
?>  


<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Traveling Time</title>
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
 <link rel="stylesheet" type="text/css" href="css/datatable.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">
			<script src="js/vendor/jquery-1.12.4.min.js"></script>
        <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />


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
	
                                        </div>	
            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Traveling Time</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Routes</li>
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
                            
                                <div class="card">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <div class="card-title">
										<button type="button" class="btn btn-soft-primary" data-bs-toggle="modal"data-bs-target="#myModal_edit"><i class="uil uil-plus "></i>Add Route Points</button>
</div>
                                    </div><!-- end card header -->
                                    <div class="card-body">
				<div id="load_travel"></div>
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

                 <div id="myModal_edit" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Add Route Points</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                                                                                        <form class="needs-validation" name="event-form" id="insert_route" novalidate>
                           
       <table class="table " id="register_table">
								<tr>
									<th>Select Point</th>
									<th>Select Route</th>
									<th>Place Name</th>
									<th>Departure Time</th>
									<th>Arrival Time</th>							

									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
                                               

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" id="search">Register</button>
                                                    </div>
</form> 
                                            <div id="second_model_area"> 
													 </div>
                                         

                                                    </div>
                                           

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
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

     <!-- end row -->

              <!-- end row -->
             
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
        <script src="assets/libs/fullcalendar/main.min.js"></script>
		<script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

        <!-- Calendar init -->
        <script src="assets/js/pages/calendar.init.js"></script>
        <script src="assets/js/app.js"></script>
		 <script src="assets/js/pages/form-advanced.init.js"></script>
		  <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		   <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		   		<script src="js/insert/insert_route_points.js"></script>


        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	        <script src="assets/js/alertify.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </body>
</html>
<script>



$(document).ready(function(){
				var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';
		html += '<td ><select  class="form-select point" id="point" name="point[]"id="point"aria-label="Floating label select example"><option value="Point Type">Point Type</option><option value="starting">starting</option><option value="ending">ending</option></select></td>';
		html += '<td ><select  class="form-select route_name" id="route_name'+count+'" name="route_name[]"id="name"aria-label="Floating label select example"><option value ="Choose Route">Choose Route</option><?php echo $start_route2;?></select></td>';
		html += '<td><input name="point_name[]" type="text" name="point_name[]"id="point_name"class="form-control  " placeholder="Point Name"></td>';
		html += '<td><input name="depture[]" type="time" id="depture"class="form-control   depture" onfocus="this.showPicker()"></td>';
		html += '<td><input name="arrival[]" type="time" id="arrival"class="form-control   arrival"  onfocus="this.showPicker()"></td>';

	
		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove_route"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#register_table').append(add_input_field(0));	




$(document).on('click', '.add', function(){

		count++;
		$('#register_table').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	});
				$(document).on('click', '.remove_route', function(){

		$(this).closest('tr').remove();

	});		




 $.ajax({
				url:"search/search_traveling_time.php",
				method:"POST",
				data:{},
				success:function(response){
$("#load_travel").html(response);

	}
			
			});
});
</script>