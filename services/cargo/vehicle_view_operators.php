<?php
require_once __DIR__ . '/includes/sr_env.php';
include'header_login.php';
?>
<?php
//index.php
$connect = sr_mysqli('cargo');
$country = '';
$query = "SELECT DISTINCT  name FROM vehicles  ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $country .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Data Table | Notika - Notika Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
				<link rel="stylesheet" href="css/pop_up.css">

    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
			<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap2-toggle.min.css" rel="stylesheet">

    <!-- font awesome CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
	  <link rel="stylesheet" href="css/datapicker/datepicker3.css">
	   <link rel="stylesheet" href="css/chosen/chosen.css">
    <link rel="stylesheet" href="css/bootstrap-select/bootstrap-select.css">
	  
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
  	<div class="breadcomb-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="breadcomb-list">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="breadcomb-wp">
									<div class="breadcomb-icon">
										<i class="notika-icon notika-form"></i>
									</div>
									<div class="breadcomb-ctn">
										<h2>List of  Trips</h2>
										<p>Update the required filled only </p>
			 
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
								<div class="breadcomb-report">
									<button class="btn btn-info" data-toggle="modal" data-target="#myModalone"><i class="glyphicon glyphicon-plus"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	                   <div class="modal fade" id="myModalone" role="dialog">
                                    <div class="modal-dialog modals-default">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <h1>Manifest Vehicle registration</h1><br>
												<table>
												<tr >
												<td> <button style="margin:10px;"class="btn btn-default btn-icon-notika"id="open"><i class="fa fa-plus" aria-hidden="true"></i> Add vehicle</button><br></td>
												
												<td>
												
												   <div class="row" id="show"style="display:none;">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <div class="nk-int-st">
                                        <input type="text" style="margin:10px;"class="form-control" placeholder="Vehicle Name"id="name">
                                    </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <button id="save"class="btn btn-default btn-icon-notika"style="margin:10px;"><i class="notika-icon notika-checked"></i> Add</button>

                            </div>
                        </div>
					
												</td>


											
												</tr>
												</table>
												           
												   <div class="nk-int-mk sl-dp-mn sm-res-mg-t-10"><br>
                                    <h2>List of vehicles</h2>
                                </div>
  <div class="bootstrap-select fm-cmp-mg">
                                    <select class="selectpicker"data-live-search="true"id="vehicle">
										<option>Enter Vehicle Name</option>
										<?php echo  $country;?>
										</select>
                                </div>                                            </div>
                                                <div class="row">
                         <br>
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">

                                        <input type="text" class="form-control"id="driver">
                                        <label class="nk-label">Driver's Name</label>
										<div id="view_place"style="position:absolute;background-color:white;z-index:1000;display:none;">

</div>
                                    </div>
                                </div> 
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">

                                        <input type="text" class="form-control"id="conductor">
                                        <label class="nk-label"id='chide'>Conductor's name</label>
                                    </div>
                                </div>
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">


                                <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
                                        <input type="text" class="form-control" id="time2">
                                    </div>
                                </div>
					<br>
                        <div class="material-design-btn">
                          
                            <button class="btn notika-btn-cyan"style="padding-top:10px;padding-bottom:10px;"id="savebtn2">Register Manifest</button>
                       
                        </div>
                    </div>                                    </div>
                                </div>
                         

                                        </div>
                                    </div>
                                </div>
    <!-- Main Menu area End-->
	<!-- Breadcomb area Start-->

	<!-- Breadcomb area End-->
    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                       
                        <div class="table-responsive">
                        <table id='empTable' class='display dataTable'>
                <thead>
                <tr>
        <th>Vehicle</th>
                
                    <th>Driver's Name</th>
                   <th>Conductor's Name</th> 
                 
 <th>To pay</th><th>Payed</th><th>Total</th> <th>date</th>  <th>View</th> <th>Edit </th>  <th>Insert Manifest</th> <th>User charge</th><th>Charges</th><th>id</th>
                </tr>
                </thead>
                	  <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
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
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap2-toggle.min.js"></script>
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
	  <script src="js/rangle-slider/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="js/rangle-slider/jquery-ui-touch-punch.min.js"></script>
    <script src="js/rangle-slider/rangle-active.js"></script>
	 <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
	<!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
	 <script src="searching.js"></script>
	  <script src="loader.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>
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
	 <script src="js/popup2.js"></script>	
	 	 <script src="insert_vehicle.js"></script>	
		 		<script src="js/sweetalert.js"></script>
				     <script src="js/bootstrap-select/bootstrap-select.js"></script>
	  <script src="place_vehicle_name.js"></script>


</body>

    <script>
        $(document).ready(function(){
            $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
				 "order": [[ 12, "desc" ]],
                'serverMethod': 'post',
                'ajax': {
                'url':'fetch_vehicle_view_admin.php',
                },'columns': [
					{ data: 'Name' },
					
					  { data: 'Driver' },
                    { data: 'Conductor' },
					
					
					  { data: 'to_pay' }, 
					  { data: 'paid' }, 
					  { data: 'total' },
					  { data: 'date' },
					  
					  { data: 'View' },
					  { data: 'update' }, 
					
					  { data: 'Insert' },
					  { data: 'user' },
					  { data: 'charges' },
					  { data: 'id' },
              
                ],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ], rowCallback: function(row, data, index) {
      if (data.status == "open") {
    	
$(row).css('background-color','white');
$(row).css('color','black');
      } else{
		$(row).css('background-color','white');  
	  }

    },
            });
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;
$('#time2').val(today);
        });

        </script>
</html>