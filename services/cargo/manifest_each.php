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

<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Form Components | Notika - Notika Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
			<link rel="stylesheet" href="css/pop_up.css">

		============================================ -->
		 <link href="css/summernote/summernote2.css" rel="stylesheet">
  <script src="css/summernote/summernote2.js"></script>
    <link href="css.font/font.css" rel="stylesheet">
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
    <!-- summernote CSS
		============================================ -->
    <link rel="stylesheet" href="css/summernote/summernote.css">
    <!-- Range Slider CSS
		============================================ -->
    <link rel="stylesheet" href="css/themesaller-forms.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- Notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/notika-custom-icon.css">
    <!-- bootstrap select CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap-select/bootstrap-select.css">
    <!-- datapicker CSS
		============================================ -->
    <link rel="stylesheet" href="css/datapicker/datepicker3.css">
    <!-- Color Picker CSS
		============================================ -->
    <link rel="stylesheet" href="css/color-picker/farbtastic.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/chosen/chosen.css">
    <!-- notification CSS
		============================================ -->
    <link rel="stylesheet" href="css/notification/notification.css">
    <!-- dropzone CSS
		============================================ -->
    <link rel="stylesheet" href="css/dropzone/dropzone.css">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
			<link rel="stylesheet" href="css/pop_up.css">

    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
	  
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">
	<link rel="stylesheet" type="text/css" href="/media/css/site-examples.css?_=0602f7ec58abe00302963423bf7a8d5a">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
	<style type="text/css" class="init">
	
	</style>
	<script type="text/javascript" src="/media/js/site.js?_=30648b1410332bada11fa3ff8050ff88"></script>
	<script type="text/javascript" src="/media/js/dynamic.php?comments-page=extensions%2Fbuttons%2Fexamples%2Fprint%2Fselect.html" async></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
	<script type="text/javascript" language="javascript" src="../../../../examples/resources/demo.js"></script>
	<script type="text/javascript" class="init">
	


