<?php
ob_start();
//index.php
$connect = mysqli_connect("localhost", "srclassic", "Teccross@360", "cargo");
$country = '';
$query = "SELECT DISTINCT  name FROM vehicles  ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $country .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}



?>

<?php
//index.php
$connect2 = mysqli_connect("localhost", "srclassic", "Teccross@360", "ticket");
$date_ticket = '';
$query2 = "SELECT trip_date FROM manifest GROUP BY trip_date ORDER BY id Desc";
$result2 = mysqli_query($connect2, $query2);
while($row2 = mysqli_fetch_array($result2))
{
 $date_ticket .= '<option value="'.$row2["trip_date"].'">'.$row2["trip_date"].'</option>';
}
include 'dependence/all_dependency.php';
?>
<style>
/* paginate styled by sr-platform-shell Book-list system */
#ui-datepicker-div {
  position: absolute !important;
  top: auto !important;
  left: auto !important;
}
.dataTables_filter {
    display: none;
}
</style>

<!doctype html>
<style>

</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Manifest</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <!-- fullcalendar css -->
        <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
	
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">

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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />   
<link rel="stylesheet" type="text/css" href="select_css.css">
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

						 <?php
	 $username=$_SESSION['username'];


$route_name_fullname = '';
$route_array=array();

$select2=mysqli_query($db,"SELECT route_full_name,status  FROM  assigned_routes where username='$username' order by preference desc");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $route_full_name =$get12['route_full_name'];  
    	    $status =$get12['status'];  
			
			
			$route_name_fullname .=  "Route='$route_full_name' OR ";
   $fullname_man="$route_full_name.$status";


			
	}
}




//echo $new_array;
$connect = mysqli_connect("localhost", "srclassic", "Teccross@360", "cargo");

	 $username=$_SESSION['username'];

$route_name_id = '';
$query = "SELECT route_full_name FROM assigned_routes where username='$username' order by preference desc";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 	$fullname = $row["route_full_name"];
  $route_name_id .= "<option value=\"(Route='$fullname') AND \">$fullname</option>";
}


$date_now = date('Y-m-d', time());




	 ?>
              
   <div class="row">
                            
                                <div class="card">
								                             
													
									
													
                          
<table
style="
  font-family:Arial;
  border-collapse: separate;
  border-spacing: 10px;
  margin-left: auto;
  margin-right: auto;
"
>
<tr>
<td>
<select name="vehicle" id="vehicle_name_selected" class="form-control selectpicker "  style="width:210px;" data-live-search="true"><option value=""> Choose Vehicle</option><?php vehicle($db); ?></select>
</td>
<td>
<select name="route" id="route" class="form-control selectpicker "  style="width:210px;" data-live-search="true"><option value=""> Choose Route</option><?php echo $route_name_id ; ?></select>
</td>
<td>
<input style="width:200px;" class="form-control dateselect" type="text" value="" id="start_date" placeholder="yyyy-mm-dd" >
</td>
<td>
<input style="width:200px;" class="form-control dateselect" type="text" value="" id="end_date" placeholder="yyyy-mm-dd">
</td>
<td>
<input style="width:200px;" class="sr-table-search form-control " type="text" value="" id="search" placeholder="Search...">
</td>
<td>

</td>
<td></td>
<td><button type="button"class="btn btn-purple"data-bs-toggle="modal"
                                        data-bs-target=".bs-example-modal-center"><i class="uil uil-plus "></i>Regsiter Manifest </button> </td>
<td></td>
</tr>
</table>
								   </div><!-- end card header -->
								   
									<div class="table-responsive">
                                           <table class="table mb-0" id="empTable">
           <thead class="table-light">
                <tr>
        <th>ID</th>
        <th>Route</th>
        <th>Vehicle</th>
		<th>Date</th> 
        <th>Driver</th>
        <th>Conductor</th>
		<th>To be paid</th>
		<th>Paid</th>
		<th>Total</th>
		<th>Share</th>
		<th>View</th>
		<th>Check out </th>
		<th>Insert </th>
		<th>Sms</th> 
		<th>Edit</th>
		<th>summary</th>
		<th>Delete</th>
	
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

     
                        
                   <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/" target="_blank" class="text-reset">Pichforest</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
                <!-- End Page-content -->

                       <?php include 'modal/manifest_registration.php';?>

										
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
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' ");
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
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by id desc");
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

   <input type="hidden"value="<?php echo $route_name_fullname;?>" id="all_routes">

										
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/" target="_blank" class="text-reset">Pichforest</a>
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

