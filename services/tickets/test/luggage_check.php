<style>
.dataTables_filter {
    display: none;
}

</style>

<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Register Luggage</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <!-- fullcalendar css -->
     

        <!-- One of the following themes -->
  

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />


	

    </head>

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
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

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
                                    <h4 class="mb-0">Luggage Registration</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">Luggage registration</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
						
                   <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
								  <div class="col-xl-9">
                                        <div class="card card-h-100">
                                            <div class="card-body">
								<div id="load_register"></div>
												</div>
                                        </div>
                                    </div> 
                                    <div class="col-xl-3">
                                        <div class="card card-h-100">
                                            <div class="card-body">
											 <button type="button" id="show_sum"class="btn btn-primary"> your summary charges</button>
                                         <div class="mt-3">
                                            
                                                <div class="col">
                                                    <div class="p-3 border ">
													 <p class="text-muted mb-2">Today's  Paid Charge</p>
                                                    <h5><span id="load_user_paid">
													 <div style="display:none;"class="spinner-border text-primary m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
													</span></h5>
													</div>
                                                </div>
                                                <div class="col">
                                                    <div class="p-3 border ">
													 <p class="text-muted mb-2">Today's to be paid</p>
                                                    <h5><span id="load_user_to_be">
																 <div style="display:none;"class="spinner-border text-primary m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
													</span></h5>
													</div>
                                                </div>
                                                <div class="col">
                                                    <div class="p-3 border bg-light">
													 <p class="text-muted mb-2">Today's Total charge</p>
                                                    <h5><span id ="load_all_sum">
																 <div style="display:none;"class="spinner-border text-primary m-1" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
													</span></h5>
													</div>
                                                </div>
                                           
                                        </div>
                                                
                                                <div id="external-events">
                                                    
     <div ><br>
	          <input type="text" id="myInputTextField" class="form-control" placeholder="Search code number..."><br>

                                        <div class="table-responsive">
                                            <table id='empTable' class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>code</th><th></th>
                                                   
                                                    
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  
                                                  
                                                </tbody><!-- end tbody -->
                                            </table><!-- end table -->
                                        </div><!-- end table responsive-->
                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> <!-- end col-->

                            <!-- end col -->
                                </div> 

                             
                             
                            
                                <!-- end modal-->
                            </div>
                        </div>
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
      <div id="due_modal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Due Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <form>
  <div class="form-group">
   <label for="exampleInputEmail1">Due payment form</label><br>

        <input type="input" class="form-control" id="due_amount" aria-describedby="emailHelp" placeholder="Luggage Ammount"pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"> 

	<br>
    <input type="input" class="form-control" id="due_amount2" aria-describedby="emailHelp" placeholder="Paid Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"> <br>

  <button type="button" class="btn btn-primary btn-lg btn-block"id="savebtn">save</button> 

  </div>
  
  
  </form>
                                                    </div>
                                               

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										

              <div id="load_ticket_print"></div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> Sr Classic coach</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->


        <!-- /Right-bar -->

   <!-- end row -->

     <!-- end row -->

              <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


             
            </div>
	<script src="js/sweetalert.js"></script>

<script src="show_sum.js"></script>


 <script src="currency.js"></script>
        <!-- JAVASCRIPT -->
		<script src="vendors/js/jquery351.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
   

        <!-- plugin js -->
  

        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script src="assets/js/alertify.min.js"></script>
		<?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now = date('Y-m-d', time());
$username=$_SESSION['username'];



	?>
	     <script>
   $(document).ready(function(){
				$("#load_register").load("load_register.php");
			var username = "<?php echo $username;?>";
			var date_now = "<?php echo $date_now;?>";
			
			
            $('#empTable').DataTable({
             "bInfo": false,
                'processing': true,
                'serverSide': true,
				
                'serverMethod': 'post',
				 "order": [[ 1, "desc" ]],
                'ajax': {
                'url':'fetch_user_luggages.php',
									 type:"POST","data": {
        "username":username,"date_now":date_now,
    }
                },'columns': [
                    { data: 'code' }, { data: 'id' },
					 
             
       
                 
                ],
				"lengthMenu": [[2]],
				dom: 'Bfrtip',
         "columnDefs" : [          
       { "targets": [1], "visible": false }
   ],
		   "fnDrawCallback": function (oSettings) {
        $(".dataTables_filter").each(function () {
            $(this).appendTo($(this).parent().siblings(".panel-body"));
        });
    }
            });
oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
});
        });


        </script>

      </script>
    </body>
</html>
