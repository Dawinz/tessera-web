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
										<h2>Office Expenses Chart</h2>
										<p>List of office expenses chart ordered by date</p>
			 
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
								<div class="breadcomb-report">
									<button data-toggle="tooltip" data-placement="left"  popup-open="popup-2" href="javascript:void(0)"class="btn" id="popup"style="background-color:red;"><i class="glyphicon glyphicon-usd"></i></button>
								</div>
							</div>
						</div>
					</div>
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
                   <th>Authoriser</th>
                    <th>Authorised Date</th>
                   <th>Cash In Hand</th> 
                     <th>V_no</th>
                 <th>Ammount Remain</th>
                <th>Date Insert</th>
                  <th>Time</th>
				  <th>View</th> 
				  <th>Insert</th> 
				  <th>Edit</th> 
				  
                </tr>
                </thead>
                	  <tfoot>
            <tr>
                <th></th>
                <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                          </tr>
        </tfoot>
            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
		<div class="popup" popup-name="popup-2">
	<div class="popup-content">
	<h2>Enter Cash In Hand</h2>
<form>
  <div class="form-group">
   <label for="exampleInputEmail1">Enter cash in hand for expenses use</label><br>

                <input type="input" class="form-control" id="due_amount" aria-describedby="emailHelp" placeholder="Cash in Hand Ammount"pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"> 
	<br>
    <input type="input" class="form-control" id="title" aria-describedby="emailHelp" placeholder="Authorised by"> <br>
		     <div class="datepicker-int mg-t-30">
                                <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
                                        <input type="text" class="form-control" id="time2">
                                    </div>
                                </div>
                            </div>

  <button type="button" class="btn btn-primary btn-lg btn-block"id="savebtn2">save</button> <span class="inserting"></span> 

  </div>
  
  
  </form>
	<a class="close-button" popup-close="popup-2" href="javascript:void(0)">x</a>
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
	   <script src="currency.js"></script>
    <!-- Data Table JS
		============================================ -->

    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
<script src="js/vendor/jquery-1.12.4.min.js"></script>
	 <script src="js/data-table/jquery.dataTables.min.js"></script>
    <script src="js/data-table/data-table-act.js"></script>
	 <script src="js/popup2.js"></script>	
	 	 <script src="insert_cash_hand.js"></script>	
		 		<script src="js/sweetalert.js"></script>
			

</body>

    <script>
        $(document).ready(function(){
            $('#empTable').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
				 "order": [[ 3, "desc" ]],
                'serverMethod': 'post',
				language: (window.TesseraStates && TesseraStates.listLanguage)
					? TesseraStates.listLanguage('No cash-in-hand entries', 'No cash entries match your search')
					: undefined,
                'ajax': {
                'url':'fetch_cash_hand.php',
				error: (window.TesseraStates && TesseraStates.loadError)
					? TesseraStates.loadError('Could not load cash-in-hand')
					: undefined
                },'columns': [
					{ data: 'authorizer' },
					  { data: 'open_date' },
                    { data: 'opening_stock' },
					  { data: 'stock_no' }, 
					  { data: 'amount_remain' }, 
					  { data: 'date' },
					  { data: 'time' },
					  { data: 'View' },
					  { data: 'Insert' },  
					  { data: 'end' }, 
					
              
                ],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ], rowCallback: function(data, index,cellData,td, cellData, rowData, row, col) {
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
     
		
      }
    },
{
       targets: 3,
      createdCell: function (td, cellData, rowData, row, col) {
          $(td).css('border-left', '1px solid black');  
		  $(td).css('border-right', '1px solid black');
		          $(td).css('background', 'white'); 
				  $(td).css('width', '3px');
      }
    }
	],
	
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