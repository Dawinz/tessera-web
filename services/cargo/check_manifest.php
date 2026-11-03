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
include'header_login.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> Sr classic coach</title>
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
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">
	<link rel="stylesheet" type="text/css" href="/media/css/site-examples.css?_=0602f7ec58abe00302963423bf7a8d5a">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
	<style type="text/css" class="init">
	
	</style>
	<script type="text/javascript" src="/media/js/site.js?_=30648b1410332bada11fa3ff8050ff88"></script>
	<script type="text/javascript" src="/media/js/dynamic.php?comments-page=extensions%2Fbuttons%2Fexamples%2Fhtml5%2FtitleMessage.html" async></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
	<script type="text/javascript" language="javascript" src="../../../../examples/resources/demo.js"></script>
	<script type="text/javascript" class="init">
	


$(document).ready(function() {
	var printCounter = 0;

	// Append a caption to the table before the DataTables initialisation
	$('#example').append('<caption style="caption-side: bottom">A fictional company\'s staff table.</caption>');

	$('#example').DataTable( {
		dom: 'Bfrtip',
		buttons: [
			'copy',
			{
				
				 footer: true ,
				extend: 'excel',
				
				messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
			},
			{
				footer: true ,
				extend: 'pdf',
				messageBottom: null
			},
			{
				footer: true ,
				extend: 'print',
				messageTop: function () {
					printCounter++;

					if ( printCounter === 1 ) {
						return 'This is the first time you have printed this document.';
					}
					else {
						return 'You have printed this document '+printCounter+' times';
					}
				},
				messageBottom: null
			}
		]
	} );
} );



	</script>
	
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
    <!-- Data Table area Start-->		<div class="breadcomb-area">
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
					
					
  
                     
                    </form>
					      <div class="dropdown-trig-sgn">
                                        <button class="btn triger-fadeIn" data-toggle="dropdown">Choose users</button>
                                        <ul class="dropdown-menu triger-fadeIn-dp">
                                           					 <form method="post" action="checkbox-db.php">
<table class="table table-bordered">
	<thead>
		<tr>
			<th></th>
			<th>Choose users</th>
			
		</tr>
	</thead>
	<tbody>
																		<?php
include 'connect.php';																
																$select1=mysqli_query($db,"SELECT *  FROM users ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $u_name=$get13['username']; 
?>
		<tr>
			<td><input type="checkbox" name="prodid[]" value="<?php echo $u_name;?>"></td>
			<td><?php echo $u_name;?>
				<input type="hidden" name="prodname[]" value="<?php echo $u_name;?>">
				<input type="hidden" name="prod_price[]" class="form-control" id="car"value="">
				<input type="hidden" name="prod_qty[]" class="form-control"id="vehicle"/>
			</td>
			
		</tr>
<?php
}}}
											?>
	</tbody>
</table>
<div class="text-center">
	<input type="submit" name="submit" class="btn btn-success" value="Submit">
	</div>
</div>
</form>
                                        </ul>
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
       			<div class="demo-html">
					<table id="example" class="display" style="width:100%">
						<thead>
							<tr>
								<th>Name</th>
								<th>Position</th>
								<th>Office</th>
								<th>Age</th>
								<th>Start date</th>
								<th>Salary</th>
								<th>Trip Date</th><th>Trip Date</th>
							</tr>
						</thead>
						<tbody>
						
							<?php
include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM   product  ORDER BY product_id ASC
 LIMIT 1");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   $product_price=$get12['product_price'];
		   $product_quantity=$get12['product_quantity'];
		
			
echo "$product_price.$product_quantity";


		
	}
}
?>	
<?php
include 'connect.php';
$select22=mysqli_query($db,"SELECT *  FROM   product");
$select_num22=mysqli_num_rows($select22);
if($select_num22 !=0){
   	WHILE($get122=mysqli_fetch_array($select22)){
    	   $product_price1=$get122['product_price'];
		   $product_quantity1=$get122['product_quantity'];
		   $product_name1=$get122['product_name'];

			
$select123=mysqli_query($db,"SELECT * FROM manifest WHERE 	trip_date='$product_price' and bus='$product_quantity' and parcel_uploader='$product_name1'");
$select_num123=mysqli_num_rows($select123);
if($select_num123 !=0){
   	WHILE($get123=mysqli_fetch_array($select123)){
    	   $status=$get123['status']; 
		   $username=$get123['username'];
		   $amount=$get123['amount'];
		   $paid=$get123['paid'];  
		   $to_be=$get123['to_be'];
		   $uploader=$get123['parcel_uploader']; 
		   $code=$get123['code'];
		   $trip_date=$get123['trip_date'];	   $id=$get123['id'];
		   echo '
		   
		   	<tr>
								<td>'.$username.'</td>
								<td>'.$amount.'</td>
								<td>'.$to_be.'</td>
								<td>'.$paid.'</td>
								<td>'.$code.'</td>
								<td>'.$uploader.'</td>
								<td>'.$trip_date.'</td>
								<td>'.$id.'</td>
			</tr>
		   ';
		
		
	}

	
}


		
	}
	
}
?>

				<?php
			
include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM   product  ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   $product_name=$get12['product_name'];
		
			
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where parcel_uploader='$product_name' and trip_date='$product_price' and bus='$product_quantity' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
				echo ''.$product_name.' '.number_format($total_amount_t ,2).'<br>';
				echo '
				
				   	<tr>
								<td>'.$product_name.'</td>
								<td>Total Ammount</td>
								<td>'.$total_amount_t .'</td>
								<td>Total Topay</td>
								<td>'.$to_be.'</td>
								<td>Total paid</td>
								<td>'.$paid.'</td>
								<td>Sumation</td>
			</tr>
				';
			
		}}
								


		
	}
}
?>
						</tbody>
					
					</table>
				</div>
			

                </div>
            </div>
        </div>
    </div></div>
								<?php
include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM   product  ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   $product_name=$get12['product_name'];
		
			
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where parcel_uploader='$product_name' GROUP BY (parcel_uploader)");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
				echo ''.$product_name.' '.number_format($total_amount_t ,2).'<br>';
			
		}}
								


		
	}
}
?>
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

    <!-- bootstrap JS
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
    <!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
    <!--  animation JS
		============================================ -->
    <script src="js/animation/animation-active.js"></script>
    <!--  Chat JS
		============================================ -->
    <script src="js/chat/jquery.chat.js"></script>
    <!--  todo JS
		============================================ -->
    <script src="js/todo/jquery.todo.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
	 <script src="loader.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>
	  	 <script src="loader.js"></script>
	 <script src="loader_operator.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>
	   <script src="repeater.js" ></script>
	    <script>var $j = jQuery.noConflict(true);</script>
    <script>
      $(document).ready(function(){
       console.log($().jquery); // This prints v1.4.2
       console.log($j().jquery); // This prints v1.9.1
      });
   </script>
	 
    <!-- Data Table JS
		============================================ -->

    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	 <script src="searching.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
<script src="jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
     
</body>

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
	alert();
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
</html>