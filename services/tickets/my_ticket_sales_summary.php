<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
?>
<!doctype html>
<style>
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
#myInputTextField
{
	width:20%;
	 position: absolute;
right:40px;
  z-index: 4;
	margin-bottom:70px;
}

.dt-search {
    display: none;
}
</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'My ticket sales'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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
				
<link rel="stylesheet" type="text/css" href="select_css.css">
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
	<link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" rel="stylesheet">

		<script src="js/vendor/jquery-1.12.4.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
    </head>

    
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php include 'top_header.php';?>
	
    <?php
//index.php
$username=$_SESSION['username'];
$route_id="";
$connect = sr_mysqli('ticket');



$route_name2 = '';
$query = "SELECT route_name,route_id,start_route,end_route FROM assigned_routes WHERE username='$username'";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
	$route_id_option = $row["route_id"];
	$fullname=  ''.$row["start_route"].' to '.$row["end_route"].'';
 //$route_name2 .= '<option value="'.$row["route_id"].'">'.$row["start_route"].' to '.$row["end_route"].'</option>';

  $route_name2 .= "<option value=\"route_id='$route_id_option' AND \">$fullname</option>";

$query_sub = "SELECT route_name,route_id,start_route,end_route FROM routes WHERE main_id='$route_id_option'";
$result_sub = mysqli_query($connect, $query_sub);
while($row_sub = mysqli_fetch_array($result_sub))
{
	$route_id_option = $row_sub["route_id"];
	$fullname=  ''.$row_sub["start_route"].' to '.$row_sub["end_route"].'';
	  $route_name2 .= "<option value=\"sub_route_id='$route_id_option' AND \">$fullname</option>";
}


}

$users = '';
$query = "SELECT firstname,surname,username FROM users where username ='$username'";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
	$user = $row["username"];
	$fullname_user=  ''.$row["firstname"].' '.$row["surname"].' ('.$user.')';

  $users .= "<option value=\"issuer='$user' AND \">$fullname_user</option>";

}

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('m/d/Y', time());
$date_now = date("Y-m-d", strtotime($date_today));
?>
            <!-- ========== Left Sidebar Start ========== -->

            <!-- Left Sidebar End -->

            <style>
			button{
				color:white;
			}
			</style>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Ticket sales </h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">Sales summary list</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                  
               <div class="card sr-table-panel">
	
					 
					        <div class="btn-toolbar p-3" role="toolbar">
                                      

											<div class="mb-4">
                                                   <select style="width:250px;"class="form-select username selectpicker" id ="username">
												   <?php echo $users; ?></select>
                                            </div>&nbsp &nbsp 
											 <div class="mb-4">
                                                   <select style="width:250px;"class="form-select route_name selectpicker" id ="route_name">
												   <option id="" value="">Choose Route</option>
												   <?php echo $route_name2; ?></select>
                                            </div>&nbsp &nbsp   
                                     

                                            <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
                                                                                    <input style="width:250px;" class="form-control" type="date" value="<?php echo $date_now;?>" id="start_date" onfocus="this.showPicker()">

                                            </div>
                                            </div>    &nbsp                  <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
                                                    <input style="width:250px;" class="form-control" type="date" value="<?php echo $date_now;?>" id="end_date" onfocus="this.showPicker()">

                                            </div>
                                            </div>       
			   
								
                                        </div>
					                   <div class="row">
                            <div class="col-lg-12">
							
  <div id="load_ticket_sales"></div>
                            </div><!-- end col --> 
                        </div>
			  <!-- end page title -->

       
                                   
				
                    </div> <!-- container-fluid -->
                </div>
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
        <script src="assets/libs/fullcalendar/main.min.js"></script>
		<script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

        <!-- Calendar init -->
        <script src="assets/js/pages/calendar.init.js"></script>
        <script src="assets/js/app.js"></script>
		 <script src="assets/js/pages/form-advanced.init.js"></script>
		  <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		   <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
		<script src="js/sweetalert.js"></script>
		<script src="select_total_sale.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js" type="text/javascript"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" type="text/javascript"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js" type="text/javascript"></script>


  
        <!-- apexcharts init -->

    </body>
</html>
 <script>
$(document).ready(function(){
		 $('.selectpicker').select2({
    });			 


function queries() {
start_date = $("#start_date").val();
	end_date = $("#end_date").val();
	route_name = $("#route_name").val();
	username = $("#username").val();
	$.ajax({
				url:"search/ticket_sales.php",
				method:"POST",
				data:{start_date:start_date,end_date:end_date,route_name:route_name,username:username},
				success:function(response){
$("#load_ticket_sales").html(response);
}
});  
}
	
queries(start_date, end_date,route_name,username);	

$("#start_date").change(function(){
queries();	
});
$("#end_date").change(function(){
queries();	

});
$("#route_name").change(function(){
queries();	
});

$("#username").change(function(){
queries();	
});

});
        </script>