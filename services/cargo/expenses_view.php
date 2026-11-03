<?php
include'header_login.php';
?>
<?php
include 'connect.php';
$stock_no= @$_GET['id'];
$select2=mysqli_query($db,"SELECT *  FROM  office_expenses where stock_no='$stock_no'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $stock_no =$get12['stock_no']; 
			$issuer =$get12['issuer']; 
			$authorizer =$get12['authorizer'];
			$amount_remain =$get12['amount_remain'];
			$opening_stock =$get12['opening_stock']; 
			$open_date =$get12['open_date']; 


		
	}
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>
Sr Classic Expenses
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
Voucher number : <span style="font-weight:bold;"><?php echo $stock_no;?></span><br>
	Cash Authorizer: <span style="font-weight:bold;"><?php echo $authorizer;?></span>
	<p>Cash In Hand: <span style="font-weight:bold;"><?php echo number_format($opening_stock,2);?><br></span>
	Cash Remain :<span style="font-weight:bold;"> <?php echo number_format($amount_remain,2);?></span>
	
	</p>
	</div>
                        </div>
							<div class="row">
					
                             						
					
							
			
							
						
														<?php
  include_once 'config2.php';

?>
    
                     <div id="stock_no"style="display:none;"><?php echo $stock_no;?></div>

			
					</div>
                        <div class="table-responsive">
                        <table id='empTable' class='display dataTable'>
                <thead>
                <tr>
                   
			
                  
                    
					<th>Cash in Hand</th>
					<th>Cash Remain</th>
					<th>Authorizer</th>
					<th>Detail</th>
					<th>V no</th>
					<th>Expneses Ammount</th>
					<th>Issuer</th>
					<th>Inserted Date </th>
					  <th>Time</th>
					
                </tr>
                </thead>
      	  <tfoot>
              <tr>
                <th></th>
				<th>Cash In Hand </th>
				<th>
				<th>		<?php
									$sql = $db->query("SELECT SUM(stock_amount) AS  stock_amount FROM expenses   where stock_no  ='$stock_no'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp=$data['stock_amount'];
				echo number_format($amount_exp,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
				</th>
</tr>       <tr>
                <th></th>
				<th>Total Expenses </th>
				<th>
				<th>		<?php
									$sql = $db->query("SELECT SUM(amount) AS  amount FROM expenses   where stock_no  ='$stock_no'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp=$data['amount'];
				echo number_format($amount_exp,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
				</th>
</tr>   <tr>
                <th></th>
				<th>Ammount Remain </th>
				<th>
				<th>		<?php
									$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain FROM expenses   where stock_no  ='$stock_no'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp=$data['amount_remain'];
				echo number_format($amount_exp,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
				</th>
</tr><tr>
                <th></th>
				<th>Cash Carried </th>
				<th>
				<th>		<?php
									$sql = $db->query("SELECT SUM(amount_curr) AS  amount_curr FROM expenses   where stock_no  ='$stock_no'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp=$data['amount_curr'];
				echo number_format($amount_exp,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
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
			var stock_no = $('#stock_no').text();
		
            $('#empTable').DataTable({
				'stripeClasses':[],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				"order": [[ 4, "asc" ]],
                'ajax': {
                'url':'fetch_exp.php',
									 type:"POST","data": {
        "stock_no":stock_no,
    }
                },'columns': [
                   
					
					{ data: 'stock_amount' , render: $.fn.dataTable.render.number( ',', '.', 2, ), },	
					 { data: 'amount_remain', render: $.fn.dataTable.render.number( ',', '.', 2, ), }, 
					 { data: 'approved' },
					{ data: 'detail' },
					{ data: 'stock_no' },
					{ data: 'amount' },
					{ data: 'username' },
                    { data: 'insert_date_time' },				
					{ data: 'time' },	
					
					
					
						
				
                 
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
      "targets": [ 0,1 ], 
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
       targets: 4,
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
		<script>
$(document).ready(function(){
	var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = dd + '.' + mm + '.' + yyyy;
$('#finish').val(today);
});
</script>
</html>