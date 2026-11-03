<?php
require_once __DIR__ . '/includes/sr_env.php';
//index.php
$connect = sr_mysqli('sr');
$country = '';
$query = "SELECT trip_date FROM manifest GROUP BY trip_date ORDER BY MAX(id) DESC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $country .= '<option value="'.$row["trip_date"].'">'.$row["trip_date"].'</option>';
}
?>
<?php

                    
$date1= @$_GET['date'];
$date2= @$_GET['vehicle'];
$date3= @$_GET['users'];


include'header_login.php';

include 'connect.php';
$select222=mysqli_query($db,"SELECT *  FROM  users  where username='$date3'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $fullname =$get122['fullname']; 

		
	}
}
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>
	<div>
	 <div class="
">
	
	<p style="font-size:25px;display:flex;
     justify-content:center;
     align-items:center;"> 
	 <img src ="http://www.redetrade.com/sr.png" width='80' height='80' /><br>
	 SR CLASSIC CARGO MANIFEST CHART</p>
	<div style="font-size:18px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Manfiest Date : <?php
echo $date2;
	?>
	<br>Vehicel : <?php echo $date2;?>
	</div>
	<div><div style="font-size:18px;">
	Operator's Name : <?php echo $date3;?>
	<br>
	Prepared by <?php echo $fullname;?>,<br> <span style=" ">On <?php echo $date_insert; ?> Around <?php echo $date_time_insert;?></span>
	</p>
	</div></div>
	</title>
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
			 <link href="css/summernote/summernote2.css" rel="stylesheet">
  <script src="css/summernote/summernote2.js"></script>
      <link rel="stylesheet" href="css/summernote/summernote.css">
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
    <!-- Mobile Menu end -->

    <!-- Main Menu area End-->
	<!-- Breadcomb area Start-->
	<div class="breadcomb-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					
					        <div class="panel panel-default"><br>
           	<div class="breadcomb-wp">
									<div class="breadcomb-icon">
										<i class="notika-icon notika-windows"></i>
									</div>
									<div class="breadcomb-ctn">
										<h2>Manifest  Charges List </h2>
										<p>Search Manifest by date,vehicle and mulitple users  <span class="bread-ntd"></span></p>
									</div>
								</div>
								
                <div class="panel-body">
                    <span id="success_result"></span>
                    <form method="post" id="repeater_form">
					 
					           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                           
                                    </div>
                            <select name="country" id="country" class="form-control action" data-live-search="true">
    <option value="">Select Trip Date</option>
    <?php echo $country; ?>
   </select>
                                </div>
                            </div>         <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                           
                                    </div>
                                  <select name="state" id="state" class="form-control action" data-live-search="true">
    <option value="">Vehicle </option>
   </select>
                                </div>
                            </div>  
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                   
                                    </div>
                                    <div class="nk-int-st">
									
                                        <input type="text" class="form-control" id="amount"placeholder="Total Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                    </div>
                                </div>
                            </div>
					
					
  
                     
                    </form>

</form>
                                        </ul>
                                    </div>

                </div>
            </div>
				
				            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="row">
                          
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                   
                                    <div class="nk-int-st">
                                       
										   <div class="summernote-wrap mg-t-30">
                       
                        <div class="cmp-tb-hd bsc-smp-sm">
                            <label>Extra details</label>
                        </div>
                       <div id="summernote" ><p>NGUO</p></div>
                       
                      
                       
                    </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
						      <div class="nk-int-st">
                                         <div class="summernote-clickable">
                            <button class="btn btn-primary btn-sm hec-button"id="insert">Save
                                </button>
                       
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
		 <script src="searching.js"></script>

    <!-- End Footer area-->
    <!-- jquery
	
		============================================ -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
	    <script src="js/summernote/summernote2.js"></script>
	<link href="css/summernote/summernote2.css" rel="stylesheet">
	
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
		 <script src="loader.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>
	
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
			<script src="js/sweetalert.js"></script>


	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
  



</body>

  <script>
  $('#summernote').summernote({
  height: 200,   //set editable area's height
  codemirror: { // codemirror options
    theme: 'monokai'
  }
});
  </script>

    <script>
        $(document).ready(function(){
			var date1_exp = $('#date1_exp').text();
			var date2_exp = $('#date2_exp').text();
			var date3exp = $('#date3exp').text();
			
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 2, "desc" ]],
                'ajax': {
                'url':'fetch_user_manifest.php',
									 type:"POST","data": {
        "date1_exp":date1_exp,"date2_exp":date2_exp,"date3exp":date3exp,
    }
                },'columns': [
               { data: 'username' },
					{ data: 'detail' },
                    
					{ data: 'amount' },
					{ data: 'to_be' },	
					{ data: 'paid' },
					{ data: 'date' },
					{ data: 'code' },
                     { data: 'left' }, 
					 { data: 'action' }, 
       
                 
                ],
				"lengthMenu": [[100, 125, 150, -1], [100, 125, 150, "All"]],
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
			var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;
$('#date').val(today);
$('#date2').val(today);
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
url = "store_charge.php?date="+date+"&date2="+date2+"&date3="+date3+"";
 window.location = url;
			
			
 });
        </script>
			<script>
$(document).ready(function(){
 $('.action').change(function(){

  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';
   if(action == "country")
   {
    result = 'state';
	$('#car').val(query);
   }
   if (action == "city")
   {
    result = 'city';
   }
   $.ajax({
    url:"fetch_dependent.php",
    method:"POST",
    data:{action:action, query:query},
    success:function(data){
     $('#'+result).html(data);
	
    }
   })
  }
 });
 $('#state').change(function(){
var state = $( "#state option:selected" ).text();
$('#vehicle').val(state);
 });
});
</script>
<script>
$(document).on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
	var trip_date = $( "#country option:selected" ).text();
	var vehicle = $( "#state option:selected" ).text();
	var users = $( "#city2 option:selected" ).text();
if(trip_date =='Select Trip Date'){
	swal("Enter Trip Date!");
				 			 control.disabled="disabled";

}
if(vehicle =='Select Vehicles'){
	swal("Enter Vehicel Name!");
				 			 control.disabled="disabled";

}if(vehicle =='Vehicle'){
		swal("Enter Vehicel Name!");
					 			 control.disabled="disabled";

}
url = "manifest_per_user.php?date="+trip_date+"&vehicle="+vehicle+"&users="+users+"";
 window.location = url;
    }
});

</script>
</html>