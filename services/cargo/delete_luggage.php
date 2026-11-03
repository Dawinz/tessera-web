<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Coming Soon | Dashonic - Admin & Dashboard Template</title>
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
		<link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />

    </head>

    <?php
	$id=$_GET['id'];

include 'connect.php';

								$select=mysqli_query($db,"SELECT *  FROM parcel_info WHERE id='$id' ");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $code=$get['code'];

}}
	?>
	
<div style="display:none;"id="id"><?php echo $id;?></div>	
<div style="display:none;"id="code"><?php echo $code;?></div>	
    <body>

    <!-- <body data-layout="horizontal"> -->

         
            
                  
                            <div class="text-center my-auto py-5">

                                <div class="text-muted mb-5">
                                    <h4><?php echo $code;?></h4>
                                    <p>You are about to delete this luggage.please enter your password to proceed</p>
                                </div>

                               

                                <div class="input-group countdown-input-group mx-auto my-5">
                                    <input id="password"type="password" class="form-control border-light shadow" placeholder="Enter your password..." aria-label="search result" aria-describedby="button-email">
                                    <button id="delete"class="btn btn-danger" type="button" id="button-email"><span id="delete_r">Delete</span> <i class="uil uil-trash"></i></button>
                                </div>
                            </div>

                       
                  
                    <!-- end col -->
         
            <!-- end container -->
        
        <!-- end authentication section -->

        <!-- JAVASCRIPT -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
    	   <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
		<script src="js/vendor/jquery-1.12.4.min.js"></script>
		   	<script  src="delete_luggage_user.js"></script> 

        <!-- Countdown js -->
        <script src="assets/js/pages/coming-soon.init.js"></script>
				<script src="js/sweetalert.js"></script>


    </body>
</html>
