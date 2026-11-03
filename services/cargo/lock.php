

<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Check Manifest</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <?php
include 'connect.php';
$id=$_GET['u'];
$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name WHERE id='$id' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $Name =$get12['Name'];  
			$Driver =$get12['Driver']; 
			$Conductor =$get12['Conductor']; 

		
	}
}
?>
    <body>
<span id="id"style="display:none;"><?php echo $id;?></span>
    <!-- <body data-layout="horizontal"> -->

        <div class="authentication-bg min-vh-100">
            <div class="bg-overlay bg-white"></div>
            <div class="container">
                <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                    <div class="row justify-content-center my-auto">
                       <div class="col-xxl-4 col-xl-6"style="width:60%; overflow-y: hidden; ">
                                <div class="card"style="padding:20px;">
                            <div class="text-center py-5">
                                <div class="mb-4 mb-md-5">
                                    <a href="vehicle_view.php" class="d-block auth-logo">
                                        <img src="images/home.png" alt="" height="80" class="auth-logo-dark">
                                        <img src="images/home.png" alt="" height="80" class="auth-logo-light">
                                    </a>
                                </div>
                                <div class="mb-4">
                                    <h5 class="">Lock Manfiest with Vehicle</h5>
            <p>Set the passcode.Once you lock The manifest with <span style="font-weight:bold;color:green;font-size:18px;"><?php echo $Driver;?> </span> and  <span style="font-weight:bold;color:green;font-size:18px;"><?php echo $Conductor;?></span>'s vehicle you wont be able to Insert items in the manifest</p>
                                </div>
			

                       
                        
                                    <div class="form-floating form-floating-custom mb-3">
                                        <input type="password" class="form-control" id="password" placeholder="Enter password">
                                        <label for="input-username">password</label>
                                        <div class="form-floating-icon">
                                            <i class="uil uil-pen"></i>
                                        </div>
                                    </div>
                             

                           

                                    <div class="mt-3">
                                        <button class="btn btn-info w-100" type="button"id="delete">Save</button>
                                    </div>
                        

                             
                            </div>
                        </div><!-- end col -->
                    </div></div><!-- end row -->
<div id="loaderdiv"></div>


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center text-muted p-4">
                                <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script> sr clasicc<i class="mdi mdi-heart text-danger"></i> </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- end container -->
        </div>
        <!-- end authentication section -->

        <!-- JAVASCRIPT -->
				 <script src="js/vendor/jquery-1.12.4.min.js"></script>

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
        <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
		    <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
        <!-- notification init -->
        <script src="assets/js/pages/notification.init.js"></script>
<script src="js/sweetalert.js"></script>
<script src="lock.js"></script>
    </body>
</html>
<script>
