<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Luggages</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
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
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
	<style>
	input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
	</style>
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
 
    <!-- Main Menu area End-->
	<!-- Breadcomb area Start-->
	<?php
	include 'nav.php';
	?>
	<div class="breadcomb-area">
		<div class="container">
	
		</div>
	</div>
	<!-- Breadcomb area End-->
    <!-- Form Element area Start-->
    <div class="form-element-area">
        <div class="container">
        
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list mg-t-30">
                        <div class="cmp-tb-hd">
                            <h2>Insert Details</h2>
                            <p>note (<code>Number must start with counrty Code (+255)</code>) </p>
                        </div>
						        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" id="fullname"class="form-control" placeholder="Sender's Full Name">
                                    </div>
                                </div>
                            </div>    
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" id="rfullname"class="form-control" placeholder="Reciver'sFull Name">
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" id="number"class="form-control" placeholder="Contact Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
                                    <div class="nk-int-st">
                                    <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                  
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
                                        <input type="text" id="date"class="form-control" placeholder="date">
                                    </div>
                                </div>
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
                                    <select id="country"class="selectpicker" data-live-search="true" onchange="FetchState(this.value)"  required>
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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int int-bg-it form-elet-mg">
                                    <div class="form-ic-cmp">
                                       
                                    </div>
                                    <div class="nk-int-st">
                                                          <div id="summernote"><p>Detail</p></div>

                                    </div>
                                </div>
                            </div>
							  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                    
                                    </div>
									 <label>Simple data input format</label>
                                    <div class="nk-int-st">
                                        <input type="number" id="amount"class="form-control input-sm" Placeholder="Amount">
                                    </div>
                                </div>
                            </div> 
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int"style="padding-left:auto;padding-right:auto;">
                                <button id="book"class="btn btn-primary btn-sm hec-button">Book Luggage
                                </button>
                                </div>
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

    <!-- Summernote area Start-->

    <!-- Summernote area End-->
    <!-- Dropzone area Start-->

    <!-- Dropzone area End-->
    <!-- Start Footer area-->
          
<?php
include 'footer.php';
?>
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
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
</body>
 
  <!DOCTYPE html>
<html lang="en">
<head>
  


  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
</head>
<body>

  <script>
    $(document).ready(function() {
        $('#summernote').summernote();
		var textareaValue = $('#summernote').summernote('code');
		$('#book').click(function(){
		var date = $('#date').val();
		
		});
    });
  </script>
  <script>
  $('#summernote').summernote({
  height: 200,   //set editable area's height
  codemirror: { // codemirror options
    theme: 'monokai'
  }
});
  </script>
<script type="text/javascript">
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
      url: 'ajaxdata.php',
      data : { country_id : id,date:date},
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
      url: 'ajaxdata.php',
      data : { state_id : id},
      success : function(data){
         $('#city').html(data);
		 $('.loading').text('choose Address');
      }

    })
  }

  $('#book').click(function(){
	  var fullname = $("#fullname").val();
	  var trip = $( "#country option:selected" ).text();
     var trip_assign = $( "#state option:selected" ).text();
	var textareaValue = $($("#summernote").summernote("code")).text();
	 var amount = $('#amount').val();
	 var number = $('#number').val();
	var date = $('#date').val();
	var receiver = $('#rfullname').val();
	 if(fullname == ""){
		alert("Fullname is Empty"); 
		control.disabled="disabled";
	 }else if(receiver==""){
		alert("Reciver's name is Empty"); 
		control.disabled="disabled";
	 }else if (number == ""){
		 alert("number is Empty");
		 control.disabled="disabled";
	 } else if (date==""){
		 alert("Date is Empty");
	 } else if(trip == "Choose Trip"){
		 alert("Trip is Empty");
		 control.disabled="disabled";
	 } else if (trip_assign == "Choose Destination"){
		alert("Destinatin is Empty"); 
		control.disabled="disabled";
	 } else if (trip_assign == "Destination not found"){
		alert("Destination is Empyt"); 
		control.disabled="disabled";
	 }else if (textareaValue == "Detail"){
		 alert("Detail is empty");
		 control.disabled="disabled";
	 } else if (amount==""){
		 alert("Ammout is Empty");
		 control.disabled="disabled";
	 }
	  var d = new Date();
     
    var date2 = d.getUTCDate();
    var month = d.getUTCMonth() + 1; // Since getUTCMonth() returns month from 0-11 not 1-12
    var year = d.getUTCFullYear();
     
    var dateStr = month + "/" + date2 + "/" + year;
    $('#book').text("loading...");
$.ajax({
				url:"insert_luggage.php",
				method:"POST",
				data:{fullname:fullname,receiver:receiver,trip:trip,trip_assign:trip_assign,textareaValue:textareaValue,amount:amount,number:number,date:date,dateStr:dateStr},
				
				success:function(response){
$('#book').text("Book Luggage");
	}
			
			});
	
  });
</script>
</body>
</html>
</html>