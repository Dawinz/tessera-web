<?php
require_once __DIR__ . '/includes/sr_env.php';
ob_start();
//index.php
$connect = sr_mysqli('cargo');
$country = '';
$query = "SELECT DISTINCT  name FROM vehicles  ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $country .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
?>
<!doctype html>
<style>

</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Manifest</title>
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
   <link rel="stylesheet" href="css/datapicker/datepicker3.css">
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
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Manifest Vehicle List</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of vehicles</li>
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
								                                    <div class="col-xl-3 col-sm-6"><br>
						 <label class="form-label" for="validationCustom02">Select Route</label>

                                                   <select name="route" id="route" class="form-select"   data-live-search="true"><?php
	if(isset($_GET['route'])){
		echo "  <option>".$_GET['route']."</option>";
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
                                                    </div>
													
	
													
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Vehicle lists</h4>
                                   
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#myModal"><i class="uil uil-plus "></i>Regsiter</button>

								   </div><!-- end card header -->
                                    <div class="card-body">
                                           <table class="table mb-0" id="empTable">
           <thead class="table-light">
             <tr>
        <th>Vehicle</th>
                
                    <th>Driver's Name</th>
                   <th>Conductor's Name</th> 
                 
 <th>To pay</th><th>Payed</th><th>Total</th><th>date</th>  <th>View</th><th>Sms</th> <th>id</th> 
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
                <!-- End Page-content -->

                       <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Manifest Vehicle registration</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
												<table>
												<tr >
												<td> <button style="margin:10px;"class="btn btn-outline-dark"id="open"><i class="fa fa-plus" aria-hidden="true"></i> Add vehicle</button><br></td>
												
												<td>
												
												   <div class="row" id="show"style="display:none;">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <div class="nk-int-st">
                                        <input type="text" style="margin:10px;"class="form-control" placeholder="Vehicle Name"id="name">
                                    </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <button id="save"class="btn btn-outline-dark"style="margin:10px;"><i class="notika-icon notika-checked"></i> Add</button>

                            </div>
                        </div>
					
												</td>


											
												</tr>
												</table>
												           
						 <label class="form-label" for="validationCustom02">Select Route</label>

                                                   <select name="route" id="route" class="form-select"  data-live-search="true"><?php
	if(isset($_GET['route'])){
		echo "  <option>".$_GET['route']."</option>";
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'");
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
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'");
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
									   ?></select><br>
  <div class="bootstrap-select fm-cmp-mg">
                                          <label class="nk-label">Vehicle Number</label>

                                    <select class="form-select"data-live-search="true"id="vehicle">
										<option>Enter Vehicle Name</option>
										<?php echo  $country;?>
										</select>
                                </div>      
  <br>  
                  <div class="row">
                       
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
                                        <label class="nk-label">Driver's Name</label>

                                        <input type="text" class="form-control"id="driver">
										<div id="view_place"style="position:absolute;background-color:white;z-index:1000;display:none;">
<br>
</div>
                                    </div>
                                </div> 
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
  <label class="nk-label"id='chide'>Conductor's name</label>
                                        <input type="text" class="form-control"id="conductor">
                                      <br>
                                    </div>
                                </div>
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">


                                <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
										
                                        <input type="text" class="form-control" id="time2">
                                    </div>
                                </div>
					<br>
                        <div class="material-design-btn">
                          
                            <button class="btn btn-primary"style="padding-top:10px;padding-bottom:10px;"id="savebtn2">Register Manifest</button>
                       
                        </div>
                    </div>                                    </div>
                                </div>

								
                                                    </div>
                                           

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>

										
						      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasRightLabel">UPDATE MANIFEST</h5>
                                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
											<div style="display:none;"id="id_update"></div>
                                            <div class="offcanvas-body">
                                      					<table>
												<tr >
												<td> <button style="margin:10px;"class="btn btn-outline-dark"id="open_update"><i class="fa fa-plus" aria-hidden="true"></i> Add vehicle</button><br></td>
												
												<td>
												
												   <div class="row" id="show_update"style="display:none;">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <div class="nk-int-st">
                                        <input type="text" style="margin:10px;"class="form-control" placeholder="Vehicle Name"id="name_update">
                                    </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <button id="save_update"class="btn btn-outline-dark"style="margin:10px;"><i class="notika-icon notika-checked"></i> Add</button>

                            </div>
                        </div>
					
												</td>


											
												</tr>
												</table>
												           
												   <div class="nk-int-mk sl-dp-mn sm-res-mg-t-10"><br>
                                  
                                </div>
  <div class="bootstrap-select fm-cmp-mg">
                                          <label class="nk-label">Vehicle Number</label>

                                    <select class="form-select"data-live-search="true"id="vehicle_update">
										<option id="vehicle_option"></option>
										<?php echo  $country;?>
										</select>
                                </div>      
  <br>  
                  <div class="row">
                       
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
                                        <label class="nk-label">Driver's Name</label>

                                        <input type="text" class="form-control"id="driver_update">
										<div id="view_place"style="position:absolute;background-color:white;z-index:1000;display:none;">
<br>
</div>
                                    </div>
                                </div> 
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
  <label class="nk-label"id='chide'>Conductor's name</label>
                                        <input type="text" class="form-control"id="conductor_update">
                                      <br>
                                    </div>
                                </div>
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">


                                <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
										
                                        <input type="text" class="form-control" id="time2_update">
                                    </div>
                                </div>
					<br>
								 <label class="form-label" for="validationCustom02">Select Route</label>

                                                   <select name="route" id="route_update" class="form-select"  data-live-search="true"><?php
	if(isset($_GET['route'])){
		echo "  <option>".$_GET['route']."</option>";
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'");
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
echo "<option>".$route_full_name."</option>";
}
}
	}else{								   
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'");
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
									   ?></select><br>
                        <div class="material-design-btn">
                          
                            <button class="btn btn-primary"style="padding-top:10px;padding-bottom:10px;"id="savebtn2_update">Update Manifest</button>
                       
                        </div>
                    </div>                                    </div>
                                </div>

                                            </div>
                                        </div>				
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
		<script src="insert_vehicle.js"></script>
		<script src="update_vehicle.js"></script>	
		<script src="route_selection_manifest.js"></script>
  <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
   <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>

<script type="text/javascript" charset="utf8" src="js/datatable.js"></script>

  
        <!-- apexcharts init -->

    </body>
</html>
  
    <script>
        $(document).ready(function(){
			 var route = $("#route option:selected" ).text();
            $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
				 "order": [[ 9, "desc" ]],
                'serverMethod': 'post',
                'ajax': {
                'url':'fetch_vehicle_view_clear.php',
										type:"POST","data": {
        "route":route
    }
                },'columns': [
					{ data: 'Name' },
					
					  { data: 'Driver' },
                    { data: 'Conductor' },
					
					
					  { data: 'to_pay' }, 
					  { data: 'paid' }, 
					  { data: 'total' },
					  { data: 'date' },
					  
					  { data: 'View' },
					
				
					  { data: 'sms' },
					  { data: 'id' }, 
					  
				
              
                ],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ], rowCallback: function(row, data, index) {
      if (data.status == "open") {
    	
$(row).css('background-color','white');
$(row).css('color','black');
      } else{
		$(row).css('background-color','white');  
	  }

    },
            });
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;
$('#time2').val(today);
        });

        </script>
		<?php
		ob_end_flush();
		?>