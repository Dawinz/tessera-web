<?php
include'header_login.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Sr classic coach<br/>address,ilala boma<br>operations@srclassiccoach.co.tz<br></title>
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
include 'top_header.php';
?>
    <!-- Mobile Menu end -->

    <!-- Main Menu area End-->
	<!-- Breadcomb area Start-->
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
										<h2>Luggages Inside The store</h2>
										<p>list of all luggage inside the store <span class="bread-ntd"></span></p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
								<div class="breadcomb-report">
									<button data-toggle="tooltip" data-placement="left" title="Download Report" class="btn"><i class="notika-icon notika-sent"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Breadcomb area End-->
    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                        <div class="basic-tb-hd">
                            <h2>Luggages Table</h2>
                            <p>List of Luggages inside the store</p>
                        </div>
							<div class="row">
					     
                             						
						
							
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                       
								
                                </div>
								
                            </div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"style="display:none;">
                                <div class="form-group ic-cmp-int">
								    <div class="form-ic-cmp">
                                    
                                    </div>
                                   <select id="trip"class="selectpicker" data-live-search="true">
											<option>Dar es Salaam to Lusaka</option>
											<option>Dar es Salaam to Congo</option>
											<option>Dar es Salaam to Lubumbashi</option>
										
										</select>
                                </div>
                            </div>	
							
						
														<?php
  include_once 'config2.php';

?>
    
                     <div id="date1_exp"style="display:none;"><?php
$date1= @$_GET['date'];
echo $date1;
?></div>
					 <div id ="date2_exp"style="display:none;"><?php
$date2= @$_GET['date2'];
echo $date2;
?></div>					 <div id ="date3exp"style="display:none;"><?php
$date3= @$_GET['date3'];
echo $date3;
?></div>
			
					</div>
                        <div class="table-responsive">
                        <table id='empTable' class='display dataTable'>
						
                <thead>
                <tr>
                    <th>sender's name</th>
                    <th>Receiver's name</th>
					<th>Date</th>
					<th>Detail</th>
					<th>code</th>
					<th>Ammount</th>
					<th>TO PAY</th>
					<th>Payment status</th>
					<th>Quantity</th>
                    
                </tr>
                </thead>
                	  <tfoot>
              <tr>
                <th></th>
                		<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(s_surname) AS  s_surname  FROM parcel_info   where store='store' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$due_new=$data['s_surname'];
			
		}}else{
			echo "Nothing";
		}
								?>
				
              
				<th>To Be Payed Total</th>
				<th>			<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(amount) AS  amount  FROM parcel_info   where   store='store' and status='To be Payed'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
				$new_amount = $amount + $due_new;
				echo number_format($new_amount ,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr> 
						  
						  
						  <tr>
                <th></th>
                
				
              
				<th>Cash Payed</th>
				<th>		<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(amount) AS  amount  FROM parcel_info   where   store='store' and status='paid'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
			
		}}else{
			echo "Nothing";
		}
								?>	
								<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(due) AS  due  FROM parcel_info   where   store='store' and status='Due payment'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$due_status=$data['due'];
				$total_due=$amount + $due_status;
				echo number_format($total_due,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>TOTAL  QUANTITY</th>
				<th>			<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(question) AS  question  FROM parcel_info   where   store='store'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$due_update=$data['question'];
				echo $due_update;
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr>
						  
						  <tr>
                <th></th>
                
				
              
				<th>Total</th>
				<th>			<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info   where  store='store'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
				echo number_format($amount ,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr>
        </tfoot>
            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="script1"style="display:none;"><script src="js/vendor/jquery-1.12.4.min.js"></script></div>
    <!-- Data Table area End-->
    <!-- Start Footer area-->
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2018 
. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                    </div>
                </div>
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
	<!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
	
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
		  <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
    <!-- Data Table JS
		============================================ -->

    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>

</body>

    <script>
        $(document).ready(function(){
			var date1_exp = $('#date1_exp').text();
			var date2_exp = $('#date2_exp').text();
			var date3exp = $('#date3exp').text();
			
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                'url':'fetch_luggages_sum.php',
									 type:"POST","data": {
        "date1_exp":date1_exp,"date2_exp":date2_exp,"date3exp":date3exp,
    }
                },'columns': [
                    { data: 's_name' },
					  { data: 'r_name' },
					{ data: 'date_insert' },
					  { data: 'detail' },
                    { data: 'code' },
					{ data: 'amount' },
					{ data: 'due' },
					{ data: 'status' },
					{ data: 'question' },
       
                 
                ],
				"lengthMenu": [[170, 190, 250, -1], [100, 125, 150, "All"]],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true },
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
url = "store_total.php?date="+date+"&date2="+date2+"&date3="+date3+"";
 window.location = url;
			
			
 });
        </script>
</html>