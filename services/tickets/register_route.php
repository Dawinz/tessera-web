<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT name FROM locations_names";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
?> 
 <?php
$curerncy = '';
$query = "SELECT country FROM currency";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $curerncy .= '<option value="'.$row["country"].'">'.$row["country"].'</option>';
}
?>  

<?php
$route_transit = '';
$query = "SELECT start_route,end_route FROM routes WHERE " . tessera_sql_tenant_eq();
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $route_transit .= '<option value="'.$row["start_route"].' to '.$row["end_route"].'">'.$row["start_route"].' to '.$row["end_route"].'</option>';
}



?>
<?php
$route_name = '';
$query = "SELECT route_name FROM routes WHERE " . tessera_sql_tenant_eq() . " AND route_type='Main Route'";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $route_name .= '<option value="'.$row["route_name"].'">'.$row["route_name"].'</option>';
}

$route_name_id = '';
$query = "SELECT fullname,route_id FROM routes WHERE " . tessera_sql_tenant_eq();
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 	$route_id_option = $row["route_id"];
 	$fullname = $row["fullname"];
  $route_name_id .= "<option value=\"route_id='$route_id_option' AND \">$fullname</option>";
}


?>  


<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Register route'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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

            
<link href="vendors/select2/select2.min.css" rel="stylesheet" />
<script src="vendors/select2/select2.min.js"></script>  	
<link rel="stylesheet" type="text/css" href="select_css.css">
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
                                    <h4 class="mb-0">Register Route</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Routs</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						            <div class="parent">
  
</div>
						 <?php
	 $username=$_SESSION['username'];
include 'modal/regiser_route.php';
include 'modal/route_contact.php';
include 'modal/regiser_transit.php';
include 'modal/route_term.php';
include 'modal/country_code.php';
include 'modal/route_time.php';
include 'canvas/update_route.php';
	 ?>
              
   <div class="row">
                            
                                <div class="card sr-table-panel">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <div class="card-title">
										<div class="row">
										<div class="col">
											<select class="form-select selectpicker_filter" id="route_name_f"
                                                aria-label="Floating label select example" style="width:250px;">
												<option value="">Choose route name</option>
                                                <?php echo $route_name_id;?>
                                            </select>
										</div>
										<div class="col">
										<select class="form-select selectpicker_filter" id="status_f"
                                                aria-label="Floating label select example" style="width:250px;">
												<option value="">Status</option>
												<option value="route_type ='Main Route' AND ">Main Route</option>
												<option value="route_type ='Sub Route' AND ">Sub Route</option>
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
<th>Route Name</th>

<th>Fair Price</th>


<th>Redirect</th>
<th>Status</th>
<th>Main Route</th>
<th>Depeture</th>
<th>Arrival</th>

<th>C'code</th>
<th>Terms</th>
<th>Transit</th>
<th>Contact</th>
<th>Change</th>
<th>Deleted </th>

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

                    </div>
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
	<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
	<script src="js/insert/route_insert.js"></script>
	<script src="js/insert/add_transit.js"></script>
   <script src="update_route.js"></script>

	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
	<script src="currency.js"></script>

	<script src="currency_multiple.js"></script>
	<script src="js/insert/add_location.js"></script>
	<script src="js/insert/add_contact.js"></script>
	<script src="js/insert/add_country_code.js"></script>
    <script>
        $(document).ready(function(){
							     $('.selectpicker').select2({
        dropdownParent: $('#myModal')
    });			 

    $('.selectpicker_filter').select2({
    });
			$("#route_name_f").change(function(){
				route_name = $(this).val();
				status = $("#status_f").val();
				 $('#example').DataTable().destroy();
	fetch_data(route_name,status);
			});
			
			$("#status_f").change(function(){
				status = $(this).val();
				route_name = $("#route_name_f").val();
					 $('#example').DataTable().destroy();
	fetch_data(route_name,status);
			});
			
			route_name ="";
			status ="";
	 fetch_data(route_name,status);		
function fetch_data(route_name,status)
 {
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch_route.php',
				'data': {
                'route_name':route_name,
                'status':status
                  }
				
                },'columns': [
                    
				
					
				
					  { data: 'fullname' },   
					
					 
					  { data: 'fare_price' },
					
					
				
					  { data: 'transit' },
					   { data: 'badge' },
					  { data: 'route_id_fullname' },
					  					  { data: 'time' },
					  					  { data: 'arrival' },

					  { data: 'code' },
					  { data: 'terms' },
					  { data: 'transit' },

					  { data: 'contact' },
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
    </body>
</html>