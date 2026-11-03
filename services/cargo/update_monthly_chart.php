require_once __DIR__ . '/includes/sr_env.php';

<!doctype html>

<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Adaptor</title>
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
	     <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />

	  
        <!-- One of the following themes -->
   <!-- 'nano' theme -->

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" type="text/css" href="css/buttons.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />   
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
	  </style>
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->

            

    
            <!-- ========== Left Sidebar Start ========== -->

            <!-- Left Sidebar End -->

      

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

                  

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">User charges Detail</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of all user charge Detail</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						<?php
$connect = sr_mysqli('cargo');
$route_name2 = '';
$query = "SELECT date_insert FROM monthly_charges group by date_insert order by id desc";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $route_name2 .= '<option value="'.$row["date_insert"].'">'.$row["date_insert"].'</option>';
}

$routes_name = '';
$query2 = "SELECT route_full_name FROM routes  ";
$result2 = mysqli_query($connect, $query2);
while($row2 = mysqli_fetch_array($result2))
{
 $routes_name .= '<option value="'.$row2["route_full_name"].'">'.$row2["route_full_name"].'</option>';
}
						?>
						
						
						            <div class="parent">
  
</div>
				           
   <div class="row">
                            
                                <div class="card">
         <div class="row"style="margin:20px;">
		 <div class="table-responsive">
		 <table>
		 <tr>

		
		 <td>
	<label class="form-label" for="validationCustom02">Starting Date</label>

<select class="form-select route_name" id ="route_name">  
<?php 
if(isset($_GET['location'])){
	echo "<option>".$_GET['month']."</option>";
}

?>
                                                      
<?php echo $route_name2; ?>
</select>
                                                    
		 </td>	
		 <td>
	<label class="form-label" for="validationCustom02">Routes</label>

<select class="form-select chlocation" id ="chlocation">  
<?php
if(isset($_GET['location'])){
	echo "<option>".$_GET['location']."</option>";
}else{
	echo '<option value="Choose Location">Choose Location</option>';
}
?>

                                                  
<?php echo $routes_name; ?>
</select>
                                                    
		 </td>
	
	<td>
	

	</td>
		 </tr>
		 
		 
		 </table>
   </div>
         
                                     <!-- end col -->
                                                </div><!-- end row -->                          

					
                                    <div class="card-body">
	
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                        
                            <!-- end col -->
                        </div>
			  <!-- end page title -->

     
                        
                   <!-- container-fluid -->
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
     
                <!-- End Page-content -->

         

				
      
            
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

	   
	   <script src="search_adpotor_sec.js"></script>
	
	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>
  
        <!-- apexcharts init -->

    </body>
</html>
  <script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>

	<script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
	
	<script>
	
	$(document).ready(function(){
			$("#route_name").change(function(){ 
		var route = $( "#route_name option:selected" ).text();
		var chlocation = $( "#chlocation option:selected" ).text();
		if(chlocation !="Choose Location" ){
		window.location="update_monthly_chart.php?month="+route+"&location="+chlocation;
		}else{
			alert("Choose location");
		}

});
	});
	</script>
	<?php
	include 'connect.php';
	if(isset($_GET['month'])){
		$date_insert = $_GET['month'];
		$account_location = $_GET['location'];
	

			 
			 $select_charge=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE   month='$date_insert' and account='$account_location' and username !='' group by username");
$select_num_charge=mysqli_num_rows($select_charge);
if($select_num_charge !=0){
   	WHILE($get_charge=mysqli_fetch_array($select_charge)){
			
			$username_charge =$get_charge['username'];

	$sql = $db->query("SELECT SUM(to_be ) AS  to_be ,SUM(paid ) AS  paid  FROM parcel_info where   month='$date_insert' and account='$account_location' and username ='$username_charge' and open !='yes'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be =$data['to_be'];
				$paid =$data['paid'];
				echo ''.number_format($to_be ,2).'---'.$paid.' --- '.$username_charge.'<br>';
/*		
 $insert=mysqli_query($db,"INSERT INTO monthly_charges 
(to_be,location,date_insert,sort,username) VALUES 
('$to_be','$account_location','$date_insert','$newDate','$username_charge')"); 
*/
 $insert=mysqli_query($db,"UPDATE monthly_charges  SET to_be='$to_be',paid='$paid' WHERE  date_insert ='$date_insert' and  location='$account_location' and username='$username_charge'"); 
	   
	
		}}


}}
	
	}
	
	?>
