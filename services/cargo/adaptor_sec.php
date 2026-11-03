
<!doctype html>

<html lang="en">

<?php

$start_month="";
$end_month="";
?>
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

													
                                                                                          <input type="text" id="date"class="form-control dateselect" placeholder="date"value ="<?php
				
if(isset($_GET['firstday'])){
 $firstday = @$_GET['firstday'];
 echo $firstday;
 }else{
							date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('Y-m-d', time());
echo $date_today;
}


	?>">
                                                    
		 </td>
	
	<td>
	

	</td>
		 </tr>
		 
		 
		 </table>
   </div>
<?php
include 'connect.php';
if(isset($_GET['firstday'])){
 $seleted = @$_GET['firstday'];
 }else{
							date_default_timezone_set('Africa/Dar_es_Salaam');
$seleted = date('Y-m-d', time());

}


	$loan_array=array();
$to_be_array=array();
$paid_array=array();								

	$select222=mysqli_query($db,"SELECT loan,paid,to_be  FROM parcel_info   where  username='salym'and date_insert ='$seleted'");
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
$paid_array_checked=array();		

	$select2223=mysqli_query($db,"SELECT loan,paid,to_be  FROM parcel_info   where  username='salym'and date_insert ='$seleted' and open='yes'");
$select_num2223=mysqli_num_rows($select2223);
if($select_num2223 !=0){
   	WHILE($get1223=mysqli_fetch_array($select2223)){
    	    
			$paid2 =$get1223['paid']; 
array_push($paid_array_checked, $paid2);



		
	}
}



?>
             
                                     <!-- end col -->
                                                </div><!-- end row -->                          

					
                                    <div class="card-body">
									  <div class="col-lg-4"style="padding:3px;">
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search Code">
      </div>
                	 <table class="table mb-0" id="empTable">
           <thead class="table-light">
              <tr>
                    <th>username</th>
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
					<th>option</th>
                    
                </tr>
                </thead>
				          	  <tfoot>
              <tr>
                <th></th>
           
				
              
				<th>To Be Payed Total</th>
				<th>			<?php
				
					$to_be_total = array_sum($to_be_array);
echo number_format($to_be_total,2);

								?></th>
                          </tr> 
						  
						  
						  <tr>
                <th></th>
                
				
              
				<th>Cash Payed</th>
				<th>		<?php
	$paid_total = array_sum($paid_array);
				echo number_format($paid_total,2);
								?></th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>Total Charged Receipt</th>
				<th>		
				<?php
					$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where  username='osama'and date_insert ='$seleted' ");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}else{
	echo 0;
									}
				
				?>
				</th>
								</th>
                          </tr>  <tr>
                <th></th>
                
				
              
				<th>Total Charged Receipt adapotor</th>
				<th>		
				<?php
					$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where  username='osama'and date_insert ='$seleted' and open='yes'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}else{
	echo 0;
									}
				
				?>
				</th>
								</th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>Total</th>
				<th>			<?php
				
	$total_amount = $paid_total + $to_be_total;
	echo number_format($total_amount,2);
?></th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>Total new</th>
				<th>			<?php
	$paid_total2 = array_sum($paid_array_checked);
				echo number_format($paid_total2,2);
?></th>
                          </tr>	  <tr>
                <th></th>
                
				
              
				<th>Total new total</th>
				<th>			<?php
	$paid_total2 = array_sum($paid_array_checked);
		$new_total = $paid_total - $paid_total2;
		echo number_format($new_total,2);
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
			var firstday = $("#date").val();
			
			
	
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 5, "desc" ]],
                'ajax': {
                'url':'fetch_adoptor_sec.php',
									 type:"POST","data": {
        "firstday":firstday
    }
                },'columns': [
                    { data: 'username' },
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
					{ data: 'delete' },
       
                 
                ],
				"lengthMenu": [[100, 125, 150, -1], [100, 125, 150, "All"]],
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
      if (data.open == "yes") {
    	
$(row).css('background-color','#6b0121');
$(row).css('color','#ffffff');
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