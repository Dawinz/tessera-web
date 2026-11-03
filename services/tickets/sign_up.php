<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Register staff'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
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
		  <link rel='stylesheet' href='vendors/select2/select2.min.css'><link rel="stylesheet" href="./style.css">
 <link rel="stylesheet" type="text/css" href="css/datatable.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">
	

    </head>

    <style>
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
			<style>
/* paginate styled by sr-platform-shell Book-list system */
		  .dt-button.red {
   color: #fff;

   background-color: #0379BB;
   border-radius: 2px;
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
                   

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Register User</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                            <li class="breadcrumb-item active">List of Users</li>
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
                                        <h4 class="card-title">User lists</h4>
                                    <button type="button" class="btn btn-soft-primary" data-bs-toggle="modal"data-bs-target="#exampleModalScrollable"><i class="uil uil-plus "></i>Regsiter</button>
                                    </div><!-- end card header -->
                                    <div class="card-body">
									<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search....">
		 </form>
      </div>
                                           <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 
<th></th>
<th>Username</th>
<th>Firstname</th>
<th>Surname</th>
<th>Role</th>
<th>Email</th>
<th>Number</th>
<th>User Status</th>
<th>Status</th>
<th>Change</th>
<th>Delete</th>
<th>Ref no</th>
</tr>
				
                          </thead>
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
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->


		                           <div id="exampleModalScrollable" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalScrollable" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalScrollable">Register Username</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                      <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               <form id="rgform">
                                                
														     <div class="col-12">
                                                      
																<div class="mb-3">
                                                                <label class="form-label">Firstname</label>
                                                                 <input class="form-control" type="text" id="fullname" placeholder="firstname.." >
                                                               
                                                            </div>	
															<div class="mb-3">
                                                                <label class="form-label">Lastname</label>
                                                                 <input class="form-control" type="text" id="lastname" placeholder="Lastname.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">username</label>
                                                                 <input class="form-control" type="text" id="username" placeholder="Username.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Password</label>
                                                                 <input class="form-control" type="password" id="password" placeholder="Password.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Phone Number</label>
                                                                 <input class="form-control" type="number" id="phone_no" placeholder="Phone number.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                 <input class="form-control" type="Email" id="email" placeholder="Email.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Opertaion Mole</label>
         <select class="form-select" id="role"aria-label="Floating label select example">
												<option value="">Role</option>
                                                 	<option>Agent</option>
                                                 <option>Administrator</option>
												  <option>Super Administrator</option>

                                            </select>                                                                     
                                                            </div>
										
														
													

                                                        </div>
														</form>
                                                    </div>
                                               
                                                </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="register_user">Register</button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
        <!-- /Right-bar -->

   <!-- end row -->
   <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasRightLabel">Update User</h5>
											  <div style="display:none;"id="id_no"></div>
                                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
          <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                    <div class="row">
                                               
                                                
														     <div class="col-12">
                                                      
																<div class="mb-3">
                                                                <label class="form-label">Firstname</label>
                                                                 <input class="form-control" type="text" id="fullname_update" placeholder="firstname.." >
                                                               
                                                            </div>	
															<div class="mb-3">
                                                                <label class="form-label">Lastname</label>
                                                                 <input class="form-control" type="text" id="lastname_update" placeholder="Lastname.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">username</label>
                                                                 <input class="form-control" type="text" id="username_update" placeholder="Username.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Password</label>
                                                                 <input class="form-control" type="password" id="password_update" placeholder="Password.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Phone Number</label>
                                                                 <input class="form-control" type="number" id="phone_no_update" placeholder="Phone number.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                 <input class="form-control" type="Email" id="email_update" placeholder="Email.." >
                                                               
                                                            </div>
															<div class="mb-3">
                                                                <label class="form-label">Opertaion Mole</label>
         <select class="form-select" id="role_update"
                                                aria-label="Floating label select example">
												<option id="role_update_text"></option>
												<option>Agent</option>
                                                 <option>Administrator</option>
												  <option>Super Administrator</option>
                                            </select>                                                                     
                                                            </div>
										
														
													

                                                        </div>
														
                                                    </div>
                                               
                                                </form>        
                                                         <button type="button" class="btn btn-primary" id="update">Update</button>

												</div>
												
                                        </div>
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
	<script src="js/timepicker-bs4.js"></script>
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
		   		<script src="insert_user.js"></script>
				<script src="update_user.js"></script>
		<script src="js/sweetalert.js"></script>


        <!-- init js -->
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
	
</script>
    <script>
        $(document).ready(function(){
            $('#example').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 11, "desc" ]],
				
                'ajax': {
                'url':'fetch_user.php',
                },'columns': [
                    
				
					
                      { data: 'image' },
					  { data: 'username' },
					  { data: 'firstname' },
					  { data: 'surname' }, 
					  { data: 'role' },			
                     { data: 'email' },
					 { data: 'number' },
					 { data: 'user_status' },
					 { data: 'status' },
					 { data: 'update' },
					 { data: 'delete' }, 
					 { data: 'id' },
				
					  
					
					
              
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
		 oTable = $('#example').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 
        });
        </script>
		<script>
document.addEventListener('DOMContentLoaded', function () {
	jQuery('#start_time, #end_time, #appt_time, #meet_time, #odd_time').timepicker({

	});
});
</script>
      </script>
    </body>
</html>
