

<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Register Route Points</title>
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
		  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'><link rel="stylesheet" href="./style.css">
 <link rel="stylesheet" type="text/css" href="css/datatable.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">
	

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
                                               <div class="row">
											     <h5>Sender Informations</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Fullname</label>
                                        <input type="text"  style="text-transform:uppercase"class="form-control" id="firstname"placeholder="Sender's fullname">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone Number</label>
                                        <input type="text" class="form-control" id="s_no"placeholder="phone number start with 255"value ="+255">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>       <div class="row">
									 <h5>Receiver Information</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Fullname</label>
                                        <input  style="text-transform:uppercase" type="text" class="form-control" id="r_firstname"placeholder="Reciver's Fullname">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone Number</label>
                                        <input type="text" class="form-control" id="r_no"placeholder="phone number start with 255" value ="+243">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>       <div class="row">
												 <h5>Trip Info</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Route</label>
                                       <select class="form-select"id="trip">
                                                          <option>Dar es Salaam to Lubumbashi</option>
											<option>Dar es Salaam to Kolowezi</option>
											
                                                        </select>                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Payment Option</label>
                                                   <select class="form-select"id="status_em">
                                                    <option>Payment Status</option>
											<option>To be Payed</option>
											<option>paid</option>
											<option style="color:red;" data-bs-toggle="modal"
                                            data-bs-target="#myModal">Due payment</option>
											
                                                        </select>                                                         <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>       <div class="row">
									<h5>Payment and Security</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Price</label>
                                        <input type="text" class="form-control" id="amount"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Quantity</label>
                                        <input type="text" class="form-control" id="question"placeholder="quantity">
                                                        <div class="valid-feedback">
                                        <input type="text" class="form-control" id="answer"placeholder="due">
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>  
  <div class="row">											     
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="basicpill-address-input"
                                                                class="form-label">Detail</label>
                                                            <textarea id="summernote" class="form-control"
                                                                rows="2">NGUO</textarea>
                                                        </div>
                                                    </div><!-- end col -->
                                                </div>		
                                    <button type="button" id="insert"class="btn btn-success"><i class="uil uil-check me-2"></i> <span id="saving">Save</span></button>
												</div>
                                        </div>
                                    </div> 
                                    <div class="col-xl-3">
                                        <div class="card card-h-100">
                                            <div class="card-body">
                                                  <div class="row mb-3">
												  
									
                                                <div class="col-6">
                                                    <p class="text-muted mb-2">Todays Paid</p>
                                                    <h5>12,582,000</h5>
                                                </div>

                                                <div class="col-6">
                                                    <p class="text-muted mb-2">Today's to be paid</p>
                                                    <h5>98,741,000</h5>
                                                </div>  <div class="col-6">
                                                    <p class="text-muted mb-2">Today's Total</p>
                                                    <h5 style="color:#F7CC53;">98,741,000</h5>
                                                </div>
                                            </div>
                                                
                                                <div id="external-events">
                                                    <br>
                                                    <p class="text-muted">This are the list of all your Luggages that you have charged</p>
     <div class="card-body">
                                        <div class="table-responsive">
                                            <table id='empTable' class="table table-sm m-0">
                                                <thead>
                                                    <tr>
                                                        <th>code</th>
                                                   
                                                    
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
      <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Modal Heading</h5>
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
                   <div class="modal fade exampleModalFullscreen" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
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
		   	<script  src="insert_script.js"></script> 
		<script src="js/sweetalert.js"></script>
<script src="option.js"></script>
<script src="due_pament.js"></script>


 <script src="currency.js"></script>
        <!-- init js -->
        <script src="assets/js/pages/form-advanced.init.js"></script>
	
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
		<?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now = date(' m/d/Y', time());
$username=$_SESSION['username'];


	?>
	     <script>
        $(document).ready(function(){
			var username = "<?php echo $username;?>";
			var date_now = "<?php echo $date_now;?>";
			
			
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 0, "desc" ]],
                'ajax': {
                'url':'fetch_user_luggages.php',
									 type:"POST","data": {
        "username":username,"date_now":date_now,
    }
                },'columns': [
                    { data: 's_name' },
					 
             
       
                 
                ],
				"lengthMenu": [[100, 125, 150, -1], [100, 125, 150, "All"]],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
            });

        });
		 $('#insert').click(function(){
	 
var date = $('#date').val();
var date2 = $('#date2').val();
var date3 = $( "#trip option:selected" ).text();


	 if (date==""){
		 alert("Date is Empty");
	 } else if(date2 == ""){
		 alert("date is Empty");
		 control.disabled="disabled";
	 }
url = "my_charge.php?date="+date+"&date2="+date2+"&date3="+date3+"";
 window.location = url;
			
			
 });
        </script>

      </script>
    </body>
</html>
