<?php
include 'connect.php';
?>
<?php
	$id= $_GET['u'];


?>
<!doctype html>

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
	$id= $_GET['u'];
$select2=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id' ");
$select_num2=mysqli_num_rows($select2);


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
		  $status=$get13['status'];
		  $date=$get13['date_insert'];
		  $store=$get13['store']; 
		  $quantity=$get13['question'];
		  $scanned=$get13['scanned'];
		  $uploader=$get13['uploader'];
		  $amount=$get13['amount'];
		  $to_be=$get13['to_be'];  
		  $paid=$get13['paid'];
		
	}
}
	?>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- 404 Page area Start-->
    <div class="error-page-area">
        <div class="error-page-wrap">
          <a href ="employee_update.php">  <i class="notika-icon notika-close"></i></a>
            <h2>Transported Luggages<span style="color:#00C292"> Detail</span></h2>
<table class="table">

  <tbody>
    <tr>
      <th scope="row">.</th>
      <td>Sender Name</td>
      <td><?php echo "$s_name";?></td>
    </tr>
    <tr>
      <th scope="row">.</th>
      <td>Sender's No</td>
      <td><?php echo $s_no;?></td>
    </tr>
    <tr>
      <th scope="row">.</th>
      <td>Reciver Name</td>
      <td><?php echo "$r_name ";?></td>
    </tr> 
	<tr>
      <th scope="row">.</th>
      <td>Reciver no</td>
      <td><?php echo "$r_no";?></td>
    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Trip</td>
      <td><?php echo  $trip_name ;?></td>
    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Code</td>
      <td ><span id="code"><?php echo $code?></span></td>
    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Detail</td>
      <td><?php echo base64_decode($detail)?></td>
    </tr>	
	<tr>
      <th scope="row">.</th>
      <td>Quantity</td>
      <td><?php echo "$scanned /$quantity"?></td>
    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Status</td>
      <td><?php echo $status?></td>
    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Store</td>
      <td><?php echo $store?></td>
    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Date</td>
      <td><?php echo $date?></td>
    </tr>		
	<tr>
      <th scope="row">.</th>
      <td>Charged by </td>
      <td><?php echo $uploader?></td>
    </tr><tr>
      <th scope="row">.</th>
      <td>Total Ammount </td>
      <td><?php echo number_format($amount,2)?></td>
    </tr><tr>
      <th scope="row">.</th>
      <td>Total To pay </td>
      <td><?php echo number_format($to_be,2)?></td>
    </tr>	<tr>
      <th scope="row">.</th>
      <td>Total paid </td>
      <td><?php echo number_format($paid,2)?></td>
    </tr>	

  </tbody>
  
</table>             <?php
  if($store == "store"){
	  echo '
	   <button id="send" class="btn">SEND</button>
	  ';
  }else{
	  
  }
  ?>
      <?php
  $select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 ==0){

}else{
	
?>

         <h2>Luggage Manifest <span style="color:#00C292"> Detail</span></h2>    
		 <table class="table">

  <tbody>
 
	    <?php
  $select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $trip_date=$get13['trip_date']; 
 $bus=$get13['bus']; 
	echo '
	   <tr>
      <th scope="row">.</th>
	 <td>Bus</td>
      <td>'.$bus.'</td>
	     </tr>
	';	
	}
}
  ?>
  
 	    <?php
  $select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $trip_date=$get13['trip_date']; 
 $bus=$get13['bus']; 
	echo '
	   <tr>
      <th scope="row">.</th>
	 <td>Trip Date</td>
      <td>'.$trip_date.'</td>
	     </tr>
	';	
	}
}
  ?>     
  <?php
  $select1=mysqli_query($db,"SELECT *  FROM vehicle_name where name = '$bus' and date='$trip_date'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $Driver=$get13['Driver']; 
 $Conductor=$get13['Conductor']; 
	echo '
	   <tr>
      <th scope="row">.</th>
	 <td>Driver</td>
      <td>'.$Driver.'</td>
	     </tr>
	';
	}
}
  ?>    
   <?php
  $select1=mysqli_query($db,"SELECT *  FROM vehicle_name where Name = '$bus' and date='$trip_date'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $Driver=$get13['Driver']; 
 $Conductor=$get13['Conductor']; 
	echo '
	   <tr>
      <th scope="row"><img src="imgs/user.png"></th>
	 <td>Conductor</td>
      <td>'.$Conductor.'</td>
	     </tr>
	';
	}
}else{

}
  ?>    
 
 



<?php
}
  ?>

  </tbody>
  
</table> 
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
		<script  src="save_manualy.js"></script>

</body>

</html>