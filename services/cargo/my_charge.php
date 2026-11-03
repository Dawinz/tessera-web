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
        <?php $tessera_page_label = 'My charges'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
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
						            <div class="parent">
  
</div>
						 <?php
	 $username=$_SESSION['username'];

	 ?>
             


 <?php
  

			   if (empty($_GET['firstday'])) {
				   $_GET['firstday'] = date('Y-m-01');
				   $_GET['lastday'] = date('Y-m-t');
				   $_GET['route'] = 'Kampala to Lubumbashi';
				   $_GET['trip'] = 'admin (admin admin)';
			   }
			   $firstday = @$_GET['firstday'];
			   $lastday = @$_GET['lastday']; 
			   $username_choosen = @$_GET['trip'];
			   $route = @$_GET['route'];
			   if($username_choosen==""){
				   			   	$username_choosen = "";	 

			   }else{
							   	$username_choosen = preg_replace('/_/',' ', $username_choosen);	 
   
			   }


if(isset($_GET['firstday'])){
	$username_choosen = preg_replace('/_/',' ', $username_choosen);	  
$select1=mysqli_query($db,"SELECT *  FROM users where fullname='$username_choosen'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $username_choosen=$get13['username']; 
		
}}}


       $route = preg_replace('/_/',' ', $route);
}else{
	$username_choosen ="";
	$route ="";
}				

	$loan_array=array();
$to_be_array=array();
$paid_array=array();								

	$select222=mysqli_query($db,"SELECT loan,paid,to_be  FROM parcel_info   where  account='$route' and  username='$username_choosen' and date_insert >= '$firstday' and  date_insert <= '$lastday' and open !='yes'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    
			$paid =$get122['paid']; 
			$to_be =$get122['to_be']; 
			$loan =$get122['loan']; 
array_push($to_be_array, $to_be);
array_push($paid_array, $paid);
array_push($loan_array, $loan);
		
	}
}

			   
?>			 
   <div class="row">
                            
                                <div class="card sr-table-panel" data-sr-loading-label="Loading charges...">
         <div class="row"style="margin:20px;">
		 <table>
		 <tr>
		 <td>  
 <label class="form-label" for="validationCustom02">Select Route</label>
		 <select name="route" id="route" class="form-control selectpicker"  data-live-search="true"><?php
							   
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
		 <td>				 <label class="form-label" for="validationCustom02">Choose User</label>

                                                   <select id="trip"class="form-control selectpicker action" data-live-search="true"><?php
				
									
$select1=mysqli_query($db,"SELECT *  FROM users where username='".$_SESSION['username']."' ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $u_name=$get13['fullname']; 
			echo '<option value="vehicle">'.$u_name.'</option>';
}}}
											?></select></td>
		 <td>
	<label class="form-label" for="validationCustom02">Starting Date</label>

													
<input type="text" id="date"class="form-control dateselect" placeholder="date"value ="<?php
if(isset($_GET['firstday'])){
	echo $_GET['firstday'];
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
		echo $_GET['lastday'];
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
											  <div class="col-lg-4"style="padding:3px;">
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search Code">
      </div>
                  				 <table class="table mb-0" id="empTable">
           <thead>
              <tr>
                    <th>sender's name</th>
                    <th>Receiver's name</th>
                    <th>Destination</th> 
					<th>Date</th>
					<th>Detail</th>
					<th>code</th>
					<th>Ammount</th>
					<th>Loans</th
					><th>Total Ammount</th>
					<th>Paid</th>
					<th>To be Paid</th>
					<th>Quantity</th>
                    
                </tr>
                </thead>
				                	  <tfoot>
              <tr>
                <th></th>
				<?php
if(isset($_GET['firstday'])){
	$username_choosen = preg_replace('/_/',' ', $username_choosen);	  
$select1=mysqli_query($db,"SELECT *  FROM users where fullname='$username_choosen'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $username_choosen=$get13['username']; 
		
}}}


       $route = preg_replace('/_/',' ', $route);
}else{
	$username_choosen ="";
	$route ="";
}

?>


       
				
              
				<th>To Be Payed Total</th>
				<th>			<?php
			$to_be_total = array_sum($to_be_array);
echo number_format($to_be_total,2);

								?></th>
                          </tr> 
						  
						  
						  <tr>
                <th></th>
                
				
              
				<th>Cash Payed</th>
				<th>		
		
								<?php
		$paid_total = array_sum($paid_array);
				echo number_format($paid_total,2);
		
								?></th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>Charged Receipt</th>
				<th>		
									<?php
													if(isset($_GET['firstday'])){
																		include_once 'connect.php';
				$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where account='$route' and  username='$username_choosen' and   date_insert >= '$firstday' and  date_insert <= '$lastday' and open !='yes'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}else{
	echo 0;
}
	
													}else{
														echo "0";
													}
			?>
				</th>
								</th>
                          </tr>
						  
						  <tr><th></th>
				<th>Loan</th>
				<th>			<?php
	$loan_total = array_sum($loan_array);
				echo number_format($loan_total,2);
								?></th>
                          </tr>  <tr>
                <th></th>
                
				
              
				<th>Total</th>
				<th>			<?php
				$total_amount = $paid_total + $to_be_total;
	echo number_format($total_amount,2);

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
	   <script src="search_mycharge.js"></script>
	
	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>
  
        <!-- apexcharts init -->

    </body>
</html>
  <script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
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
			var firstday = "<?php echo $firstday;?>";
			var lastday = "<?php echo $lastday;?>";
			var username_choosen = "<?php echo $username_choosen?>";
			var route = "<?php echo $route;?>";
			
			
	
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 5, "desc" ]],
                'ajax': {
                'url':'fetch_user_sum.php',
									 type:"POST","data": {
        "firstday":firstday,"lastday":lastday,"username_choosen":username_choosen,"route":route,
    }
                },'columns': [
                    { data: 's_name' },
					  { data: 'r_name' },
                    { data: 'account' }, 
					{ data: 'date_insert' },
					  { data: 'detail' },
                    { data: 'code' },
					{ data: 'amount' },
						{ data: 'loan' },
					{ data: 'loan_amount' },
					{ data: 'paid' },
					{ data: 'to_be' },
					{ data: 'question' },
       
                 
                ],
				"lengthMenu": [[50, 100, 150], [50, 100, 150]],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print', 
			className: 'red',
			 autoPrint: true,
                title: ' <img style="margin:10px;" src ="http://www.redetrade.com/sr.png" width="80" height="80" /><p style="font-size:25px;margin:10px;"> User charges</p><div style="font-size:14px;margin:20px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Date : <?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
echo $date_insert;
	?></div><p style="font-size:25px;margin:10px;">  <?php if(isset($_GET['date3'])){echo $_GET['date3'];}else{  }?></div>',
			
			footer: true },
            { extend: 'pdfHtml5', footer: true ,className: 'red' }
        ], rowCallback: function(row, data, index) {
      if (data.status == "Due payment") {
    	
$(row).css('background-color','#6b0121');
$(row).css('color','#ffffff');
      } 
	  if (data.status == "To be Payed"){
	$(row).css('background-color','#ffffff');	  
	  } if (data.status == "paid"){
	$(row).css('background-color','#99ff9c');	  
	  }
    },
					   "fnDrawCallback": function (oSettings) {
        $(".dataTables_filter").each(function () {
            $(this).appendTo($(this).parent().siblings(".panel-body"));
        });
    }
            });
	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 
 oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
});  
$(".search_text").text("Search");

        });

        </script>