$(document).ready(function() {
	$('#example').DataTable( {
		"order": [[ 7, "des" ], [ 3, "desc" ]],
		"lengthMenu": [[300, 350, 450, -1], [300, 350, 450, "All"]],
		dom: 'Bfrtip',
		
		buttons: [
			{
				extend: 'print',
				text: 'Print all',
				exportOptions: {
					modifier: {
						selected: null
					}
				}
			},
			{
				extend: 'print',
				text: 'Print selected'
			}
		],
		select: true,
		
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
    <!-- Main Menu area End-->
	<!-- Breadcomb area Start-->
	<div class="container">
     
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
					
					
                        <div id="repeater">
                            <div class="repeater-heading" align="right">
							 <div class="form-group ic-cmp-int">
                                <button type="button" class="btn btn-default repeater-add-btn"><i class="glyphicon glyphicon-plus"style="color:green;"></i></button>
								</div>
                            </div>
                            <div class="clearfix"></div>
                          <div class="items" data-group="programming_languages">
                                <div class="item-content">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-9">
                                             
                                                <select data-skip-name="true" data-name="skill[]" class="form-control" >
                                                    <option value="">Select  users</option>
        <option value="">Select</option>
                                                    <option value="PHP">PHP</option>
                                                    
                                                                         					<?php
include 'connect.php';																
																$select1=mysqli_query($db,"SELECT *  FROM users ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $u_name=$get13['username']; 
			echo '<option value="'.$u_name.'">'.$u_name.'</option>';
}}}
											?>
                                                </select>
                                            </div>
                                           
                                                <button id="remove-btn" onclick="$(this).parents('.items').remove()" class="btn btn-default"><i class="glyphicon glyphicon-remove"style="color:#d1021a;"></i></button>
                                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group" align="center">
                        
                            <input type="submit" name="insert"  class="btn btn-default" value="Search" />
                        </div>
                    </form>
                </div>
            </div>
    </div>
    
	<!-- Breadcomb area End-->
    <!-- Form Element area Start-->
   <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                       
                        <div class="table-responsive">
	<table id="example" class="display" style="width:100%">
						<thead>
							<tr>
								<th>Reciever's name</th>
								<th>Date</th>
								<th>Detail</th>
								<th>Ammount</th>
								<th>To paid</th>
								<th>Paid</th>
								<th>Status</th>
								<th>Code</th>
								<th>Q'left</th>
								<th>Check</th>
							</tr>
						</thead>
						<tbody>
					
										<?php
include 'connect.php';

$select2=mysqli_query($db,"SELECT *  FROM  parcel_info where store = 'store' or store = 'half_out'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $id =$get12['id']; 
			$date =$get12['date_insert']; 
			$username= $get12['r_name']; 
			$code= $get12['code']; 
			$amount= $get12['amount']; 
			$paid= $get12['paid']; 
			$to_be= $get12['to_be']; 
			$status=  $get12['status']; 
			$detail=  $get12['detail']; 
			$quantity=  $get12['question']; 
			$scanned=  $get12['scanned']; 
echo '
	<tr>
								<td>'.$username.'</td>
								<td>'.$date.'</td>
								<td>'.base64_decode($detail).'</td>
								<td>'.number_format($amount,2).'</td>
								<td>'.$to_be.'</td>
								<td>'.$paid.'</td>
								<td>'.$status.'</td>
								<td>'.$code.'</td>
								<td>'.$scanned.'/'.$quantity.'</td>
								<td></td>
							</tr>

';

		
	}
}
?>
						</tfoot>
					</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Element area End-->
    <!-- Datepicker area Start-->

    <!-- Datepicker area End-->
    <!-- Color Picker area Start-->
 
    <!-- Color Picker area End-->
    <!-- Summernote area Start-->

    <!-- Summernote area End-->
    <!-- Dropzone area Start-->

    <!-- Dropzone area End-->
    <!-- Start Footer area-->
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-copy-right">
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer area-->
    <!-- jquery
	
		============================================ -->
		<script src="js/sweetalert.js"></script>

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
    <!-- Input Mask JS
		============================================ -->
    <script src="js/jasny-bootstrap.min.js"></script>
    <!-- icheck JS
		============================================ -->
    <script src="js/icheck/icheck.min.js"></script>
    <script src="js/icheck/icheck-active.js"></script>
    <!-- rangle-slider JS
		============================================ -->
    <script src="js/rangle-slider/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="js/rangle-slider/jquery-ui-touch-punch.min.js"></script>
    <script src="js/rangle-slider/rangle-active.js"></script>
    <!-- datapicker JS
		============================================ -->
    <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
    <!-- bootstrap select JS
		============================================ -->
    <script src="js/bootstrap-select/bootstrap-select.js"></script>
    <!--  color-picker JS
		============================================ -->
    <script src="js/color-picker/farbtastic.min.js"></script>
    <script src="js/color-picker/color-picker.js"></script>
    <!--  notification JS
		============================================ -->
    <script src="js/notification/bootstrap-growl.min.js"></script>
    <script src="js/notification/notification-active.js"></script>
    <!--  summernote JS
		============================================ -->
   
    <!-- dropzone JS
		============================================ -->
    <script src="js/dropzone/dropzone.js"></script>
    <!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
    <!--  chosen JS
		============================================ -->
    <script src="js/chosen/chosen.jquery.js"></script>
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
	<script  src="insert_script.js"></script>
	 <script src="js/popup.js"></script>	
	 <script src="currency.js"></script>
	 <script src="loader.js"></script>
	 <script src="loader_operator.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>
	 
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
    <script src="js/summernote/summernote2.js"></script>
	<link href="css/summernote/summernote2.css" rel="stylesheet">
	 <script src="repeater.js" ></script>
</body>

      <script>
    $(document).ready(function(){

        $('#repeater').createRepeater();

        $('#repeater_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"insert_man.php",
                method:"POST",
                data:$(this).serialize(),
                success:function(data)
                {
                    $('#repeater_form')[0].reset();
                    $('#repeater').createRepeater();
                    $('#success_result').html(data);
                }
            })
        });

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
   }
   else
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
});
</script>
</html>