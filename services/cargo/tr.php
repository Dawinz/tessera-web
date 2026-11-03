<?php
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
$vehicle= $_GET['vehicle'];

?>
<!doctype html>

<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
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
</head>
<style>
body{

	
}
/* Chrome, Safari, Edge, Opera */
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
<body>
				<?php
	$id =$_GET['code'];
	$select2=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id' and store='out' and scanned='0'");
$select_num2=mysqli_num_rows($select2);

if($select_num2 !=0){

}
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $s_name=$get13['s_name']; 
			$s_surname=$get13['s_surname'];
			$s_no=$get13['s_no'];
			$r_no=$get13['r_no']; 
			$r_name=$get13['r_name'];   
            $r_surname=$get13['r_surname'];   
		 $trip_name=$get13['account'];
		  $detail=$get13['detail'];
		  $code=$get13['code']; 
 $due=$get13['due'];
		  $scanned=$get13['scanned'];  
		  $cost=$get13['amount'];  
		  $s_q= $get13['scanned'];
		  $quantity= $get13['question'];
		  $amount_remain= $get13['amount_remain'];
		  $status=$get13['status']; 
			  $paid=$get13['paid']; 
			  	  $to_be=$get13['to_be']; 
		
	}
}
	?>
	<?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$id'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    
		  $addcode=$get13['code'];  
		  $to_be_remain =$get13['to_be'];


	}
}
?>
	<span id="due"style="display:none;"><?php
	echo $due;
	?></span>
	<span id="date_insert"style="display:none;"><?php echo $_GET['date']; ?></span>
	<?php 
	
if(($quantity > $scanned) and ($scanned != 0)){
		echo '
<div id="equal"style="display:none;">equal</div>
	';
}else{

}
?>
<span id="quantity"style="display:none;"><?php echo $quantity;?></span>
<span id="scanned" style="display:none;"><?phpecho $scanned;?></span>
<span id="scan" style="display:none;"><?php echo $s_q;?></span>
<span id ="time_remain" style="display:none;"><?php echo $time_remain;?></span>

    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- 404 Page area Start-->
    <div class="error-page-area">
        <div class="error-page-wrap">
       <a href="employee_update.php"> <i class="fa fa-home"></i></a>
            <h2>Transported Luggages<span style="color:#00C292"> Detail</span></h2><br>
			<h3 style="display:none;"><br> 
			<br>LEFT amount : <span id="amount_remain_new"><?php 
			if($status=="To be Payed"){
	  if($amount_remain==0){
		  echo $to_be;
	  }else{
echo $amount_remain;
	  }
	 
			}else if($status=="Due payment"){
				if($amount_remain==0){
 echo $to_be;
 }else{
echo $amount_remain;
}
			} else if($status=="Payed"){
				
			}

			?></span>
			</h3>
<table class="table">
<div id="status_check"><?php echo $status;?></div>
  <tbody>


  
	
	<tr>
      <th scope="row"><img src ="imgs/bus-stop.png"></th>
      <td>S'  Name</td>
      <td><?php echo  $s_name ;?></td>
    </tr><tr>
      <th scope="row"><img src ="imgs/bus-stop.png"></th>
      <td>R'  Name</td>
      <td><?php echo  $r_name ;?></td>
    </tr><tr>
      <th scope="row"><img src ="imgs/bus-stop.png"></th>
      <td>Trip</td>
      <td><?php echo  $trip_name ;?></td>
    </tr>
	<tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Code</td>
      <td id="code"><?php echo $code?></td>
    </tr>	<tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Ammount</td>
      <td id="code"><?php echo number_format($cost ,2)?></td>
    </tr><tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>To Be Payed</td>
      <td id="code"><?php 
	  if($amount_remain==0){
		   echo number_format($to_be ,2);
	  }else{
		  if($status=="Due payment"){
			  		 echo number_format($amount_remain ,2)  ;
		  }else{
			  		 echo number_format($amount_remain ,2)  ;
		  }

	  }
	 

	  ?></td>
    </tr><tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Paid</td>
      <td id="code"><?php echo number_format($paid ,2)?></td>
    </tr><tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Amount Left</td>
      <td id="code">
	  <?php 
			if($status=="To be Payed"){
				if($amount_remain==0){
	echo $to_be;
}else{
	echo $cost - $to_be;
}
			}else if($status=="Due payment"){
				if($amount_remain==0){
	echo $s_surname;
}else{
	echo $s_surname - $to_be;
}
			} else if($status=="Payed"){
				
			}

			?>
	  </td>
    </tr>
	<tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Detail</td>
      <td><?php echo base64_decode($detail)?></td>
    </tr>	

<tr>
<td></td><td>
   <div class="form-example-int mg-t-15">
                           
</td><td></td>


</tr>
  </tbody>
  

</table>           
<?php
if($quantity != 0){
?>

            <h2><span style="color:red"> <?php echo $scanned;?></span>, Luggages are in the store</h2>

             <div class="row">
               
                            
                            
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                     
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="number" class="form-control" id="number"
										value="<?php if($quantity=="1"){echo $quantity;}?>"
										placeholder="QUANTITY" >
                                    </div>
                                </div>   <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                     
                                    </div>
									
									<?php
									if($status=="paid"){
									}else{
									if($quantity == 1){
									?>
									      <div class="nk-int-st" style="d">
                                        <input type="number" style="display:none;"class="form-control" id="topaid"value="<?php
										if($status=="paid"){
											echo "$paid";
										}else if ($status=="To be Payed") {
											echo "$to_be";
										} else if($status == "Due payment"){
											echo "$s_surname";
										}
										?>
										">
                                    </div>
									<?php 	
									}else{
									?>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="topaid" placeholder="Manifest Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                    </div>
									<?php
									}
									}
									?>
                                </div>
                          
                        </div>
						    <div id="choosen"style="display:none;"><?php echo $vehicle;?></div>
						<?php
}else{
	
}
?>
	<br>					
<button id="send" class="btn">Post</button>
            
        </div>
    </div>
    <!-- 404 Page area End-->
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
    <script src="js/summernote/summernote-updated.min.js"></script>
    <script src="js/summernote/summernote-active.js"></script>
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
	
		<script  src="save.js"></script>
		<script  src="log_out.js"></script>
<script src="js/sweetalert.js"></script>
	 <script src="currency.js"></script>


</body>

</html>