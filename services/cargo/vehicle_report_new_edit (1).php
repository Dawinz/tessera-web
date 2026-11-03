<?php
error_reporting(E_ERROR | E_PARSE);
$date_vehicle= @$_GET['date'];
$name_vehicle= @$_GET['date3'];
include 'connect.php';

$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name where date='$date_vehicle' and Name='$name_vehicle'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $vehicle_name =$get12['Name']; 
			$driver =$get12['Driver']; 
			$Conductor =$get12['Conductor']; 


		
	}
}
$username=$_SESSION['username'];
$select222=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $fullname =$get122['fullname']; 

		
	}
}
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());

if(isset($_GET['start_date'])){
	$start_date = $_GET['start_date'];
	$end_point = $_GET['end_point'];


	
	$man_date = "and parcel_date >= '$start_date' and  parcel_date <= '$end_point'";
}else{
	$man_date="";
}

?>
    <?php
//index.php
$connect2 = mysqli_connect("localhost", "srclassic", "Teccross@360", "cargo");
$date_vehicle2 = '';
$query2 = "SELECT parcel_date FROM manifest_dates where trip_date='$date_vehicle' and bus='$name_vehicle' ";
$result2 = mysqli_query($connect2, $query2);
while($row2 = mysqli_fetch_array($result2))
{
 $date_vehicle2 .= '<option value="'.date("m-d-Y", strtotime($row2["parcel_date"])).'">'.date("m-d-Y", strtotime($row2["parcel_date"])).'</option>';
}
?>
<!doctype html>
<style>

</style>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Luggages</title>
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
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
   <link rel="stylesheet" href="css/datapicker/datepicker3.css">
   <link rel="stylesheet" type="text/css" href="css/buttons.css">

    </head>
<style>

