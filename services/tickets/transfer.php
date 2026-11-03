<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
?>
<!doctype html>

<html lang="en">

<?php

$start_month="";
$end_month="";
?>
    <head>
        
        <meta charset="utf-8" />
        <title>Transfer Manifest</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
 
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	     <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />

	  
        <!-- One of the following themes -->
   <!-- 'nano' theme -->

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

 
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>  
  
  </head>

    <style>

button.dt-button, div.dt-button, a.dt-button, input.dt-button {
    border-radius: 2px;
    color: black;
    
}

</style>
          <style>
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
#seat_no{
	font-size:13px;
	
	color:white;
	border-radius:11px;
	padding:2px;
	font-weight:bold;
}
	  </style>
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

      <?php
include 'connect.php';
session_start();
$username=$_SESSION['username'];
 if(isset($username)){
	 
 }else{
		 header("Location: log_out.php");
 
 }
$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			$firstStringCharacter = substr($surname, 0, 1);
			$fullname= "$firstname.$firstStringCharacter.";
			$level =$get12['role']; 
$image = $get12['image']; 

		
	}
}else{
	 header("Location: log_out.php");
}
?>
	                <?php
//index.php
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT start_route FROM assigned_routes WHERE username='$username' GROUP BY start_route ORDER BY start_route ASC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["start_route"].'">'.$row["start_route"].'</option>';
}
 $seats=0;
 
 
 
 	
		$route_id = isset($_GET['id']) ? (string) $_GET['id'] : '';
		$trip_date = isset($_GET['trip_date']) ? (string) $_GET['trip_date'] : '';
		$route_id_param = isset($_GET['route_id']) ? (string) $_GET['route_id'] : '';
		$select1450=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id
	) row_num,
    firstname,
    id,
	lastname,
	seat_no,
	phone_no,
	validation,
	start_location,
	ending_location,bus
FROM 
	booking WHERE   route_day_id='$route_id' and trip_date='$trip_date' and status !='booked'
ORDER BY 
	id 
		
		");
$select_num1450=mysqli_num_rows($select1450);
?> 


    
            <!-- ========== Left Sidebar Start ========== -->

            <!-- Left Sidebar End -->

      

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
      <div class="main-content">


                        <!-- start page title -->
   
              
                            
                                <div class="card">
         <div class="row"style="margin:20px;">
		                     <div class="col-xl-3 col-sm-6"><br>
						  <a href="manifest.php"  class=" btn btn-soft-warning">Back to Manifest <i class="mdi mdi-arrow-left align-middle"></i></a>

                                                    </div>

		 <table>
		 <tr>
	
	
	 <td style="padding:10px;">  
 <label class="form-label" for="validationCustom02">Select Starting Point</label>
		  <select class="form-select shadow-none action" name="category"id="event-category country" required>
																	<option>Choose starting Point</option>
                                                                     <?php echo $start_route2; ?>
                                                                </select></td>
																<td style="padding:10px;">				 <label class="form-label" for="validationCustom02">Select Ending Point</label>

                                            <select class="form-select shadow-none" name="category"
                                                                    id="state" required>
                                                                  
                                                                </select></td>
		 <td style="padding:10px;"><label class="form-label" for="validationCustom02">Trip Date</label>

                                                                                  <input type="text" id="date2"class="form-control dateselect" placeholder="date"value ="<?php
				

						date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('Y-m-d', time());