<?php

 $route_name_fullname = substr($route_name_fullname, 0, -3);
$route_name_fullname="($route_name_fullname) and  ";

 ?>
             
            </div>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>

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
		<script src="insert_vehicle_ticket.js"></script>
		<script src="insert_vehicle.js"></script>
		<script src="update_vehicle.js"></script>	
		
  <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
   <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>

<script type="text/javascript" charset="utf8" src="js/datatable.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  	

  
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
	
		route_name_select = "<?php echo $route_name_fullname;?>";
		start_date = "";
		end_date = "";
        vehicle ="";
	
								 fetch_data(route_name_select,start_date,end_date,vehicle);		

							function fetch_data(route_name_select,start_date,end_date,vehicle)
 {
		
				 var route = $("#route option:selected" ).text();

            $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
								   "language": {
	     searchPlaceholder: "Search...",
        search: "",
     processing: '<i style="position:absolute;left:50%;top:200px;color:black;"class="fa fa-spinner fa-spin fa-3x fa-fw"></i>Processing...'
},
                'serverMethod': 'post',
                'ajax': {
                'url':'fetch_vehicle_view.php',
						type:"POST","data": {
        "route":route,
        "route_name_select":route_name_select,
        "start_date":start_date,
        "end_date":end_date,
        "vehicle":vehicle
    }
                },'columns': [
					{ data: 'code' },
					{ data: 'Route' },
					{ data: 'Name' },
										  { data: 'date' },

					  { data: 'Driver' },
                    { data: 'Conductor' },
					
					
					  { data: 'to_pay' }, 
					  { data: 'paid' }, 
					  { data: 'total' },
					  { data: 'pdf' },
					  
					  { data: 'View' },
					  { data: 'check_out' }, 
					  { data: 'Insert' },
					  { data: 'sms' },
					  { data: 'edit_manifest' }, 
					  { data: 'user' }, 
					  { data: 'Delete' }, 
					  
				
              
                ],
				dom: 'Bfrtip',
				"lengthMenu": [120],
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ], rowCallback: function(row, data, index,td) {
      if (data.Route == "") {	
$(td).css('background-color','red');
$(row).css('color','black');
      } else{
		$(row).css('background-color','white');  
	  }

    },


            });
            	 oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#search').keyup(function(){
      oTable.search($(this).val()).draw() ;
});
 }
 $("#route").change(function(){
	 $('#empTable').DataTable().destroy();
		var route_name_select = $("#route").val();
		start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        vehicle = $("#vehicle_name_selected").val();
fetch_data(route_name_select,start_date,end_date,vehicle);		
	});
	
 $("#vehicle_name_selected").change(function(){
start_date = $("#start_date").val();
end_date = $("#end_date").val();
route_name_select = $("#route").val();
   vehicle = $("#vehicle_name_selected").val();
    $('#empTable').DataTable().destroy();
fetch_data(route_name_select,start_date,end_date,vehicle);	
	});
	
	
$("#start_date").change(function(){
start_date = $("#start_date").val();
end_date = $("#end_date").val();
route_name_select = $("#route").val();
vehicle = $("#vehicle_name_selected").val();
	 $('#empTable').DataTable().destroy();
fetch_data(route_name_select,start_date,end_date,vehicle);		
});
$("#end_date").change(function(){
start_date = $("#start_date").val();
end_date = $("#end_date").val();
route_name_select = $("#route").val();
	 $('#empTable').DataTable().destroy();
     vehicle = $("#vehicle_name_selected").val();
fetch_data(route_name_select,start_date,end_date,vehicle);	
});
	
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;
$('#time2').val(today);
        });

        </script>
		
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
	$('#car').val(query);
   }
   if (action == "city")
   {
    result = 'city';
   }
   $.ajax({
    url:"fetch_dependent_ticket.php",
    method:"POST",
    data:{action:action, query:query},
    success:function(data){
     $('#'+result).html(data);

    }
   })
  }
 });
 $('#state').change(function(){
var state = $( "#state option:selected" ).text();
$('#vehicle').val(state);
 });
});
</script>
		
		<?php
		ob_end_flush();
		?>