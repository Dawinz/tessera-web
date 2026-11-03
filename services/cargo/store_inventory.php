<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
?>
<style>
	iframe{
	height:1000px;
	width:100%;
}
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
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 0.25rem !important;
    border: 1px solid #dee2e6 !important;
    background: #fff !important;
    color: #495057 !important;
}
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
<!doctype html>

<html lang="en">
<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('d-m-Y', time());
?>
    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Store inventory'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
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
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
      <link rel="stylesheet" type="text/css" href="css/buttons.css">
      		<script src="js/vendor/jquery-1.12.4.min.js"></script>
       <link rel="stylesheet" href="css/datapicker/datepicker3.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />   
<link rel="stylesheet" type="text/css" href="select_css.css">
    </head>

    <style>

button.dt-button, div.dt-button, a.dt-button, input.dt-button {
    border-radius: 2px;
    color: black;
    
}

</style>
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark">

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
                                    <h4 class="mb-0">Store Inventory</h4>

                                 

                                </div>
                            </div>
                        </div>


                          <?php
                             $route_query = "";
   $routes_array=array();
$select=mysqli_query($db,"SELECT route_full_name FROM   assigned_routes   where username ='$username'  ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_name_choose =$get['route_full_name']; 
			
			$route_name_choose = "'$route_name_choose' ";
			
array_push($routes_array,$route_name_choose);

			
}}
$selected_route = implode(', ', $routes_array);
if ($select_num !== 0 && $selected_route !== '') {
	$route_query = "account IN ($selected_route) and ";
} else {
	$route_query = '';
}


   
    ?>

<span id="route_name" style="display:none;"><?php echo  "$route_query";?></span>

						            <div class="parent">
  
</div>
						 <?php
	 $username=$_SESSION['username'];

	 ?>
              
   <div class="row">
                            
                                <div class="card sr-table-panel">
	
								             <div class="row">

                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Select Route</label>
<select name="route" id="route" class="form-select action selectpicker"  data-live-search="true">
    <option value="<?php echo $route_query;?>">All routes</option>
    <?php
							   
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'  order by id asc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){

	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
$route_full_name_value="account='".$route_full_name."' and";
echo '<option value="'.$route_full_name_value.'">'.$route_full_name.'</option>';
}
}
	
									   ?></select>
                                                    </div>
                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Select Status</label>
<select name="store" id="store" class="form-select action selectpicker"  data-live-search="true">
   <option value="">Choose Status</option>
   <option value="store">Store</option>
   <option value="half_out">Half out</option>
</select>
                                                    </div>
                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Start Date</label>
<input class="form-control " type="date" value="" id="start_date" placeholder="yyyy-mm-dd" >

                                                    </div>
                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Start Date</label>
<input  class="form-control dateselect" type="text" value="" id="end_date" placeholder="yyyy-mm-dd" >

                                                    </div>
                                                 <div class="col"style="padding-left:20px;">
						 <label class="form-label" for="validationCustom02">Search Info</label>
<input  class="sr-table-search form-control " type="text" value="" id="search" placeholder="search..." >

                                                    </div>
                                             </div>
                                  
                                    <div class="card-body">
				  <div class="table-responsive">
         
	  <table class="table mb-0" id="empTable">
           <thead class="table-light" id="table_light">
                <tr>
                   <th>Code</th>  
                   <th>Dispatch</th> 
					<th>Sender </th>               
                    <th>Receiver</th>
                   
				   <th>Date</th>
				  
  <th>Weight</th>
  <th>Detail</th>
  <th>Quantity</th>
    <th>To paid</th> 
	 <th>Paid</th> 
        <th>Amount</th> 

 
 

                </tr>
                </thead>

 
	
                    <tfoot id="load_total">

</tfoot>
                                            </table>
	   
                                    </div>   
                                
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
                <!-- End Page-content -->

         

				
      
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
		<script src="js/vendor/jquery-1.12.4.min.js"></script>
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

  <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
   <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>

	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  	
<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
		
		<script src="fetch_all_store.js<?php echo "?". modified_time("fetch_all_store.js")."";?>"></script>

  
        <!-- apexcharts init -->

    </body>
</html>
<script>

    $(document).ready(function(){
$('.selectpicker').select2({
    });	

	$('.dateselect').datepicker({
		todayHighlight: true,
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 	

    });
</script>
  
   