<?php
require_once __DIR__ . '/includes/sr_env.php';
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
<!doctype html>

<html lang="en">

<?php
error_reporting(E_ERROR | E_PARSE);
//index.php
$connect = sr_mysqli('cargo');
$country = '';
$query = "SELECT trip_date FROM manifest GROUP BY trip_date ORDER BY MAX(id) DESC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $country .= '<option value="'.$row["trip_date"].'">'.$row["trip_date"].'</option>';
}
?>
<?php

                    
$date1= @$_GET['date'];
$date2= @$_GET['vehicle'];
$date3= @$_GET['users'];




include 'connect.php';
$select222=mysqli_query($db,"SELECT *  FROM  users  where username='$date3'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $fullname =$get122['fullname']; 

		
	}
}
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());
?>
    <head>
        
        <meta charset="utf-8" />
        <title>User Charges</title>
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
      <link rel="stylesheet" type="text/css" href="css/buttons.css">
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
<?php
include'connect.php';
$username=$_SESSION['username'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $level =$get12['level']; 

		
	}
}
?>
    
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
                                    <h4 class="mb-0">User per manifest charge list</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of all user Manifest summary</li>
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
                            
                                <div class="card sr-table-panel">
         <div class="row">
                                                    <div class="col-xl-3 col-sm-6">
						 <label class="form-label" for="validationCustom02">Manifest Date</label>

                                                   <select name="country" id="country" class="form-control action"  data-live-search="true">
 <?php
	 if(isset($date1)){
	echo '<option value="vehicle">'.$date1.'</option>';
	
	}else{
	echo '<option>Select Trip Date</option>';	
	}
	 
	 ?>
    <?php echo $country; ?>
										
										
										</select>
                                                    </div><!-- end col -->
                                                    <div class="col-xl-3 col-sm-6">
<label class="form-label" for="validationCustom02">Vehicle</label>

<select name="state" id="state" class="form-control action"data-live-search="true">
   
	 <?php
	 if(isset($date2)){
	echo '<option value="vehicle">'.$date2.'</option>';
	
	}else{
	echo '<option>Vehicle</option>';	
	}
	 
	 ?>
   </select>
                                                    </div><!-- end col -->
                                                    <div class="col-xl-3 col-sm-6">
 <label class="form-label" for="validationCustom02">UserName</label>

      <select name="city" id="city2" class="form-control ">
	 
	 <?php
	 if(isset($date3)){
	echo '<option value="vehicle">'.$date3.'</option>';
	
	}else{
	echo '<option>Choose system user</option>';	
	}
	 
	 ?>
	 
	  
	  
	  
	  
    															<?php
include 'connect.php';																
																$select1=mysqli_query($db,"SELECT *  FROM users ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $u_name=$get13['username']; 
			echo '<option value="vehicle">'.$u_name.'</option>';
}}}
											?>
   </select>
						<div id="start_month"style="display:none;"><?php
				
if(isset($_GET['date'])){
$date1= @$_GET['date'];
$start_month = date('m/Y', strtotime($date1));

echo $start_month;
}
	?></div>	
	
	<div id="end_month" style="display:none;"><?php
				
