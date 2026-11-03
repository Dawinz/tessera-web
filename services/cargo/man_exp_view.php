<?php
include'header_login.php';
?>
<?php
$date_vehicle= @$_GET['date'];
$name_vehicle= @$_GET['date3'];
include 'connect.php';

$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name where date='$date_vehicle' and Name='$name_vehicle'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $vehicle_name =$get12['Name']; 
			$driver =$get12['Driver']; 
			$Conductor =$get12['Conductor']; 


		
	}
}
$username=$_SESSION['username'];
$select222=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
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
	<div style="font-size:14px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Manfiest Date : <?php
echo $date_vehicle;
	?></div>
	<div><div style="font-size:14px;">
	Vehicle Name : <?php echo $vehicle_name;?>
	<p>Driver's Name: <?php echo $driver;?><br>
	Conductor's Name : <?php echo $Conductor;?><br>
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
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Start Header Top Area -->
<?php
include 'connect.php';
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
					<div class="breadcomb-list">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="breadcomb-wp">
									<div class="breadcomb-icon">
										<i class="notika-icon notika-windows"></i>
									</div>
									<div class="breadcomb-ctn">
										<h2>Manifest List</h2>
										<p>List of all luggages inside the manifest <span class="bread-ntd"></span></p>
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
	<!-- Breadcomb area End-->
    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                        <div class="basic-tb-hd">
                            <h2>Manifest Information</h2>

<div style="font-size:14px;">
Date : <span style="font-weight:bold;"><?php echo $date_vehicle;?></span><br>
	Vehicle Name : <span style="font-weight:bold;"><?php echo $vehicle_name;?></span>
	<p>Driver's Name: <span style="font-weight:bold;"><?php echo $driver;?><br></span>
	Conductor's Name :<span style="font-weight:bold;"> <?php echo $Conductor;?></span>
	
	</p>
	</div>
                        </div>
							<div class="row">
					
                             						
					
							
			
							
						
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
                   
			
                  
                    <th> Manifest Total Ammount</th>
					<th>To be paid</th>
					<th>Paid</th>
					<th>Particular</th><th>Catagory Name</th><th>Income </th><th>V_no</th>
					<th>Expenses </th>
					  <th>Date</th>
					  <th>time</th> 
					  <th>Issuer</th> 
                </tr>
                </thead>
                    	  <tfoot>
              <tr>
                <th></th>
                		<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(s_surname) AS  s_surname  FROM parcel_info   where username='$date3' and   date_insert >= ' $date1' and date_insert <= ' $date2'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$due_new=$data['s_surname'];
			
		}}else{
			echo "Nothing";
		}
								?>
				
              
				<th>Total Expenses</th>
				<th>		<?php
									$sql = $db->query("SELECT SUM(insert_amount) AS  insert_amount FROM manifest_exp   where   trip_date >= '$date1' and bus='$date3'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp=$data['insert_amount'];
				echo number_format($amount_exp,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr> 
						  
						  
						  <tr>
                <th></th>
                
				
              
				<th>Total Income</th>
				<th>	<?php
									$sql = $db->query("SELECT SUM(income_amt) AS  income_amt FROM manifest_exp   where   trip_date >= '$date1' and bus='$date3'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp=$data['income_amt'];
				echo number_format($amount_exp,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>Gross To Be Paid</th>
				<th>		
								<?php
									$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM manifest_exp   where   trip_date >= '$date1' and bus='$date3'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_to_exp=$data['to_be'];
				
			
		}}else{
			echo "Nothing";
		}
								?>			<?php
									$sql = $db->query("SELECT SUM(insert_amount) AS  insert_amount FROM manifest_exp   where status ='expenses' and  status_status = 'To pay' and trip_date >= '$date1' and bus='$date3'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp_exp=$data['insert_amount'];
				
			$gross2=$amount_to_exp - $amount_exp_exp;
			echo number_format($gross2,2);
		}}else{
			echo "Nothing";
		}
								?>
				</th>
								</th>
                          </tr>
						  
						  <tr>
                <th></th>
                
				
              
				<th>Gross  Paid</th>
				<th>				<?php
									$sql = $db->query("SELECT SUM(paid) AS  paid FROM manifest_exp   where   trip_date >= '$date1' and bus='$date3'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_to_exp=$data['paid'];
				
			
		}}else{
			echo "Nothing";
		}
								?>			<?php
									$sql = $db->query("SELECT SUM(income_amt) AS  income_amt FROM manifest_exp   where  status ='income' and status_status = 'Payed' and trip_date >= '$date1' and bus='$date3'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp_exp2=$data['income_amt'];
				
			$gross=$amount_to_exp - $amount_exp_exp2;
			echo number_format($gross,2);
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr>
						  <tr>
						    <th></th>
						  <th>Gross Total Remain</th>
						  <th>
						  
						  <?php
						  echo number_format(($gross + $gross2),2);
						  ?>
						  </th>
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
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
      <link rel="stylesheet" type="text/css" href="datatable/min.css"/>
  <script type="text/javascript" src="datatable/min.js"></script>

</body>

    <script>
        $(document).ready(function(){
			var date1_exp = $('#date1_exp').text();
			var date2_exp = $('#date2_exp').text();
			var date3exp = $('#date3exp').text();
            $('#empTable').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 5, "asc" ]],
                'ajax': {
                'url':'fetch_manifest_expenses.php',
									 type:"POST","data": {
        "date1_exp":date1_exp,"date2_exp":date2_exp,"date3exp":date3exp,
    }
                },'columns': [
                   
				
					 { data: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, ), }, 
					 { data: 'to_be' , render: $.fn.dataTable.render.number( ',', '.', 2, ), },	
					{ data: 'paid', render: $.fn.dataTable.render.number( ',', '.', 2, ),  },
					{ data: 'detail' },
					{ data: 'status_name' },
					{ data: 'income_amt', render: $.fn.dataTable.render.number( ',', '.', 2, ),  },
					{ data: 'v_no' },
					{ data: 'insert_amount' },
                    { data: 'date' },
						{ data: 'time' },
						{ data: 'username' },
						
				
                 
                ],
