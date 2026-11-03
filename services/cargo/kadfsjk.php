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
include 'top_header.php';
$id= $_GET['u'];
?>
<span id="id_update" style="display:none;"><?php echo $id;?></span>
						<?php
											include'connect.php';
											$select1=mysqli_query($db,"SELECT *  FROM parcel_info WHERE id='$id' ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $s_name=$get13['s_name'];   
		    $s_surname=$get13['s_surname']; 
			$s_no=$get13['s_no']; 
			$r_name=$get13['r_name']; 

            $r_surname=$get13['r_surname'];   
		    $r_no=$get13['r_no']; 
			$account=$get13['account']; 
			$date=$get13['date'];  			
			
			$detail=$get13['detail'];   
		    $status=$get13['status']; 
			$amount=$get13['amount']; 
			$code=$get13['code'];  			
		
		    $payment_status=$get13['payment_status'];   
		    $rec_status=$get13['status']; 
			$question=$get13['question']; 
			$answer=$get13['answer'];  			
			
			$store=$get13['store']; 
			$due=$get13['due'];  
		
	}
}
	
											?>
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
										<i class="notika-icon notika-form"></i>
									</div>
									<div class="breadcomb-ctn">
										<h2>Please Add parcel informations</h2>
										<p>Fill all the forms correctly <span class="bread-ntd">And make sure of every entery that you enter</span></p>
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
    <!-- Form Element area Start-->
    <div class="form-element-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list">
                      
                        <div class="cmp-tb-hd bcs-hd">
                            <h2>Sender Informations</h2>
                            <p>Please fill all the fields correctly </p>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                       
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="firstname"placeholder="Firstname" value ="<?php echo "$s_name";?>"">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                       
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="s_no"placeholder="phone number start with 255" value ="<?php echo "$s_no";?>">
                                    </div>
                                </div>
                            </div>
                        </div>
						                        <div class="cmp-tb-hd bcs-hd">
                            <h2>Receiver Information</h2>
                            <p>Please fill all the fields correctly </p>
                        </div>
						<div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                       
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="r_firstname"placeholder="Firstname" value ="<?php echo "$r_name";?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                               
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="r_surname" placeholder="middlename" value ="<?php echo "$r_surname";?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="r_no"placeholder="phone number start with 255" value ="<?php echo "$r_surname";?>">
                                    </div>
                                </div>
                            </div>
                        </div>
						                    <div class="cmp-tb-hd bcs-hd">
                            <h2>Trip Info</h2>
                            <p>Please fill all the fields correctly </p>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                           
                                    </div>
                                        <div class="bootstrap-select fm-cmp-mg">
                                   <select id="trip"class="selectpicker" data-live-search="true">
											<option><?php echo "$account";?></option>
											<option>Dar es Salaam to Lusaka</option>
											<option>Dar es Salaam to Congo</option>
											<option>Dar es Salaam to Lubumbashi</option>
										
										</select>
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
                                        <input type="text" class="form-control"id="date" placeholder="Expected Receiving Date" value ="<?php echo "$date";?>">
                                    </div>
                                </div>
                                </div>
                            </div>
							
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                                         <div class="bootstrap-select fm-cmp-mg">
                                    <select class="selectpicker"id="status_em">
											<option><?php echo "$status";?></option>
											<option>To be Payed</option>
											<option>paid</option>
											<option style="color:red;" popup-open="popup-2" href="javascript:void(0)"class="check">Due payment</option>
							
										</select>
                                </div>
                                </div>
                            </div>
                        </div>
							                        <div class="cmp-tb-hd bcs-hd">
                            <h2 >Payment and Security</h2>
                            <p>Please fill all the fields correctly </p>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                   
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="amount"placeholder="Transport fees" value ="<?php echo "$amount";?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                     
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="question"placeholder="Quantity" value ="<?php echo "$question";?>">
                                    </div>
                                </div>
                            </div>  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                             
                            </div>
                        </div>
                 
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
                       <div id="summernote"><p>
					    <?php echo base64_decode($detail); ?>
</p></div>
                       
                      
                       
                    </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
						      <div class="nk-int-st">
                                         <div class="summernote-clickable">
                            <button class="btn btn-primary btn-sm hec-button"id="insert">Next
                                </button>
                       
                        </div>
                                    </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
	<div class="popup" popup-name="popup-2">
	<div class="popup-content">
	<h2>PAYMENT AREA</h2>
<form>
  <div class="form-group">
   <label for="exampleInputEmail1">Due payment form</label><br>

        <input type="input" class="form-control" id="due_amount" aria-describedby="emailHelp" placeholder="Luggage Ammount"> 

	<br>
    <input type="input" class="form-control" id="due_amount2" aria-describedby="emailHelp" placeholder="Due Ammount"> <br>

  <button type="button" class="btn btn-primary btn-lg btn-block"id="savebtn">save</button> 

  </div>
  
  
  </form>
	<a class="close-button" popup-close="popup-2" href="javascript:void(0)">x</a>
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
	<script  src="update_script.js"></script>
	 <script src="js/popup.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
</body>
  <script>
  $('#summernote').summernote({
  height: 200,   //set editable area's height
  codemirror: { // codemirror options
    theme: 'monokai'
  }
});
  </script>
</html>