button.dt-button, div.dt-button, a.dt-button, input.dt-button {
    border-radius: 2px;
    color: black;
    background-color: #F5F6F8;
}
.stock{
	text-decoration:line-through;
	text-decoration-thickness :2px;
	text-decoration-color:red;

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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Manfest luggage list</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Luggages</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						            <div class="parent">
  
</div>
										<div class="row">
					
                             						
					
							
			
							
						
														<?php


?>
    
                     <div id="date1_exp"style="display:none;"><?php
$date1= @$_GET['date'];
echo $date1;
?></div>
					 <div id ="date2_exp"style="display:none;"><?php
$date2= @$_GET['date2'];
echo $date2;
?></div>					 <div id ="date3exp"style="display:none;"><?php
$date3= @$_GET['date3'];
echo $date3;
?></div>
			
					</div>		
              
   <div class="row">
                            
                                <div class="card">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title">Vehicle lists</h4>
                                 

								   </div><!-- end card header -->
                                    <div class="card-body">
											<div class="row">
										<div class="col">
										<div style="font-size:14px;">
	Vehicle Name : <span style="font-weight:bold;"><?php echo $vehicle_name;?></span><br>
	Trip Date : <span style="font-weight:bold;"><?php echo date('jS M Y', strtotime($date2)) ;?></span>
	<p>Driver's Name: <span style="font-weight:bold;"><?php echo $driver;?></span><br>
	Conductor's Name : <span style="font-weight:bold;"><?php echo $Conductor;?></span><br>

	</p>
	</div>
	
	</div>
<div class="col">
	  <div class="">
                                                    
                                                    <div class="col-md-10">
											<label class="form-label" for="validationCustom02">Starting Date</label>

													
                                                                                          <input type="text" id="date"class="form-control dateselect" placeholder="date"value ="<?php
				
if(isset($_GET['start_date'])){
 $start_date = @$_GET['start_date'];
 echo $start_date;
 }else{
							date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('Y-m-d', time());
echo $date_today;
}


	?>">
</div>
	</div>
	</div>
	<div class="col">
	  <div >
                                                    
                                                    <div >
<label class="form-label" for="validationCustom02">Ending Date</label>

                                                                                  <input type="text" id="date2"class="form-control dateselect" placeholder="date"value ="<?php
				

if(isset($_GET['end_point'])){
 $end_point = @$_GET['end_point'];
 echo $end_point;
 }else{
							date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('Y-m-d', time());
echo $date_today;
}


	?>"/>
</div>
	</div>
	</div>
	<div class="col">
<button style="margin-top:30px;" type="button" id="insert"class="btn btn-primary"><span class="search_text">Search</span></button>

	</div>
	</div>
                                           <table class="table mb-0" id="empTable">
           <thead class="table-light">
           <tr>
                   
					
					<th style="">No</th>
					<th style="">Route</th>
					<th>Code</th>
					<th>Name of Owner</th>
										<th>Weight</th>          

					<th>Description of Goods</th>          
					<th>status</th>          
					<th>To Be Paid</th>
					<th>Paid</th>
					<th>loan</th>
					<th>Total Ammount</th>					
					<th>Exported Quantity</th>
					<th>Charged Date</th>
					<th>Check out info</th>
					<th>Check out</th>
				
					  
                </tr>
                </thead>
				<tbody>
				<?php
								$select=mysqli_query($db,"SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id desc 
	) row_num,username_taken,weight_name,weight_value,timetaken,id,parcel_date,parcel_route,status,code,username,status,checked,detail,loan,paid,to_be,code,quantity,q_left,trip_date,amount
FROM 
	manifest    WHERE trip_date ='$date1' and bus = '$date3' $man_date
		");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
WHILE($get=mysqli_fetch_array($select)){
$id =$get['id'];
$no =$get['row_num'];
$username =$get['username'];
$amount =$get['amount'];
$detail =$get['detail'];
$to_be =$get['to_be'];
$paid =$get['paid'];
$loan =$get['loan'];
$code =$get['code'];
$q_left =$get['q_left'];
$quantity =$get['quantity'];
$parcel_route =$get['parcel_route'];
$status =$get['status'];
$checked =$get['checked'];
$parcel_date =$get['parcel_date'];
$username_taken =$get['username_taken'];
$timetaken =$get['timetaken'];
  $weight_name=$get['weight_name']; 
 $weight_value=$get['weight_value']; 
?>
<tr id="stroke<?php echo $id;?>"
<?php
if($status=="paid"){
	echo"style='background-color:#99ff9c'";
}else if($status=="Due payment"){
		echo"style='background-color:#ffe8f0'";
}

if($checked=="out"){
	echo"class='stock'";
}
?>>
				<td>
				<?php echo "$no";?>
				</td>
				<td>
				<?php echo $parcel_route ;?>
				</td>
				<td>
				<?php echo $code;?>
				</td><td>
				<?php echo $username;?>
				</td>
				<td>
				<?php echo "$weight_value$weight_name";?>
				</td>
				<td>
				<?php echo  base64_decode($detail);?>
				</td><td>
				<?php echo  ($status);?>
				</td>
				<td>
				<?php if($to_be=="0"){echo "-";}else{echo number_format($to_be,2);}?>
				</td><td>
				<?php if($paid==""){echo "-";}else{echo number_format($paid,2);}?>
				</td><td>
				<?php if($loan=="0"){echo "-";}else{echo "<span style='color:red;'>".number_format($loan,2)."</span>";}?>
				</td>
				<td>
				<?php echo number_format($amount,2);?>
				</td>
				<td>
				<?php echo "$q_left/$quantity";?>
				</td>
				<td>
				<?php echo 
				date("d-m-Y", strtotime($parcel_date));?>
				</td>
				<td>
				<?php if($username_taken !=""){echo "$username_taken at $timetaken";}?>
				</td>
				<td>
				<button type="button" class="btn btn-soft-danger" id="check<?php echo $id;?>">
          <i class="uil  uil-clipboard-notes "></i>
        </button>
				<div style="display:none;"id="loading<?php echo $id;?>">loading....</div>

		<script>
		$(document).ready(function(){
			

			
			
		$("#check<?php echo $id;?>").click(function(){
				var id="<?php echo $id;?>";
				$("#loading<?php echo $id;?>").show();
				
  $.ajax({
				url:"check_recieved.php",
				method:"POST",
				data:{id:id},
				success:function(response){
					
					if($.trim(response)!=""){
						$("#loading<?php echo $id;?>").hide();
$("#stroke<?php echo $id;?>").addClass("stock");
					}else{
						$("#loading<?php echo $id;?>").hide();
$("#stroke<?php echo $id;?>").removeClass("stock");
					}
	

	}
			
			});			
		});
		});
		</script>
		
				</td>
				</tr>
<?php
}}
				
				?>
					  <?php
		$to_be_array=array();
