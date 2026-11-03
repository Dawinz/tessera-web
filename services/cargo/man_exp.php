<?php
require_once __DIR__ . '/includes/sr_env.php';
//index.php
$connect = sr_mysqli('sr');
$country = '';
$query = "SELECT date FROM vehicle_name GROUP BY date ORDER BY id Desc";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $country .= '<option value="'.$row["date"].'">'.$row["date"].'</option>';
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
	
		============================================ -->
		 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
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
	<div class="breadcomb-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					
					        <div class="panel panel-default"><br>
           	<div class="breadcomb-wp">
									<div class="breadcomb-icon">
<button data-toggle="tooltip" data-placement="left"  popup-open="popup-2" href="javascript:void(0)"class="btn" id="popup"style="background:none;"><i class="notika-icon notika-up-arrow"></i></button>

										
									</div>
									<div class="breadcomb-ctn">
										<h2>Manifest  Expenses </h2>
										<p>Enter vehicle expenses enter correctly  <span class="bread-ntd"></span></p>
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
					
					
   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                           
                                    </div>
                                        <div class="bootstrap-select fm-cmp-mg">
                                   <select id="status"class="selectpicker" data-live-search="true">
											<option>Choose Category</option>
																			<?php
include 'connect.php';																
																$select1=mysqli_query($db,"SELECT *  FROM expenses_list WHERE status='expenses'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $name=$get13['name']; 
			echo '<option value="vehicle">'.$name.'</option>';
}}}
											?>
										
										</select>
                                </div>
                                </div>
                            </div>
                     
                    </form>

</form>
                                        </ul>
                                    </div>

                </div>
            </div>
				
	
				</div>
				
			</div>
			
		</div>
	<!-- Breadcomb area End-->
    <!-- Form Element area Start-->
    <div class="form-element-area">
        <div class="container">

    
        
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
                       <div id="summernote"><p></p></div>
                       
                      
                       
                    </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
						      <div class="nk-int-st">
                                         <div class="summernote-clickable">
<button type="button" class="btn btn-primary btn-lg btn-block"id="insert">Insert</button>
                       
                        </div>
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
			<div class="popup" popup-name="popup-2">
	<div class="popup-content">
	<h2>Enter Expenses Type</h2>
<form>
  <div class="form-group">
   <label for="exampleInputEmail1">Enter Expenses Name</label><br>

                <input type="input" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Expenses Name" value="" > 
	<br>
	<label for="exampleInputEmail1">dedicate From</label><br>
                           <select name="category" id="category" class="form-control action" data-live-search="true">
    <option value="">choose</option>
	<option value="">Payed</option>   
	<option value="">To pay</option>
    
   </select>
 <br>
 
  <button type="button" class="btn btn-primary btn-lg btn-block"id="savebtn2">save</button> <span class="inserting"></span> 

  </div>
 
  
  </form>
	<a class="close-button" popup-close="popup-2" href="javascript:void(0)">x</a>
	</div>
	
</div>
	
	
	
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
		 <script src="js/popup2.js"></script>	

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
	<script  src="insert_man_insert.js"></script>
	<script  src="insert_item.js"></script>
	 		 <script src="loader.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>
			 <script src="searching.js"></script>
			 	<script src="js/sweetalert.js"></script>
				 <script src="currency.js"></script>

	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
</body>
  <script>
  $('#summernote').summernote({
  height: 200,  width: 1000,   //set editable area's height
  codemirror: { // codemirror options
    theme: 'monokai'
  }
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
    url:"fetch_dependent_man.php",
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