echo $date_today;

	?>"/>


                                                   </td>
	<td>
	
	                                            <button style="margin-top:30px;" type="button" id="search"class="btn btn-primary"><span class="search_text">Search</span></button>

	</td>
		 </tr>
		 
		 
		 </table>
                                <div style="display:none;" class="p-3 mb-2 bg-success bg-gradient text-white seat_holder"></div>

  <div class=" justify-content-between d-flex align-items-center" style="padding:10px;">
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="transfer_all"><i class="uil  uil-arrow-random"></i> <span id="holder">Transfer All</span> <span class="badge bg-danger ms-1"><?php echo $select_num1450;?></span><span class="position-absolute top-0  translate-middle badge rounded-pill bg-success "id="total_transferd">0<span class="visually-hidden"></span></span> <span style="display:none;"class="badge bg-success ms-1 checked_seats">0</span></button>
                                    <button type="button" class="btn btn-outline-success btn-sm"><i class="uil uil-check me-2"></i> Selected Seats <span class="badge bg-success ms-1 select_seats">0</span><span style="display:none;"class="badge bg-success ms-1 select_seats_update">0</span></button>
                            </div> 
                <div class="row">
  <div class="col ">
  
  <div id="load_current_seats"></div>
  </div>
  <div class="col">
   <div class="card-header justify-content-between d-flex align-items-center badge badge-soft-primary">
                                    <h4 class="card-title">
									<?php  
									$tripRaw = isset($_GET['trip_date']) ? (string) $_GET['trip_date'] : '';
									$tripTs = $tripRaw !== '' ? strtotime($tripRaw) : false;
									if ($tripTs && (int) date('Y', $tripTs) > 1970) {
										echo date('l jS \of F Y', $tripTs) . "'s seat chart";
									} else {
										echo 'Seat chart - open a trip from Manifest';
									}
									?>
									</h4>
                                  
                                </div>
  <div id="load_current_seats_chart"></div>
  </div>
  <div class="vr"style="width:1px;"></div>
  <div class="col">
   <div class="card-header justify-content-between d-flex align-items-center badge badge-soft-primary">
                                    <h4 class="card-title"id="passenger_list">Seat Chart</h4>
                                   
                                </div>
  <div id="load_new_seats_chart"></div>

</div>
  <div class="col">
  

        <div id="load_new_seats"></div>

  </div>
</div>
             
                                     <!-- end col -->
                                                </div><!-- end row -->                          

                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                        
                            <!-- end col -->
              
			  <!-- end page title -->
<div style="display:none"id="route_day_id_old"><?php echo htmlspecialchars($route_id, ENT_QUOTES, 'UTF-8'); ?></div>
<div style="display:none" id="route_id_old"><?php echo htmlspecialchars($route_id_param, ENT_QUOTES, 'UTF-8'); ?></div>
<div style="display:none;"id="trip_date_old"><?php echo htmlspecialchars($trip_date, ENT_QUOTES, 'UTF-8'); ?></div>
<div style="display:none"id="route_day_id_new_one"></div>
<div style="display:none" id="route_id_new_one"></div>
<div style="display:none;"id="trip_date_new_one"></div>
     
                        
     
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

        <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog"
                                            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myExtraLargeModalLabel">Chooose Vehicles</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                 <div id="load_trip_vehicles">adsf</div>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>

				
      
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
	

        <!-- plugin js -->
       
        <!-- Calendar init -->
        

	   		<script src="js/sweetalert.js"></script>

	   <script src="select_transfer_date.js"></script>
	   <script src="transfer_all.js"></script>
	
	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>
  
        <!-- apexcharts init -->

    </body>
</html>


	<script src="assets/js/alertify.min.js"></script>
<script>
$(document).ready(function(){
 $('.action').change(function(){
  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';
   if(action == "event-category country")
   {
    result = 'state';
   }
  
   $.ajax({
    url:"fetch_dependence.php",
    method:"POST",
    data:{action:action, query:query},
    success:function(data){
     $('#'+result).html(data);
    }
   })
  }
 });


	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 

var id="<?php echo htmlspecialchars($route_id, ENT_QUOTES, 'UTF-8'); ?>";
route_id="<?php echo htmlspecialchars($route_id_param, ENT_QUOTES, 'UTF-8'); ?>";
trip_date="<?php echo htmlspecialchars($trip_date, ENT_QUOTES, 'UTF-8'); ?>";
$("#load_current_seats").load("load_current_seats.php?route_day_id="+id+"&route_id="+route_id+"&trip_date="+trip_date+"");
$("#load_current_seats_chart").load("load_current_seats_chart.php?route_day_id="+id+"&route_id="+route_id+"&trip_date="+trip_date+"");
});
</script>