$paid_array=array();	
	$loan_array=array();	
		
$select=mysqli_query($db,"SELECT parcel_route  FROM  manifest    WHERE trip_date ='$date1' and bus = '$date3' group by parcel_route ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
$parcel_route =$get['parcel_route'];
$sum_to_be=0;
$sum_paid=0;
$sum_loan=0;

$select_route=mysqli_query($db,"SELECT  paid,to_be,loan FROM  manifest    WHERE trip_date ='$date1' and bus = '$date3' and parcel_route='$parcel_route' $man_date ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
$paid =$get_route['paid'];
$to_be =$get_route['to_be'];
$loan =$get_route['loan'];
$sum_to_be += $to_be;	
$sum_paid += $paid;
$sum_to_be_total=0;
$sum_paid_total=0;
$sum_loan += $loan;

}}

  ?>
  <tr style="font-weight:bold;">
 <td></td>
  <td>
  <?php
  echo $parcel_route ;
  ?>
  </td>
  
  <td>To be paid</td>
  <td><?php echo  number_format($sum_to_be,2);?></td>
  <td>Paid</td>
  <td><?php echo  number_format($sum_paid,2);?></td>
   <td>Loan</td>
  <td style="color:red;"><?php echo number_format($sum_loan,2)?></td>  
  <td>Total Amount</td>
  <td><?php echo number_format($sum_to_be+$sum_paid+$sum_loan,2);?></td>
  <td></td>
  <td></td>  
  <td></td>  
  <td></td>  
  <td></td>  
 
  </tr>  

  <?php
		array_push($paid_array, $sum_paid);
array_push($to_be_array, $sum_to_be);
array_push($loan_array, $sum_loan);
	
		}
}

?>
  <tr style="font-weight:bold;">
 <td></td>
  <td>
  Net Total
  </td>
  
  <td></td>
  <td><?php 
  	$to_be_total = array_sum($to_be_array);
echo number_format($to_be_total,2);
  ?></td>
  <td></td>
  <td><?php 
  	$paid_total = array_sum($paid_array);
				echo number_format($paid_total,2);
  ?></td>
    <td></td>
  <td style="color:red;"><?php 
  	$loan_total = array_sum($loan_array);
				echo number_format($loan_total,2);
  ?></td>
  <td></td>
  <td><?php echo number_format($to_be_total+$paid_total+$loan_total,2);?></td>
  <td></td>
  <td></td>    
  <td></td>    
  <td></td>    
  <td></td>    
 
  </tr>
<?php
							  
							  ?>
							  
				</tbody>
				
				
	
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
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/" target="_blank" class="text-reset">Sr classic</a>
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
			
$("#insert").click(function(){
start_date = $("#date").val();
end_date = $("#date2").val();
window.location="vehicle_report_new_edit.php?date=<?php echo $_GET['date'];?>&date2=<?php echo $_GET['date'];?>&date3=<?php echo $_GET['date3'];?>&open=open&start_date="+start_date+"&end_point="+end_date;

});
		 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "top left",
		 format: "yyyy-mm-dd",
		 
	
		//container: console.log($(this).datepicker())
}); 
	
			
			
     		var date1_exp = "<?php echo $_GET['date'];?>";
			var date2_exp = $('#date2_exp').text();
			var date3exp = "<?php echo $_GET['date3'];?>";
            $('#empTable').DataTable({
			
				"order": [],
				"lengthMenu": [[300, 325, 450, -1], [300, 325, 450, "All"]],
                   dom: 'Bfrtip',
				   buttons: [
                { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print', 
			className: 'red',
			 autoPrint: true,
                title: ' <img style="margin:10px;" src ="http://www.redetrade.com/sr.png" width="80" height="80" /><p style="font-size:25px;margin:10px;"> Manifest</p><div style="font-size:14px;margin:20px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Date : <?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
echo $date_insert;
	?></div><p style="font-size:25px;margin:10px;">  <?php if(isset($_GET['date2'])){echo $_GET['date'];}else{  }?><br><?php if(isset($_GET['date3'])){echo $_GET['date3'];}else{  }?></div>',
			
			footer: true },
            { extend: 'pdfHtml5', footer: true ,className: 'red' }
        ],
           
  });

        });

        </script>