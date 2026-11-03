<?php
session_start();
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
	  <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
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

    <!-- End Header Top Area -->
    <!-- Mobile Menu start -->

    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
<?php
include 'nav.php';
?>
    <!-- Main Menu area End-->
	<!-- Breadcomb area Start-->

	<!-- Breadcomb area End-->
    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
			
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
				
                        <div class="basic-tb-hd">
                            <h2>Basic Example</h2>
                            <p>It's just that simple. Turn your simple table into a sophisticated data table and offer your users a nice experience and great features without any effort.</p>
                        </div>
							<div class="row">
					       <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                                         <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                  
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
                                        <input type="text" id="date"class="form-control" placeholder="date">
                                    </div>
                                </div>
                                </div>
                            </div>    
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                                       <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                  
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
                                        <input type="text" id="date2"class="form-control" placeholder="date">
                                    </div>
                                </div>
                                </div>
                            </div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                                       <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                  
               <button type="button" id="insert"class="btn btn-outline-primary">Search</button>
                                </div>
                                </div>
                            </div>
														<?php
  include_once 'config.php';
  $query = "SELECT * FROM trip_route ";
  $result = $db->query($query);
?>
    
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                      
                                    </div>
                                    <div class="nk-int-st">
                                        <label>Choose Trip</label>
                                    <select id="country"class="form-control" data-live-search="true" onchange="FetchState(this.value)"  required>
									   <option>Choose Trip</option>
										     <?php
            if ($result->num_rows > 0 ) {
               while ($row = $result->fetch_assoc()) {
                echo '<option value='.$row['id'].'>'.$row['name'].'</option>';
               }
            }
          ?> 
										</select>
                             
                                    </div>
                                </div>
                            </div>
							             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                      
                                    </div>
                                    <div class="nk-int-st">
                                        <label>Choose Trip</label>
                                   <select name="state" id="state" class="form-control" onchange="FetchCity(this.value)"  required>
            <option>Choose Destination</option>
          </select>
                             
                                    </div>
                                </div>
                            </div>
					</div>
					<div class="test">
					
					</div>
				
<div class="date_s"style="display:none;"><?php
$date= @$_SESSION['date'];
echo $date;
?></div>
<div class="date2_s"style="display:none;">
<?php
$date2_s= @$_SESSION['date2'];
echo $date2_s;
?>
</div>
<div class="trip_s"style="display:none;">
<?php
$trip_s=@$_SESSION['trip'];
echo $trip_s;?></div>
<div class="trips_assign"style="display:none;">
<?php
$trip_assign= @$_SESSION['trip_assign'];
echo $trip_assign;
?></div>
						<!--------
						
						------>
  <table id='empTable' class='display dataTable'>
                <thead>
                <tr>
                   
                    <th>Route</th>
                    <th>Trip</th>
				
					<th>Date</th>	
					<th>Amount</th>
                </tr>
                </thead>
                <td class="serial">4.</td>
				  <tfoot>
            <tr>
                
				<th></th>
				<th></th>
				<th>Total</th>
				<th>			<?php
										$sql = $db->query("SELECT SUM(amount) AS  amount  FROM trip_expens where trips='$trip_s' and date >= '$date' and date <= '$date2_s' and assign_trip = '$trip_assign'");
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
	  <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
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
    <!-- Data Table JS
		============================================ -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
	<!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
</body>
<script type="text/javascript" language="javascript" >
 function FetchState(id){
	  	  var date = $('#date').val();
	 if(date == ""){
		  alert("Enter Date");
		  control.disabled="disabled";
	  }else{
		   
	  }
    $('#state').html('');
    $('#city').html('<option>Select City</option>');
			$('.loading2').text('loading...');

    $.ajax({
      type:'post',
      url: 'ajaxdata3.php',
      data : { country_id :id,date:date},
      success : function(data){
		 
         $('#state').html(data);
$('.loading2').text('Select City...');
      }

    })
  }

  function FetchCity(id){ 
    $('#city').html('');
	 $('.loading').text('loading...');
    $.ajax({
      type:'post',
      url: 'ajaxdata3.php',
      data : { state_id : id},
      success : function(data){
         $('#city').html(data);
		 $('.loading').text('choose Address');
      }

    })
  }

 $(document).ready(function(){
	 	 var date_s = $('.date_s').text();
	 var date2_s = $('.date2_s').text();
	 var trip_s = $('.trip_s').text();
	 var trips_assign = $('.trips_assign').text();
        $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
				 
                    'url':'fetch_report.php',
					
					 type:"POST","data": {
        "date_s":"<?php
$date= @$_SESSION['date'];
echo $date;
?>","date2_s":"<?php
$date2_s= @$_SESSION['date2'];
echo $date2_s;
?>","trip_s":"<?php
$trip_s=@$_SESSION['trip'];
echo $trip_s;?>","trips_assign":"<?php
$trip_assign= @$_SESSION['trip_assign'];
echo $trip_assign;
?>"
    }
             },
                'columns': [
                   
                    { data: 'trips' },
                    { data: 'assign_trip' }, 
					{ data: 'date' },
					{ data: 'amount' },
                ], dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true },
				{ extend: 'print', footer: true }
        ]  
            });
		
 });
 $('#insert').click(function(){
	 
var date = $('#date').val();
var date2 = $('#date2').val();
var trip = $( "#country option:selected" ).text();
var trip_assign = $( "#state option:selected" ).text();

	 if (date==""){
		 alert("Date is Empty");
	 } else if(date2 == ""){
		 alert("date is Empty");
		 control.disabled="disabled";
	 } else if (trip == "Choose Destination"){
		alert("Destinatin is Empty"); 
		control.disabled="disabled";
	 } else if (trip_assign == "Destination not found"){
		alert("Destination is Empyt"); 
		control.disabled="disabled";
	 }
$.ajax({
				url:"session.php",
				method:"POST",
				data:{date:date,date2:date2,trip:trip,trip_assign:trip_assign},
				success:function(response){
$('.test').html(response);
window.location.reload(true); 
	}
			
			});
			
			
 });
</script>
</html>