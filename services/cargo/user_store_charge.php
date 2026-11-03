
<!doctype html>

<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Store Inventory</title>
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
                                    <h4 class="mb-0">Store user luggages</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of luggages found in store</li>
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
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Luggages lists</h4>
                          

								   </div><!-- end card header -->
								   
								          <div class="row">
                                                    <div class="col-xl-3 col-sm-6">
						 <label class="form-label" for="validationCustom02">Select Route</label>

                                                   <select name="route" id="route" class="form-control action"  data-live-search="true"><?php
	if(isset($_GET['route'])){
echo "  <option>".$_GET['route']."</option>";
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc ");
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
                                                    </div><!-- end col -->
													<?php
													if(isset($_GET['route'])){
														$route = $_GET['route'];
														
													}else{
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc limit 1 ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
		   	WHILE($get132=mysqli_fetch_array($select12)){
	$route_full_name =$get132['route_full_name']; 
			}
}
														
														$route=$route_full_name;
													
													}
													?>
                                    <div class="card-body">
                                           <table class="table mb-0" id="empTable">
           <thead class="table-light">
            <tr>
                    
                  
                    <th>Receiver's name</th>
                     <th>Date</th>
<th>Detail</th> <th>Ammount</th><th>paid</th><th>To Be Payed</th> <th>Store Status</th> 
<th>Code</th> 
<th>q'left</th>

                </tr>
                </thead>
                	  <tfoot>
                   <tr>
                <th></th>
                
				
              
				<th>Total Quantity</td> <th>
				<?php
				include_once 'connect.php';
				$select2=mysqli_query($db,"SELECT *  FROM  parcel_info where store !='out'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
echo "$select_num2";
}
				?>
				</th>
		<th>To Be Payed</th><th>
								
								
							<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where   store = 'store' and username='$username'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amounti=$data['to_be'];
			
		}}else{
			echo "Nothing";
		}
								?>	
								<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain FROM parcel_info  where   store = 'half_out' and username='$username'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount7=$data['amount_remain'];
			$z= $amounti+ $amount7;
			echo number_format($z,2);
		}}else{
			echo "Nothing";
		}
								?>				
								</th><th>Paid</th><th>
							<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info   where   store= 'store' and username='$username' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amountp=$data['paid'];
		
			
		}}else{
			echo "Nothing";
		}
								?>			<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain FROM parcel_info   where   store = 'half_out' and status='paid' and username='$username' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount2=$data['amount_remain'];
			
			
		}}else{
			echo "Nothing";
		}
		$t= $amount2 + $amountp;
		$t2= $amount2 - $amountp;
		 echo ''.number_format($amountp,2).' <br>
		 +'.number_format($amount2,2).'(AR)
		 <br>'.number_format($t,2).'
		 ';
								?>
								</th>
								<th>Total </th><th>
													<?php
	$to = $t+$z;
	echo number_format($to,2);
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
		<script src="route_selection_user.js"></script>
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

  
        <!-- apexcharts init -->

    </body>
</html>
  
    <script>
        $(document).ready(function(){
			
	
			 var route = $("#route option:selected" ).text();
		
		var user = "<?php echo $username;?>";
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 7, "asc" ]],
                'ajax': {
            
				                'url':'fetch_user_see_all.php',
									 type:"POST","data": {
        "user":user,"route":route
    }
                },'columns': [
                    
				
					
                    { data: 'r_name' },
					 { data: 'date' },
					  { data: 'detail' }, 
					  { data: 'amount' },			
					  { data: 'paid' },			
					  { data: 'to_be' },
					  { data: 'store' },
					  { data: 'code' },
					  { data: 'left' }, 
					
				
					
					
              
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
            });
		
        });
        </script>