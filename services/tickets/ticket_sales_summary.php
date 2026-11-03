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
</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Ticket sales summary'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
 <link rel="stylesheet" type="text/css" href="css/datatable.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">
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
$query = "SELECT route_name FROM assigned_routes WHERE username='$username'";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $route_name2 .= '<option value="'.$row["route_name"].'">'.$row["route_name"].'</option>';
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
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Ticket sales Summary</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">Sales summary list</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                  
                    </div> 
					 <div  style="display:none;" id ="start_location_select"><?php $start_location_name = @$_GET['start_location'];
$select14=mysqli_query($db,"SELECT * FROM assigned_routes WHERE route_name ='$start_location_name'  and username='$username' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
WHILE($get14=mysqli_fetch_array($select14)){
	$route_id =@$get14['route_id'];	

}}
					echo "$route_id"; 
					 ?></div>
					
					 <div  style="display:none;" id ="start_date_select"><?php
					 $start_date= $_GET['start_date'];
					
					 echo $start_date;
					 ?>
					 </div>
					 <div  style="display:none;" id ="end_date_select"><?php 
					 
					$end_date =  $_GET['end_date'];
					
					 echo $end_date;
					 
					 ?></div>	
					 <div  style="display:none;" id ="end_year_select"><?php 
					 
					$end_year =  $_GET['end_date'];
					 	 $end_year = date("Y", strtotime($end_date));
					 echo $end_year;
					 
					 ?></div>
					 
					 
					 	 <div  style="display:none;" id ="start_year_select"><?php 
					 
					$start_date_year =  $_GET['start_date'];
					 	 $start_date_year = date("Y", strtotime($start_date_year));
					 echo $start_date_year;
					 
					 ?></div>
					 
					        <div class="btn-toolbar p-3" role="toolbar">
                                       <div class="mb-4">
                                                <label class="form-label" for="default-input">Route Name</label>
                                                   <select class="form-select route_name" id ="route_name">
                                                           
															  <?php 
																					if (isset($_GET['start_location'])){
																						echo "<option value=''>".$_GET['start_location']."</option>";
																					}else{
																						echo "<option value=''>Choose Route Name</option>";
																					}
																					
																					?>
                                                     <?php echo $route_name2; ?>
                                                        </select>
                                            </div>&nbsp &nbsp 
                                     

                                            <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">Starting Date</label>
                                                                                    <input class="form-control" type="date" value="<?php 
																					if (isset($_GET['start_date'])){
																						echo $_GET['start_date'];
																					}else{
																						echo $date_now;
																					}
																					
																					?>" id="start_date">

                                            </div>
                                            </div>    &nbsp                  <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">Ending Date</label>
                                                                                    <input class="form-control" type="date" value="<?php 
																					if (isset($_GET['end_date'])){
																						echo $_GET['end_date'];
																					}else{
																						echo $date_now;
																					}
																					
																					?>" id="end_date">

                                            </div>
                                            </div>       <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
   <label class="form-label" for="default-input">Search</label><br>
                                    <button type="button" id="search" class="btn btn-soft-success"><i class="uil uil-search-alt"></i> Search</button>

                                            </div>
                                            </div>    
			   
								
                                        </div>
					                   <div class="row">
                            <div class="col-lg-12">
                       <div class="card sr-table-panel" data-sr-loading-label="Loading sales summary...">
					   <div class="card-body p-0 pt-2">
                       <div class="table-responsive px-2 pb-3">
                                            <table class="table mb-0" id="example">
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
						                 	  <tfoot>
              <tr><td>TOTAL</td><td>
              
                		<?php

                      $start_date = date("Y-m-d", strtotime($start_date));  
                      $end_date = date("Y-m-d", strtotime($end_date));  
							  include_once 'connect.php';
							  
						
							  
							  
$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking  WHERE route_id ='$route_id'  and trip_date >='$start_date' and year='$start_date_year' and trip_date <='$end_date' and year='$end_year' and cost !='0' AND " . tessera_sql_tenant_eq());
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$cost=$data['cost'];
			echo number_format($cost,2);
		}}else{
			echo "Nothing";
		}
			
	
								?>
				
              
				
		
                         </td> </tr> 
						  
						  
			
		
						  
			
        </tfoot>
                                          <!-- end tbody --> 
                                            </table><!-- end table --> 
                                        </div>
										</div>
                                        </div><!-- end card --> 
                            </div><!-- end col --> 
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
		<script src="select_total_sale.js"></script>
<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>

  
        <!-- apexcharts init -->

    </body>
</html>
    <script>
        $(document).ready(function(){
var start_location_select = $("#start_location_select").text();

var start_date_select = $.trim($("#start_date_select").text());
var end_date_select = $.trim($("#end_date_select").text());

var end_year_select = $.trim($("#end_year_select").text());
var start_year_select = $.trim($("#start_year_select").text());


        $('#example').DataTable({
				
                'processing': true,
                'serverSide': true,
				
                'serverMethod': 'post',
				"order": [[ 0, "desc" ]],
				language: (window.TesseraStates && TesseraStates.listLanguage)
					? TesseraStates.listLanguage('No sales records', 'No matching sales')
					: undefined,
                'ajax': {
                'url':'fetch_ticket_sales.php',
				 type:"POST","data": {
        "start_location_select":start_location_select,"start_date_select":start_date_select,"end_date_select":end_date_select,"start_year_select":start_year_select,"end_year_select":end_year_select,
    },
				error: (window.TesseraStates && TesseraStates.loadError)
					? TesseraStates.loadError('Could not load sales report')
					: undefined
                },'columns': [
                    
				
					
                      { data: 'id' },
					  { data: 'bus' },
					  { data: 'seat_no' },
					  { data: 'fullname' }, 
					  { data: 'cost' },	
					   { data: 'book_date_time' },	
					  { data: 'start_location' },
                     { data: 'ending_location' },
									  
					  { data: 'issuer' },
				
					
					
              
                ],
				"lengthMenu": [[30, 35, 45, -1], [30, 35, 45, "All"]],
			dom: 'Bfrtip',
          buttons: [
            'copy', 'csv', 'excel', 'pdf',

 { extend: 'print', footer: true,
       
   autoPrint: true,
                title: '<p style="font-size:25px;"> ticket sales</p><div style="font-size:14px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Date : <?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());

echo $date_insert;
	?></div>',

                //For repeating heading.
                repeatingHead: {
                    logo: 'images/sr.jpg',
                    logoPosition: 'right',
                    logoStyle: '',
                    title: '<h3>Sample Heading</h3>'
                } },
        ], 



rowCallback: function(row, data, index) {
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
 $('.action').change(function(){
  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';
   if(action == "country")
   {
    result = 'state';
   }
  
   $.ajax({
    url:"fetch_dependence_sales.php",
    method:"POST",
    data:{action:action, query:query},
    success:function(data){
		
     $('#'+result).html(data);
    }
   })
  }
 });
        });
        </script>