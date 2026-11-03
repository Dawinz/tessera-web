<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
//index.php
$connect = sr_mysqli('ticket');
$route_name2 = '';
$query = "SELECT route_name FROM assigned_routes GROUP BY route_name ORDER BY route_name ASC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $route_name2 .= '<option value="'.$row["route_name"].'">'.$row["route_name"].'</option>';
}
$users = '';
$query = "SELECT username FROM users ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $users .= '<option value="'.$row["username"].'">'.$row["username"].'</option>';
}

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('m/d/Y', time());
$date_now = date("Y-m-d", strtotime($date_today));
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
        <?php $tessera_page_label = 'My daily charges'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
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
	<link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" rel="stylesheet">

    </head>

    
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php include 'top_header.php';?>
	
    
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
                                    <h4 class="mb-0">Daily user ticket sales</h4>

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
	$route_id =$get14['route_id'];	

}}
			
					 ?></div>
					
						 <div  style="display:none;" id="users"><?php $user = @$_GET['user']; echo $user;?></div>
					
					
					 <div  style="display:none;" id ="start_date_select"><?php
					 $start_date= $_GET['start_date'];
					 $start_date = date("m/d/Y", strtotime($start_date));
					 echo $start_date;
					 ?>
					 </div>
					 <div  style="display:none;" id ="end_date_select"><?php 
					 
					$end_date =  $_GET['end_date'];
					 	 $end_date = date("m/d/Y", strtotime($end_date));
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
                                                <label class="form-label" for="default-input">Users</label>
                                                   <select class="form-select users" id ="users">
                                                           
															  <?php 
																					if (isset($_GET['user'])){
																						echo "<option value=''>".$_GET['user']."</option>";
																					}else{
																						echo "<option value=''>$username</option>";
																					}
																					
																					?>
                                                     <?php echo $users; ?>
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
<?php
$start_date = date("Y-m-d", strtotime($start_date));
$end_date = date("Y-m-d", strtotime($end_date));
?>
<div class="table-responsive">
<table class="table mb-0" id="summary">
<thead class="table-light">
<tr>                 

<th></th>
<th>Seat id</th>
<th>Bus</th>
<th>seat no</th>
<th>Fullname</th>
<th>Booking time </th>
<th>start_location </th>
<th>ending_location</th>
<th>Issure</th>
<th>Cost</th>

</tr>
				
                          </thead>
						  
						  <?php
$total_income =0;	
$select_booking=mysqli_query($db,"

SELECT 
	ROW_NUMBER() OVER (
		order by id desc 
	) row_num,
    firstname,
    id,
	lastname,
	seat_no,
	book_date,
	bus,
	validation,
	start_location,
	issuer,
	book_time,
	cost,
	end_route,
	start_route

FROM   booking WHERE " . tessera_sql_tenant_eq() . " AND (booker='$user'  and  book_date >= '$start_date' and  book_date <= '$end_date' and cost !=0 and delete_book ='') order by id desc

");
$select_num_booking=mysqli_num_rows($select_booking);
if($select_num_booking !=0){
   	WHILE($get_booking=mysqli_fetch_array($select_booking)){ 
	$total_income += $get_booking['cost'];	
	$row_num = $get_booking['row_num'];	
						  ?>
						  
			<tr>
			<td><?php echo $get_booking['row_num'];?></td>
			<td><?php echo $get_booking['id'];?></td>
			<td><?php echo $get_booking['bus'];?></td>
			<td><?php echo $get_booking['seat_no'];?></td>
			<td><?php echo "".$get_booking['firstname']." ".$get_booking['lastname']."";?></td>
			<td><?php echo "".$get_booking['book_date']." ".$get_booking['book_time']."";?></td>
			<td><?php echo $get_booking['start_route'];?></td>
			<td><?php echo $get_booking['end_route'];?></td>
			<td><?php echo $get_booking['issuer'];?></td>
						<td><?php echo number_format($get_booking['cost'],2);?></td>

			</tr>	
			
			<?php
}}
			?>
			
			
				<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Total</td>
			<td><?php echo number_format($total_income,2);?></td>
			</tr>	
			
                                          <!-- end tbody --> 
                                            </table><!-- end table --> 
											
										
											
											
                                        </div><!-- end card --> 
                            </div><!-- end col --> 
                        </div>
			  <!-- end page title -->

       
                                   
				
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
        
            </div>
            <!-- end main content-->


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
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
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
		<script src="my_daily_user_sales.js"></script>
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

	 var table = new DataTable('#summary', {
		   lengthMenu: [100],
		 				   "language": {
	     searchPlaceholder: "Search",
        search: "",
		
     processing: '<i style="position:relative;left:50%;"class="fa fa-spinner fa-spin fa-3x fa-fw"></i>Processing...'
},
ordering: false,

"order": [],
    layout: {
        topStart: {
            buttons: [
			 {
			 extend: 'copy',
			 footer: true, 
			 class: 'buttons-copy',
			 init: function(api, node,cofig){
			 }
			 },
			 { extend: 'csv', footer: true, class: 'buttons-csv',init: function(api, node,cofig){
			 }},
			 { extend: 'print', footer: true, class: 'buttons-print',init: function(api, node,cofig){
			
			 }},
			 { extend: 'pdf', footer: true, class: 'buttons-pdf',init: function(api, node,cofig){
			
			 }},
			{ extend: 'excel', text: 'Save as Excel', class: 'buttons-excel',init: function(api, node,cofig){
				
			 }}
			],
		

        }
    }
});
		});
        </script>