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
<!doctype html>

<html lang="en">

<?php

$start_month="";
$end_month="";
?>
    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Total charges'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
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
                                    <h4 class="mb-0">User charges Summary</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of all user charge Summary</li>
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
                  <?php
				 if (empty($_GET['firstday'])) {
					 $_GET['firstday'] = date('Y-m-01');
					 $_GET['lastday'] = date('Y-m-t');
					 $_GET['route'] = 'Kampala_to_Lubumbashi';
				 }
				 if(isset($_GET['firstday'])){
					 $firstday = @$_GET['firstday'];
			   $lastday = @$_GET['lastday']; 
			   $username_choosen = @$_GET['trip'];
			   $route = @$_GET['route'];
		       $route = preg_replace('/_/',' ', $route);
				 }else{
					$firstday = "";
			   $lastday = ""; 
			   $username_choosen = "";
			   $route = "";
		       $route = ""; 
				 }
			   


			   ?>
   <div class="row">
                            
                                <div class="card sr-table-panel">
         <div class="row"style="margin:20px;">
		 <table>
		 <tr>
		 <td>  
 <label class="form-label" for="validationCustom02">Select Route</label>
		 <select name="route" id="route" class="form-control selectpicker"  data-live-search="true"><?php
							if(isset($_GET['firstday'])){
			  $route = @$_GET['route'];
			$route = preg_replace('/_/',' ', $route);
echo '<option value="vehicle">'.$route.'</option>';

		}else{
			
		}		   
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' and preference='1' order by id desc");
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
	
									   ?></select></td>
	
		 <td>
	<label class="form-label" for="validationCustom02">Starting Date</label>

													
                                                                                          <input type="text" id="date"class="form-control dateselect" placeholder="date"value ="<?php
				
if(isset($_GET['firstday'])){
	echo $firstday;
	

}else{

							date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('Y-m-d', time());
echo $date_today;
}

	?>">
                                                    
		 </td>
		 <td><label class="form-label" for="validationCustom02">Ending Date</label>

                                                                                  <input type="text" id="date2"class="form-control dateselect" placeholder="date"value ="<?php
				if(isset($_GET['firstday'])){
					echo $lastday;
				}else{
										date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('Y-m-d', time());
echo $date_today;
				}

	

	?>"/>


                                                   </td>
	<td>
	
	                                            <button style="margin-top:30px;" type="button" id="insert"class="btn btn-primary"><span class="search_text">Search</span></button>

	</td>
		 </tr>
		 
		 
		 </table>
   

             
                                     <!-- end col -->
                                                </div><!-- end row -->                          
    
					
                                    <div class="card-body">

                   <table class="table mb-0" id="empTable">
    
			  <thead>
            <tr>
              <th>Name</th>
                <th>To be Paid</th>
              
                <th>Paid</th>
                <th>Total Ammount</th>
				
            </tr>
        </thead>
        <tbody>
        
           							<?php
									
	
								
								include 'connect.php';
				$select1=mysqli_query($db,"SELECT date_insert,location,username  FROM  daily_charges WHERE location='$route'  and   date_insert between  '$firstday'  and  '$lastday' group by date_insert ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	   	WHILE($get12=mysqli_fetch_array($select1)){
			$date_insert_day =$get12['date_insert']; 
	
?>
		<?php

?>
<tr>
                <td><?php
				 $newDate = date("d-M-Y", strtotime($date_insert_day));
				 echo "<strong>$newDate</strong>";
				?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
<?php
	$select_username=mysqli_query($db,"SELECT username  FROM  daily_charges WHERE location='$route'  and   date_insert = '$date_insert_day'   ");
$select_num_username=mysqli_num_rows($select_username);
if($select_num_username !=0){
	   	WHILE($get_username=mysqli_fetch_array($select_username)){
$username_charge =$get_username['username'];
?>
<tr>
                <td><?php
				 
				 $newDate = date("d-M-Y", strtotime($date_insert_day));  
				 echo $username_charge;
				?></td>
                <td>
			<?php
				 			
$sum_paid = 0;
		$sum_to_be = 0;
		$sum_total_paid =0;			
$select23=mysqli_query($db,"SELECT paid,to_be   FROM parcel_info  where account='$route' and  username='$username_charge' and  date_insert = '$date_insert_day' and open !='yes'");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
		$to_be=$get123['to_be'];
		$paid=$get123['paid'];
		 $sum_paid += $paid;
$sum_to_be += $to_be;

$total_sum = $sum_to_be + $sum_paid;


}}else{
	$total_sum=0;
}


echo number_format($sum_to_be,2);

								?>	
				</td>
          <td>
		  <?php
		  echo number_format($sum_paid,2);
		  ?>
		  </td>  <td>
		  <?php
		  echo number_format($total_sum,2);
		  ?>
		  </td>
            </tr>
<?php



}}

?>



      <?php
	  }}
?>     
        </tbody>
  <tfoot>
  <tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
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
	

        <!-- plugin js -->
       
        <!-- Calendar init -->
        

	   <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
	   <script src="search_total_summary.js"></script>
	
	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>
	<link rel="stylesheet" type="text/css" href="datatable/datatable.css">

	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>

        <!-- apexcharts init -->

    </body>
</html>

<script>
$(document).ready(function(){
	
	
	
		 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 
});
</script>
 <script>
        $(document).ready(function(){
            $.fn.dataTable.ext.errMode = 'none';
            $('#empTable').DataTable();
        });
        </script>