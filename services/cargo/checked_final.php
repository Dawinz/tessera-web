
<!doctype html>
<?php
include 'connect.php';
?>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>404 Page | Notika - Notika Admin Template</title>
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
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
    <!-- Notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/notika-custom-icon.css">
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
				<?php
		$id =$_GET['code'];
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id' or id='$id'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $s_name=$get13['s_name']; 
			$s_surname=$get13['s_surname'];
			$s_no=$get13['s_no'];
			$r_no=$get13['r_no']; 
			$r_name=$get13['r_name'];   
            $r_surname=$get13['r_surname'];   
		 $question=$get13['question'];
		  $answer=$get13['answer'];
		  $detail=$get13['detail'];
		  $code=$get13['code'];
		  		  $cost=$get13['amount'];  
$due=$get13['due'];
		
	}
}
	?>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- 404 Page area Start-->
	<span id="due"style="display:none;"><?php
	echo $due;
	?></span>
    <div class="error-page-area">
        <div class="error-page-wrap">
            <i class="notika-icon notika-close"></i>
            <h2>Transported Luggages<span style="color:#00C292"> Detail</span></h2>
<table class="table">

  <tbody>
    <tr>
      <th scope="row"><img src="imgs/user.png"></th>
      <td>Sender Name</td>
      <td><?php echo "$s_name $s_surname";?></td>
    </tr>
    <tr>
      <th scope="row"><img src="imgs/phone.png"></th>
      <td>Sender's No</td>
      <td><?php echo $s_no;?></td>
    </tr>
    <tr>
      <th scope="row"><img src="imgs/user.png"></th>
      <td>Reciver Name</td>
      <td><?php echo "$r_name $r_surname";?></td>
    </tr> 
	<tr>
      <th scope="row"><img src="imgs/phone.png"></th>
      <td>Reciver no</td>
      <td><?php echo "$r_no";?></td>
    </tr>
	<tr>
      <th scope="row"><img src ="imgs/bus-stop.png"></th>
      <td>Quantity</td>
      <td><?php echo  $question ;?></td>
    </tr>
	
	<tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Code</td>
      <td id="code"><?php echo $code?></td>
    </tr>
	<tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Detail</td>
      <td><?php echo base64_decode($detail)?></td>
    </tr>
	<tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Cost</td>
      <td id="code"><?php echo number_format($cost,2);?></td>
    </tr><tr>
      <th scope="row"><img src ="imgs/loupe.png"></th>
      <td>Due</td>
      <td id="code"><?php echo  number_format($due ,2);?></td>
    </tr>
	<tr>
<td></td><td>
   <div class="form-example-int mg-t-15">
                            <div class="fm-checkbox"id="checked_show"style="display:none;">
                                <label><input type="checkbox" class="i-checks"> <i></i> Check to settle payment</label>
                            </div>
</td><td></td>


</tr>
  </tbody>
</table>           <button id="send" class="btn">SEND</button>
            
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
    <!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
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
			<script  src="save_final.js"></script>
				<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>

</html>