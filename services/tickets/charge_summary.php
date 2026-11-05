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
        <title>Ticket sales</title>
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
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
 <link rel="stylesheet" type="text/css" href="css/datatable.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">
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
                                    <h4 class="mb-0">User Ticket sales Summary</h4>

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
$select_num14=(($select14 instanceof mysqli_result) ? mysqli_num_rows($select14) : 0);
if($select_num14 !=0){
WHILE($get14=mysqli_fetch_array($select14)){
	$route_id =$get14['route_id'];	

}}
					echo "$route_id"; 
					 ?></div>
					
						 <div  style="display:none;" id="users"><?php $user = @$_GET['user']; echo $user;?></div>
					
					
					 <div  style="display:none;" id ="start_date_select"><?php
					 $start_date_raw = $_GET['start_date'] ?? $date_now;
					 $start_ts = strtotime((string) $start_date_raw) ?: time();
					 $start_date = date('m/d/Y', $start_ts);
					 echo htmlspecialchars($start_date, ENT_QUOTES, 'UTF-8');
					 ?>
					 </div>
					 <div  style="display:none;" id ="end_date_select"><?php
					 $end_date_raw = $_GET['end_date'] ?? $date_now;
					 $end_ts = strtotime((string) $end_date_raw) ?: time();
					 $end_date = date('m/d/Y', $end_ts);
					 echo htmlspecialchars($end_date, ENT_QUOTES, 'UTF-8');
					 ?></div>
					 <div  style="display:none;" id ="end_year_select"><?php
					 $end_year = date('Y', $end_ts);
					 echo htmlspecialchars($end_year, ENT_QUOTES, 'UTF-8');
					 ?></div>
					 	 <div  style="display:none;" id ="start_year_select"><?php
					 $start_date_year = date('Y', $start_ts);
					 echo htmlspecialchars($start_date_year, ENT_QUOTES, 'UTF-8');
					 ?></div>
					 
					        <div class="btn-toolbar p-3" role="toolbar">
                             
                                     

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
   <label class="form-label" for="default-input"><span class='searching_word'>Search</span></label><br>
                                    <button type="button" id="search" class="btn btn-soft-success"><i class="uil uil-search-alt"></i> Search</button>

                                            </div>
                                            </div>    
			   
								
                                        </div>
					                   <div class="row">
                           <div class="card">
                       <div class="table-responsive">
					   <div class="card-body">
                                            <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 

<th>Starting Point</th>
<th>Ending Point</th>
<th>Total cost</th>




</tr>
				
                          </thead>
						  
						  <tbody>
<?php 
$select146=mysqli_query($db,"SELECT * FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >='$start_date' and year='$start_date_year' and trip_date <='$end_date' and year='$end_year' and cost !='0') group by booker ");
$select_num146=(($select146 instanceof mysqli_result) ? mysqli_num_rows($select146) : 0);
if($select_num146 !=0){
WHILE($get146=mysqli_fetch_array($select146)){
	$booker =$get146['booker'];
	
	$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$booker'");
$select_num2=(($select2 instanceof mysqli_result) ? mysqli_num_rows($select2) : 0);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
	$fullname  = "$firstname $surname";

		
	}
}
	
	
	
	$select1463=mysqli_query($db,"SELECT * FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >='$start_date' and year='$start_date_year' and trip_date <='$end_date' and year='$end_year' and cost !='0' and booker='$booker') group by route_id");
$select_num1463=(($select1463 instanceof mysqli_result) ? mysqli_num_rows($select1463) : 0);
if($select_num1463 !=0){
WHILE($get1463=mysqli_fetch_array($select1463)){

	$route_id  =$get1463['route_id'];

}}
	
	
	?>
	<tr >
	

	<td ><?php echo strtoupper($fullname);?></td>
	<td > </td>
	<td ></td>
	


	</tr>
	
	

					
	
	
	<?php
	
	
		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'  ");
$select_num14=(($select14 instanceof mysqli_result) ? mysqli_num_rows($select14) : 0);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){

			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			
			$sql = $db->query("SELECT  trip_date,bus, SUM(cost) AS  cost FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (booker = '$booker' and route_id ='$route_id'  and trip_date >='$start_date' and year='$start_date_year' and trip_date <='$end_date' and year='$end_year' and cost !='0')");
		$rows=$sql->num_rows;
		
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$cost=$data['cost'];
				$trip_date=$data['trip_date'];
				$bus=$data['bus'];
		
		}}else{
		}
			

		?>
		
			<tr>
	

	<td ><?php echo $start_route;?></td>
	<td ><?php echo $end_route;?></td>
	<td ><?php echo number_format($cost,2);?></td>



	</tr>
		
		<?php
		}}else{
		}
	

		$sql = $db->query("SELECT  trip_date,bus, SUM(cost) AS  cost FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (booker = '$booker' and trip_date >='$start_date' and year='$start_date_year' and trip_date <='$end_date' and year='$end_year' and cost !='0')");
		$rows=$sql->num_rows;
		
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$cost_total=$data['cost'];
				
		
		}}else{
		}
?>
			<tr>
	
	
	<td ></td>
	<td style="font-weight:bold;">Total</td>
	<td style="font-weight:bold;"><?php echo number_format($cost_total,2);?></td>
	
	

	</tr>

<?php


}}
					
		$sql = $db->query("SELECT  trip_date,bus, SUM(cost) AS  cost FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >='$start_date' and year='$start_date_year' and trip_date <='$end_date' and year='$end_year' and cost !='0')");
		$rows=$sql->num_rows;
		
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$cost=$data['cost'];
				
		
		}}else{
		}
			
?>
	<td style="font-weight:bold;">Grand total</td>
	<td style="font-weight:bold;"><?php echo number_format($cost,2);?></td>
<?php
					
						  ?>
						  

						  
						  
						  </tbody>
						  
						  
						 
                                          <!-- end tbody --> 
                                            </table></div><!-- end table --> 
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
		<script src="select_user_charge.js"></script>
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