if(isset($_GET['date2'])){
$date2= @$_GET['date2'];
$end_month = date('m/Y', strtotime($date2));

echo $end_month;
}
	?></div>
                                                    </div><!-- end col -->
                                                    <div class="col-xl-3 col-sm-6">
													
                                            <button style="margin-top:30px;" type="button" id="check_user"class="btn btn-outline-primary">Search</button>
                                                    </div> <!-- end col -->
                                                </div><!-- end row -->                          
			
                                    <div class="card-body">
                                           <table class="table mb-0" id="empTable">
           <thead class="table-light">
               <tr>
                   
					
					<th>Name of Owner</th>
					<th>Description of Goods</th>
                  
                    <th>AMMOUNT</th>
					<th>To be paid</th>
					<th>Paid</th>
					<th>Date</th>
					<th>Cargo rec.no</th>
					  <th>Exported Quantity</th>
					  <th>Action</th> 
                </tr>
                </thead>
	  <tfoot>
              <tr>
                <th>Total receipt</th>  <th>
				<?php
				$username = $_SESSION['username']
				?>

					<?php
                    if(isset($_GET['date'])){
				include_once 'connect.php';
				$select2=mysqli_query($db,"SELECT *  FROM  manifest where parcel_uploader='$date3'and trip_date ='$date1' and bus ='$date2'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
echo "$select_num2";
}}else{
    echo "0.00";
}
				?>
				</th>
                
				
              
				
		<th>Total amount</th><th>
													<?php
                                                    if(isset($_GET['date'])){
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where parcel_uploader='$date3'and trip_date ='$date1' and bus ='$date2'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
				echo number_format($total_amount_t ,2);
			
		}}else{
			echo "Nothing";
		}}else{
            echo "0.00";
        }
								?>
								</th><th>To Be Payed</th><th>
								
								
							<?php
                            if(isset($_GET['date'])){
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM manifest  where parcel_uploader='$date3'and trip_date ='$date1' and bus ='$date2'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['to_be'];
				echo number_format($amount,2);
			
		}}else{
			echo "Nothing";
		}
    }else{
        echo "0.00";
    }
								?>				
								</th><th>Paid</th><th>
							<?php
                            if(isset($_GET['date'])){
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid FROM manifest   where parcel_uploader='$date3'and trip_date ='$date1' and bus ='$date2'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['paid'];
				echo number_format($amount,2);
			
		}}else{
			echo "Nothing";
		}}else{
            echo "0.00";
        }
								?>
								</th>
                          </tr> 
        </tfoot>
	
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
		<script src="insert_vehicle.js"></script>
		<script src="update_vehicle.js"></script>
  <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
   <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>

	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>

<div id="date1_exp"style="display:none;"><?php
$date1= @$_GET['date'];
echo $date1;
?></div>
					 <div id ="date2_exp"style="display:none;"><?php
$date2= @$_GET['vehicle'];
echo $date2;
?></div>					 <div id ="date3exp"style="display:none;"><?php
$date3= @$_GET['users'];
echo $date3;
?></div>
			
  
        <!-- apexcharts init -->

    </body>
</html>
  
    <script>
        $(document).ready(function(){
			var date1_exp = $('#date1_exp').text();
			var date2_exp = $('#date2_exp').text();
			var date3exp = $('#date3exp').text();
			
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 2, "desc" ]],
                'ajax': {
                'url':'fetch_user_manifest.php',
									 type:"POST","data": {
        "date1_exp":date1_exp,"date2_exp":date2_exp,"date3exp":date3exp,
    }
                },'columns': [
               { data: 'username' },
					{ data: 'detail' },
                    
					{ data: 'amount' },
					{ data: 'to_be' },	
					{ data: 'paid' },
					{ data: 'date' },
					{ data: 'code' },
                     { data: 'left' }, 
					 { data: 'action' }, 
       
                 
                ],
				"lengthMenu": [[100, 125, 150, -1], [100, 125, 150, "All"]],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
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
            });
			var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;
$('#date').val(today);
$('#date2').val(today);

$("#check_user").click(function(){
	var trip_date = $( "#country option:selected" ).text();
	var vehicle = $( "#state option:selected" ).text();
	var users = $( "#city2 option:selected" ).text();
	
if(trip_date =='Select Trip Date'){
	swal("Enter Trip Date!");
				 			 control.disabled="disabled";

}
if(vehicle =='Select Vehicles'){
	swal("Enter Vehicel Name!");
				 			 control.disabled="disabled";

}if(users =='Choose system user'){
		swal("Enter user Name!");
					 			 control.disabled="disabled";

}
$("#check").text("checking...");
url = "manifest_per_user.php?date="+trip_date+"&vehicle="+vehicle+"&users="+users+"";
 window.location = url;
	});
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
    url:"fetch_dependent.php",
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
<script>
$(document).on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
	var trip_date = $( "#country option:selected" ).text();
	var vehicle = $( "#state option:selected" ).text();
	var users = $( "#city2 option:selected" ).text();
if(trip_date =='Select Trip Date'){
	swal("Enter Trip Date!");
				 			 control.disabled="disabled";

}
if(vehicle =='Select Vehicles'){
	swal("Enter Vehicel Name!");
				 			 control.disabled="disabled";

}if(users =='Choose system user'){
		swal("Enter user Name!");
					 			 control.disabled="disabled";

}
url = "manifest_per_user.php?date="+trip_date+"&vehicle="+vehicle+"&users="+users+"";
 window.location = url;
    }
});



</script>