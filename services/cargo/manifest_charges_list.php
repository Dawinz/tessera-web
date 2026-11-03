<?php
include'header_login.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Data Table | Notika - Notika Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- font awesome CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
    <!-- meanmenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu/meanmenu.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
	<!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
    <link rel="stylesheet" href="css/wave/button.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- Notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/notika-custom-icon.css">
    <!-- Data Table JS
		============================================ -->
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
	
</head>

<body>

    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Start Header Top Area -->
<?php
include'connect.php';
$username=$_SESSION['username'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $level =$get12['level']; 

		
	}
}
?>
<?php
include 'top_header.php';

?>
    <!-- Main Menu area End-->
	<!-- Breadcomb area Start-->

	<!-- Breadcomb area End-->
    <!-- Data Table area Start-->
			<div class="breadcomb-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="breadcomb-list">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="breadcomb-wp">
									<div class="breadcomb-icon">
										<i class="notika-icon notika-windows"></i>
									</div>
									<div class="breadcomb-ctn">
										<h2>list Of All Manifest Expenses</h2>
										<p>Only the admin and the uploader can delete the entity <span class="bread-ntd"></span></p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                       
                        <div class="table-responsive">
                        <table id='empTable' class='display dataTable'>
                <thead>
                <tr>
                    <th>Vehicle Name</th>               
                    <th>Trip Date</th>
                   <th>Particular</th> 
				   <th>Income</th>  
				   <th>V no</th>
				  
  <th>Ammount</th>
  <th>Issuer</th>
   <th>Inserted Date</th> 
    <th>Time</th>    <th>Delete</th> 

                </tr>
                </thead>
                	  <tfoot>
            <tr>
                <th></th>
                <th></th>
				<th></th>
                <th></th><th></th>
                <th></th>    <th></th>    <th></th><th></th>
                          </tr>
        </tfoot>
            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->
    <!-- Start Footer area-->
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
             
            </div>
        </div>
    </div>
    <!-- End Footer area-->
    <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/meanmenu/jquery.meanmenu.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="js/counterup/jquery.counterup.min.js"></script>
    <script src="js/counterup/waypoints.min.js"></script>
    <script src="js/counterup/counterup-active.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="js/flot/jquery.flot.js"></script>
    <script src="js/flot/jquery.flot.resize.js"></script>
    <script src="js/flot/flot-active.js"></script>
    <!-- knob JS
		============================================ -->
    <script src="js/knob/jquery.knob.js"></script>
    <script src="js/knob/jquery.appear.js"></script>
    <script src="js/knob/knob-active.js"></script>
    <!--  Chat JS
		============================================ -->
    <script src="js/chat/jquery.chat.js"></script>
    <!--  todo JS
		============================================ -->
    <script src="js/todo/jquery.todo.js"></script>
	 <script src="searching.js"></script>
	<!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
	 <script src="loader.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>
	  			<script src="js/sweetalert.js"></script>

    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- Data Table JS
		============================================ -->

    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
<script src="jquery.min.js"></script>
	 <script src="js/data-table/jquery.dataTables.min.js"></script>
    <script src="js/data-table/data-table-act.js"></script>
</body>

    <script>
        $(document).ready(function(){
            $('#empTable').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
				"order": [[ 4, "desc" ]],
                'serverMethod': 'post',
                'ajax': {
                'url':'fetch_store_exp.php',
                },'columns': [
         
					{ data: 'bus' },
					{ data: 'trip_date' },
					  { data: 'detail' },
					  { data: 'income' },
					  	   { data: 'v_no' },
                    { data: 'insert_amount' },
                   
					   { data: 'insert_date' },
					  { data: 'username' }, 
					 
				
					  { data: 'time' },
					  { data: 'edit' },
              
                ],
				"lengthMenu": [[15, 50, 70, -1], [15, 50, 70, "All"]],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],rowCallback: function(data, index,cellData,td, cellData, rowData, row, col) {
      if (data.status == "open") {
    	
$(row).css('background-color','white');
$(row).css('color','black');
      } else{
		$(row).css('background-color','white');  
	  }

    },
	 columnDefs: [ {
      "targets": [ 0,1,2,6], 
      createdCell: function (td, cellData, rowData, row, col) {
        if ( cellData == '0' ) {
          $(td).css('background', 'white');
          $(td).html('', '-');
        }
		
      }
    },
{
       targets: 4,
      createdCell: function (td, cellData, rowData, row, col) {
          $(td).css('border-left', '1px solid black');  
		  $(td).css('border-right', '1px solid black');
		          $(td).css('background', 'white'); 
				  $(td).css('width', '3px');
      }
    }
	], 
            });

        });
        </script>
</html>