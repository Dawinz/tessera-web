<!doctype html>

<html lang="en">

<?php

$start_month="";
$end_month="";
?>
    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Store charge summary'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
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
              
   <div class="row">
                            
                                <div class="card">
         <div class="row"style="margin:20px;">
		 <table>
		 <tr>
		 <td>  
 <label class="form-label" for="validationCustom02">Select Route</label>
		 <select name="route" id="route" class="form-control selectpicker"  data-live-search="true"><?php
				if(isset($_GET['route'])){
			  $route = @$_GET['route'];
			$route = preg_replace('/_/',' ', $route);
echo '<option value="vehicle">'.$route.'</option>';

		}else{
			
		}						   
 $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'  order by id desc");
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
		 <td>				 <label class="form-label" for="validationCustom02">Choose User</label>

                                                   <select id="trip"class="form-control selectpicker action" data-live-search="true">
								   
																			<?php
include 'connect.php';						


																if(isset($_GET['trip'])){
																	echo "  <option>".$_GET['trip']."</option>";
																	$select1=mysqli_query($db,"SELECT *  FROM users ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $u_name=$get13['fullname']; 
			echo '<option value="vehicle">'.$u_name.'</option>';
}}}
																}else{
										echo '<option value="vehicle">CHOOSE USER</option>';
							

$select1=mysqli_query($db,"SELECT *  FROM users ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $u_name=$get13['fullname']; 
			echo '<option value="vehicle">'.$u_name.'</option>';
}}}
																}				

											?>
										
										
										</select></td>
		 <td>
	<label class="form-label" for="validationCustom02">Starting Date</label>

													
                                                                                          <input type="text" id="date"class="form-control dateselect" placeholder="date"value ="<?php
				
if(isset($_GET['lastday'])){
 $firstday = @$_GET['firstday'];
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
if(isset($_GET['lastday'])){
 $lastday = @$_GET['lastday'];
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
		     <?php
			  
if(isset($_GET['firstday'])){
	 $firstday = @$_GET['firstday'];
			   $lastday = @$_GET['lastday']; 
			   $username_choosen = @$_GET['trip'];
			   $route = @$_GET['route'];
		       $route = preg_replace('/_/',' ', $route);
}else{
	 $firstday = "";
			   $lastday =""; 
			   $username_choosen = "";
			   $route ="";
		       $route = "";
}

			   ?>
		 
		 </table>
   

             
                                     <!-- end col -->
                                                </div><!-- end row -->                          
    
					
                                    <div class="card-body">
                <table class="table mb-0" id="empTable">
           <thead class="table-light">
              <tr>
                    
                   
					<th>Username</th>
					<th>Date</th>
				 <th>Destination</th> 
					<th>Paid</th>
					<th>To be Pay</th>
					<th>Ammount</th>
					<th>Receipts</th>
                    
                </tr>

				
				
                </thead>
				<tbody>
								<?php
	$to_be_array=array();
$paid_array=array();	

$select1=mysqli_query($db,"SELECT *  FROM users where fullname='$username_choosen'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $username_choosen=$get13['username']; 
		
}}}


								include 'connect.php';
					if(isset($_GET['firstday'])){			
				$select1=mysqli_query($db,"SELECT date_insert,location  FROM  daily_charges WHERE location='$route' and  username='$username_choosen' and date_insert between  '$firstday'  and  '$lastday'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	   	WHILE($get12=mysqli_fetch_array($select1)){
			$date_insert_day =$get12['date_insert']; 
			$route =$get12['location']; 
?>
<tr>
<td> <?php echo $username_choosen;?></td>
<td><?php echo $date_insert_day;?></td>
<td><?php echo $route;?></td>

<td>

	<?php
	if(isset($_GET['firstday'])){
$sum_paid = 0;
		$sum_to_be = 0;
		$sum_total_paid =0;
$select23=mysqli_query($db,"SELECT paid,to_be   FROM parcel_info  where account='$route' and  username='$username_choosen' and  date_insert = '$date_insert_day' and open !='yes' ");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
		$to_be=$get123['to_be'];
		$paid=$get123['paid'];
		 $sum_paid += $paid;
$sum_to_be += $to_be;

}}
array_push($paid_array, $sum_paid);
array_push($to_be_array, $sum_to_be);

echo number_format($sum_paid,2);

	}else{
		echo "0.00";
	}

								?>

</td>
<td>
		<?php
				 				
if(isset($_GET['firstday'])){
	echo number_format($sum_to_be,2);
}else{
	echo "0.00";
}
								?>

</td>
<td>
<?php
if(isset($_GET['firstday'])){

		$total_amnt_paid = $sum_to_be + $sum_paid;
		echo number_format($total_amnt_paid,2);
}else{
	echo "0.00";
}
					?>

</td>
<td>
<?php
if(isset($_GET['firstday'])){
		$select15=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE account='$route' and   username='$username_choosen' and  date_insert = '$date_insert_day' and open !='yes'");
$select_num15=mysqli_num_rows($select15);
if($select_num15 !=0){
	echo $select_num15;
}}else{
	echo "0.00";
}
?>
</td>

</tr>



<?php
	}
}}else{
	?>
	<tr>
	<td></td>
	<td></td><td></td><td>No data found</td><td></td><td></td><td></td>
	
	</tr>
	
	<?php
	
}
				
				?>
				
				
				
				</tbody>
				
				
				                	  <tfoot>
              <tr>
                <th></th>
      
				
              
				<th>To Be Payed Total</th>
				<th>			<?php
				if(isset($_GET['firstday'])){
				$to_be_total = array_sum($to_be_array);
echo number_format($to_be_total,2);

				}else{
					echo "0.00";
				}		?></th>
                          </tr> 
						  
						  
						  <tr>
                <th></th>
                
				
              
				<th>Cash Payed</th>
				<th>		<?php
				if(isset($_GET['firstday'])){
				$paid_total = array_sum($paid_array);
				echo number_format($paid_total,2);
}else{
					echo "0.00";
				}
								?></th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>Charged Receipt</th>
				<th>		
									<?php
									if(isset($_GET['firstday'])){
														include_once 'connect.php';
				$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where account='$route' and  username='$username_choosen' and date_insert >= '$firstday' and date_insert <= '$lastday' and open !='yes' ");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
									}else{
										echo "0.00";
									}

				?>
				</th>
								</th>
                          </tr>
						  
						  <tr>
                <th></th>
                
				
              
				<th>Total</th>
				<th>			<?php
													if(isset($_GET['firstday'])){
	$total_amount = $paid_total + $to_be_total;
	echo number_format($total_amount,2);

													}else{
														echo "0.00";
													}
?></th>
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
        

	   
	   <script src="search_usercharge_summary.js"></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>

	<link rel="stylesheet" type="text/css" href="datatable/datatable.css">
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
  <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
        <!-- apexcharts init -->

    </body>
</html>

<script>
	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 
</script>