"lengthMenu": [[300, 325, 450, -1], [300, 325, 450, "All"]],
				dom: 'Bfrtip',
        buttons: [
		
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true ,},
            { extend: 'pdfHtml5', footer: true }
        ], rowCallback: function( data, index,cellData,td, cellData, rowData, row, col) {
      if (data.status == "expenses") {
    	
$(row).css('background-color','#ffe8f0');

      } 
	  if (data.status == "To be Payed"){
  

	  } if (data.status == "expenses"){
	  	$(row).css('background-color','#99ff9c');


	  }
	     if ( cellData == '0.00' ) {
          $(td).css('color', 'red');
          $(td).css('text-decoration', 'line-through');
        }
    },
        columnDefs: [ {
      "targets": [ 0,1,2,5 ], 
      createdCell: function (td, cellData, rowData, row, col) {
        if ( cellData == '0' ) {
          $(td).css('background', 'white');
          $(td).html('-', '-');
        }  if ( cellData != '0' ) {
          $(td).css('background-color', '#07b561'); $(td).css('color', 'white');
           $(td).css('border-bottom', '1px solid #07b561');
		   $(td).css('font-weight', 'bold');
        }
		
      }
    },
{
       targets: 6,
      createdCell: function (td, cellData, rowData, row, col) {
          $(td).css('border-left', '1px solid black');  
		  $(td).css('border-right', '1px solid black');
		          $(td).css('background', 'white'); 
				  $(td).css('width', '5px');
      }
    }
	],   
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
url = "vehicle_report_new.php?date="+date+"&date2="+date2+"&date3="+date3+"";
 window.location = url;
			
			
 });
        </script>
</html>