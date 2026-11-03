 <?php
include 'connect.php';
session_start();
$username=$_SESSION['username'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    
			$level =$get12['role']; 

		
	}
}
if($level=="Super Administrator"){
	
}else{
			 header("Location: register_luggage.php");

}
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
        <title>Charts and statistics</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    </head>

    
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
                             <div class="card ">
							 
														                                    <div class="col-xl-3 col-sm-6"style="margin:10px;">
																							
																						<h6 class="font-size-xs text-uppercase"> <i class="uil uil-chart-growth font-size-16 text-secondary me-2"></i>List of your assigned routes</h6>
					

                                                   <select name="route" id="route" class="form-select"   data-live-search="true"><?php
	if(isset($_GET['route'])){
		echo "  <option>".base64_decode($_GET['route'])."</option>";
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by id asc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){

}else if($select_num12 ==1){
	
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
		}
}else if($select_num12 >=2){

	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}
}
	}else{								   
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){

}else if($select_num12 ==1){
	
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
		}
}else if($select_num12 >=2){

	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}
}
	}
									   ?></select>
                                                    </div></div>
													<br>
                            </div>
                        </div>
                         <div class="row">
                        <div class="col-xl-3 col-sm-6">
						        <?php
			$uri = $_SERVER['HTTP_HOST'];
			
			?>
					<?php
			if(isset($_GET['route'])){
			$route2 = $_GET['route'];
			$route2 = $route2;
			}else{
			$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc limit 1 ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
		   	WHILE($get132=mysqli_fetch_array($select12)){
	$route_full_name =$get132['route_full_name']; 
	
			}
}	
$route2=base64_encode(@$route_full_name);

			}
			
			?>
                            <!-- Card -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="font-size-xs text-uppercase">Total Monthly charge</h6>
                                            
<div id="totol_cash"><div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:50px;"></span>
                                                    </div></div>
                                          
                                            <div class="text-muted">Total Monthly Ammount charges</div>
                                        </div>
                                        <div class="dropdown">
                                           <img src="images/sales.png"/>
                                            
                                            </div>
                                    </div>
                                        <?php 
										include 'http://'.$uri.'/cargo/total_monthly_charge_graph.php?route='.$route2.'';
										?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <!-- Card -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="font-size-xs text-uppercase"> Cash paid</h6>
                                          
                    <div id="load_paid_cash">
					<div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:50px;"></span>
                                                    </div>
													</div>
                                          
                                            <div class="text-muted">Total monthly cash paid amount</div>
                                        </div>
                                        <div>
                                            <div class="dropdown">
                                         <img src="images/seat_occupied.png">
                                            </div>
                                        </div>
									
                                    </div><?php 
										include 'http://'.$uri.'/cargo/total_monthly_paid_graph.php?route='.$route2.'';
										?></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <!-- Card -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="font-size-xs text-uppercase">To be Paid</h6>
                                           
<div id="load_to_paid"><div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:50px;"></span>
                                                    </div></div>                                          
                                            <div class="text-muted">Total Monthly To-be-paid Ammount</div>
                                        </div>
                                        <div>
                                            <div class="dropdown">
                                    <img src="images/route.png">
                                            </div>
                                        </div>
                                    </div>
                                  <?php 
										include 'http://'.$uri.'/cargo/total_monthly_to_be_graph.php?route='.$route2.'';
										?>
                                </div>
                            </div>
                        </div>
                     <div class="col-xl-3 col-sm-6">
                            <!-- Card -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="font-size-xs text-uppercase">Total charge luggage</h6>
                                         
<div id="total_number_l"><div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:50px;"></span>
                                                    </div></div>                                        
                                            <div class="text-muted">Total Monthly recipts quantity</div>
                                        </div>
                                          <div class="dropdown">
                                    <img src="images/like.png">
                                            </div>
                                    </div>
                                     <?php 
										include 'http://'.$uri.'/cargo/total_monthly_no_total.php?route='.$route2.'';
										?>
                                </div>
                            </div>
                        </div>
                    </div> 
			  <!-- end page title -->


             <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header bg-transparent">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                                               <h5>Your Assigned Routes - Monthly Charge summary in (Tsh)</h5>
                                <p>This contain monthly charge statics of your assigned routes</p>
                                            </div>
                              
                                        </div>
                                    </div>
                                          <div id="all_route_monthly">
<div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:300px; width:100%;"></span>
                                                    </div>
										  </div>

                                    <!-- end card body -->

                                    <div>
                                        <div id="chart-area" class="apex-charts" dir="ltr"></div>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div>             <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header bg-transparent">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                                               <h5>CHARGES AND PAYMENT STATUS MONTHLY TOTAL AMMOUNT</h5>
                                <p>This list consist of due payment to-pay and paid, for the whole month</p>
                                            </div>
                              
                                        </div>
                                    </div>
                                          <div id="mchart">
<div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:300px; width:100%;"></span>
                                                    </div>
										  </div>

                                    <!-- end card body -->

                                    <div>
                                        <div id="chart-area" class="apex-charts" dir="ltr"></div>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
            
                         
                            <!-- end col -->
                        </div>


                       <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
								 <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">DAILY CARGO CHARGES</h4>
                                     </div>
<div id="load_daily_inc"><div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:300px; width:100%;"></span>
                                                    </div></div>  

                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div>  
						<div class="row">
                            <div class="col-lg-12">
                                <div class="card">
								 <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Totol Recipts</h4>
                                     </div>
<div id="load_daily_recipt"><div class="placeholder-glow">
                                                        <span class="placeholder col-12  bg-warning"style="height:300px; width:100%;"></span>
                                                    </div></div>  

                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div>
   
						
	
                  
                </div>
                <!-- End Page-content -->

                <div id="route_name"style="display:none;"><?php echo $route2;?></div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/" target="_blank" class="text-reset">Sr classic coach</a>
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
  <script src="route_selection_option.js"></script>

        <!-- plugin js -->


        <!-- Calendar init -->
     
        
    <!-- apexcharts -->


    <script src="assets/js/app.js"></script>

        <!-- apexcharts init -->

    </body>
</html>
<script>

$(document).ready(function(){
	var route =$("#route_name").text();
$("#totol_cash").load("load_total_cash.php?route="+route);
$("#load_paid_cash").load("load_paid_cash.php?route="+route);
$("#load_to_paid").load("load_to_paid.php?route="+route);
$("#total_number_l").load("total_number_l.php?route="+route);
$("#mchart").load("load_monthly_chart.php?route="+route);
$("#load_daily_inc").load("daily_chart.php?route="+route);
$("#load_daily_recipt").load("load_daily_recipt.php?route="+route);
$("#all_route_monthly").load("load_assgine_monthly_graph.php?route="+route);





});
</script>