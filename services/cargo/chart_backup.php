<?php
include'header_login.php';

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard Three | Notika - Notika Admin Template</title>
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
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- jvectormap CSS
		============================================ -->
    <link rel="stylesheet" href="css/jvectormap/jquery-jvectormap-2.0.3.css">
    <!-- notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/notika-custom-icon.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
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

    <!-- Main Menu area End-->
    <!-- Start Status area -->
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
    <div class="notika-status-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2>Tsh <span class="counter"style="display:none;">
							<?php
							date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));
$month =  date('m/Y', strtotime($query_date));


									$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
				echo number_format($amount,2);
			
		}}else{
			echo "0.00";
		}
							?>
							</span></h2>
                            <p>Total Monthly Ammount charges</p>
                        </div>
                        <div class="sparkline-bar-stats1"style="display:none;">
							<?php
						date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
			
			
		}}else{
		}
		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
									$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   date_insert ='$date_insert' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
				echo ''. $amount.',';
			
		}}else{
			echo "Nothing";
		}	


		
	}
}else{
	
}

						?>
						</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2>Tsh <span class="counter">
							<?php
							date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));
$month =  date('m/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				echo number_format($paid,2);
			
		}}else{
			echo "0.00";
		}
							?>
							</span></h2>
                            <p>Total monthly cash paid amount</p>
                        </div>
                        <div class="sparkline-bar-stats2">
						<?php
						//////////////////////////
						///////////////////////////
						///////////////paid///////////
						///////////////////////////
						date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));
$month =  date('m/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid_see=$data['paid'];
			
			
		}}else{
			
		}
		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
									$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where   date_insert ='$date_insert' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid_see=$data['paid'];
				echo ''. $paid_see.',';
			
		}}else{
			echo "Nothing";
		}	


		
	}
}else{
	
}

						?>
						</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30 dk-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2>Tsh <span class="counter"style="display:none;">
								<?php
							date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));
$month =  date('m/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be=$data['to_be'];
				echo number_format($to_be,2);
			
		}}else{
			echo "0.00";
		}
							?>
							</span></h2>
                            <p>Total Monthly To-be-paid Ammount</p>
                        </div>
                        <div class="sparkline-bar-stats3"style="display:none;">
							<?php
						//////////////////////////
						///////////////////////////
						///////////////To be///////////
						///////////////////////////
						date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_see=$data['to_be'];
			
			
		}}else{
			
		}
		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
									$sql = $db->query("SELECT SUM(to_be) AS to_be FROM parcel_info  where   date_insert ='$date_insert' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_see=$data['to_be'];
				echo ''. $to_be_see.',';
			
		}}else{
			echo "Nothing";
		}	


		
	}
}else{
	
}

						?>
						</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30 dk-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2>reciepts <span class="counter"style="display:none;"><?php
									$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where   date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
				?>
							</span></h2>
                            <p>Total Monthly recipts quantity</p>
                        </div>
                        <div class="sparkline-bar-stats4">
						<?php
						//////////////////////////
						///////////////////////////
						///////////////To be///////////
						///////////////////////////
						date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_see=$data['to_be'];
			
			
		}}else{
			
		}
		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
										$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where  date_insert ='$date_insert'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo ''. $select_num222.',';
}

		
	}
}else{
	
}

						?>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Status area-->
    <!-- Start Sale Statistic area-->
    <div class="sale-statistic-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                    <div class="sale-statistic-inner notika-shadow mg-tb-30">
                        <div class="curved-inner-pro">
                            <div class="curved-ctn">
                                <h2>CHARGES AND PAYMENT STATUS MONTHLY TOTAL AMMOUNT</h2>
                                <p>This list consist of due payment to-pay and paid, for the whole month</p>
                            </div>
                        </div>
                        <div class="flot-chart bar-three bar-hm-three">
						   <div id ="load_monthly_chart"></div>
						
						</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                    <div class="statistic-right-area notika-shadow mg-tb-30 sm-res-mg-t-0">
                        <div class="past-day-statis">
                            <h2>Reciept charge For The Past 30 Days</h2>
                            <p>Total number of all reciepts with in a month.</p>
                        </div>
						<div class="dash-widget-visits"></div>
                        <div class="past-statistic-an">
                            <div class="past-statistic-ctn">
                                <h3><span class="counter"style="display:none;">
								<?php
								//////////////////////////////////////
								/////////////////////////////////////charges//////////////////////
								//////////////////////////////////////////////////
								
									$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where   status='paid'and date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
				?>
					
								</span></h3>
                                <p>Cash Paid Reciepts</p>
                            </div>
                            <div class="past-statistic-graph">
                                <div class="sparkline-bar-stats4"style="display:none;">
									<?php
						//////////////////////////
						///////////////////////////
						///////////////To be///////////
						///////////////////////////
						date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_see=$data['to_be'];
			
			
		}}else{
			
		}
		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info where status='Payed' GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
										$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where  date_insert ='$date_insert'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo ''. $select_num222.',';
}

		
	}
}else{
	
}

						?>
								</div>
                            </div>
                        </div>
                        <div class="past-statistic-an">
                            <div class="past-statistic-ctn">
                                <h3><span class="counter"style="display:none;">
													<?php
								//////////////////////////////////////
								/////////////////////////////////////charges to pay//////////////////////
								//////////////////////////////////////////////////
								
									$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where   status='To be Payed'and date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
				?>
					
								</span></h3>
                                <p>To be Paid Reciepts</p>
                            </div>
                            <div class="past-statistic-graph">
                                                         <div class="sparkline-bar-stats4">
									<?php
						//////////////////////////
						///////////////////////////
						///////////////To be///////////
						///////////////////////////
						date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_see=$data['to_be'];
			
			
		}}else{
			
		}
		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  where status='To be Payed' GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
										$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where  date_insert ='$date_insert'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo ''. $select_num222.',';
}

		
	}
}else{
	
}

						?>
								</div>
                            </div>
                        </div>
                        <div class="past-statistic-an">
                            <div class="past-statistic-ctn">
                                <h3><span class="counter"style="display:none;">
																<?php
								//////////////////////////////////////
								/////////////////////////////////////charges to pay//////////////////////
								//////////////////////////////////////////////////
								
									$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where   status='Due payment'and date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
				?>
					
								</span></h3>
                                <p>Due paid Reciepts</p>
                            </div>
                            <div class="past-statistic-graph">
                                                           <div class="sparkline-bar-stats4">
									<?php
						//////////////////////////
						///////////////////////////
						///////////////To be///////////
						///////////////////////////
						date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



									$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_see=$data['to_be'];
			
			
		}}else{
			
		}
		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info where status='Due payment' GROUP BY (date_insert) ORDER BY date_insert desc limit 10");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
		
		
										$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where  date_insert ='$date_insert'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo ''. $select_num222.',';
}

		
	}
}else{
	
}

						?>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sale Statistic area-->
    <!-- Start Email Statistic area-->
    <div class="notika-email-post-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="email-statis-inner notika-shadow">
                        <div class="email-ctn-round">
                            <div class="email-rdn-hd">
								<h2>Manifest Statics <sup style="color:#db2104;"> tsh 11,000,000</sup></h2>
						
									
							</div>
                            <div class="email-statis-wrap">
                                <div class="email-round-nock"style="display:none;">
                                    <input type="text" class="knob" value="0" data-rel="<?php
									include 'connect.php';
									$select2=mysqli_query($db,"SELECT *  FROM vehicle_name  order by id desc limit 1");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date =$get12['date']; 
			$name=$get12['Name'];
			

	
								$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date = '$date' and bus='$name' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
			$percentage = $total_amount_t;
$totalWidth = 11000000;

$new_width = ($percentage / $totalWidth) * 100 ;
			echo $new_width;
		}}else{
			echo "0.00";
		}

		
	}
}
?>" data-linecap="round" data-width="130" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true">
                                </div>
                                <div class="email-ctn-nock"style="display:none;">
                                    <p>Last Manifest <br> 14/1/2920 . (<?php echo $name;?>) . Tsh <?php  echo number_format($total_amount_t,2);?> </p>
                                </div>
                            </div>
                            <div class="email-round-gp">
                                <div class="email-round-pro">
                                    <div class="email-signle-gp"style="display:none;">
                                        <input type="text" class="knob" value="0" data-rel="<?php
									include 'connect.php';
									$select2=mysqli_query($db,"SELECT *  FROM vehicle_name  order by id desc limit 2,1");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date =$get12['date']; 
			$name=$get12['Name'];
			

	
								$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date = '$date' and bus='$name' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
			$percentage = $total_amount_t;
$totalWidth = 11000000;

$new_width = ($percentage / $totalWidth) * 100 ;
			echo $new_width;
		}}else{
			echo "0.00";
		}

		
	}
}
?>" data-linecap="round" data-width="90" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true" disabled>
                                    </div>
                                    <div class="email-ctn-nock">
                                        <p><?php echo number_format($total_amount_t,2);?> (<?php echo $name;?>)</p>
                                    </div>
                                </div>
                                <div class="email-round-pro">
                                    <div class="email-signle-gp">
                                        <input type="text" class="knob" value="0" data-rel="<?php
									include 'connect.php';
									$select2=mysqli_query($db,"SELECT *  FROM vehicle_name  order by id desc limit 3,1");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date =$get12['date']; 
			$name=$get12['Name'];
			

	
								$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date = '$date' and bus='$name' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
			$percentage = $total_amount_t;
$totalWidth = 11000000;

$new_width = ($percentage / $totalWidth) * 100 ;
			echo $new_width;
		}}else{
			echo "0.00";
		}

		
	}
}
?>" data-linecap="round" data-width="90" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true" disabled>
                                    </div>
                                    <div class="email-ctn-nock">
                                       <p><?php echo number_format($total_amount_t,2);?> (<?php echo $name;?>)</p>
                                    </div>
                                </div>
                                <div class="email-round-pro sm-res-ds-n lg-res-mg-bl">
                                    <div class="email-signle-gp">
                                        <input type="text" class="knob" value="0" data-rel="<?php
									include 'connect.php';
									$select2=mysqli_query($db,"SELECT *  FROM vehicle_name  order by id desc limit 4,1");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date =$get12['date']; 
			$name=$get12['Name'];
			

	
								$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date = '$date' and bus='$name' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
			$percentage = $total_amount_t;
$totalWidth = 11000000;

$new_width = ($percentage / $totalWidth) * 100 ;
			echo $new_width;
		}}else{
			echo "0.00";
		}

		
	}
}
?>" data-linecap="round" data-width="90" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true" disabled>
                                    </div>
                                    <div class="email-ctn-nock">
                                        <p><?php echo number_format($total_amount_t,2);?> (<?php echo $name;?>)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"style="display:none;">
                    <div class="recent-post-wrapper notika-shadow sm-res-mg-t-30 tb-res-ds-n dk-res-ds">
                        <div class="recent-post-ctn">
                            <div class="recent-post-title">
                                <h2>5 Last Manifest</h2>
                            </div>
                        </div>
						<?php
						$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name limit 6 ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
			$date=$get12['date'];
			$name=$get12['Name'];
			$paid=$get12['paid'];
			$to_pay=$get12['to_pay'];
			$due=$get12['due'];

	

		
	}
}else{
	echo "none";
}
						?>
                        <div class="recent-post-items">
                            <div class="recent-post-signle rct-pt-mg-wp"style="display:none;">
							                     		<?php
						$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name order by id desc limit 5 ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
			$date=$get12['date'];
			$name=$get12['Name'];
			$paid=$get12['paid'];
			$to_pay=$get12['to_pay'];
			$due=$get12['due'];

	echo '
   <div class="recent-post-signle">
                                <a href="vehicle_report_new.php?date='.$date.'&date2='.$date.'&date3='.$name.'">
                                    <div class="recent-post-flex rct-pt-mg">
                                        <div class="recent-post-img"style="width:42px;height:42px">
                                          <img src="img/fast.png" alt="" />
                                        </div>
                                        <div class="recent-post-it-ctn">
                                            <h2>'.$name.'</h2>
                                            <p>Date '.$date.' Total  '.number_format($due,2).' </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
	';

		
	}
}else{
	echo "none";
}
						?>
                            </div>
                        
                         
                          
                        
                            <div class="recent-post-signle">
                                <a href="vehicle_view.php">
                                    <div class="recent-post-flex rc-ps-vw">
                                        <div class="recent-post-line rct-pt-mg">
                                            <p>View All</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="recent-items-wp notika-shadow sm-res-mg-t-30">
                        <div class="rc-it-ltd">
                            <div class="recent-items-ctn">
                                <div class="recent-items-title">
                                    <h2>Last Charge</h2>
                                </div>
                            </div>
                            <div class="recent-items-inn">
                                <table class="table table-inner table-vmiddle">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>To paid</th>
                                            <th style="width: 60px">Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
														$select2=mysqli_query($db,"SELECT *  FROM  parcel_info order by id desc limit 8 ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
			$code22=$get12['code'];
			$amount22=$get12['to_be'];
			$paid=$get12['paid'];
			echo '
			  <tr>
                                            <td class="f-500 c-cyan">'.$code22.'</td>
                                            <td>'.number_format($amount22,2).'</td>
                                            <td class="f-500 c-cyan"> '.number_format($paid,2).'</td>
                                        </tr>
			';
}}		?>
                                     
                                      
                                       
                                    </tbody>
                                </table>
								
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
	<div class="breadcomb-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="breadcomb-list">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="breadcomb-wp">
									
									<div class="breadcomb-ctn">
<div id="load_yearly_chart">loading yearly chart please wait...<br>
<img src="images/spinner.gif">
</div>
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
    <!-- End Email Statistic area-->
    <!-- Start Realtime sts area-->

    <!-- End Realtime sts area-->
    <!-- Start Footer area-->

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
    <!-- jvectormap JS
		============================================ -->
    <script src="js/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="js/jvectormap/jvectormap-active.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="js/flot/jquery.flot.js"></script>
    <script src="js/flot/jquery.flot.resize.js"></script>
    <script src="js/flot/jquery.flot.pie.js"></script>
    <script src="js/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/flot/jquery.flot.orderBars.js"></script>
    <script src="js/flot/curvedLines.js"></script>
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
	<script src="js/chat/moment.min.js"></script>
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
		 <script src="loader.js"></script>
	 <script src="update_loader.js"></script>  
	 <script src="update_loader_manifest_new.js"></script> 
	 <script src="update_loader_manifest.js"></script>
	  <script src="searching.js"></script>
	  <script src="load_delete.js"></script> 
	  <script src="load_update.js"></script>

</body>
<script>

$(document).ready(function(){
$("#load_yearly_chart").load("load_yearly_chart.php");
});
</script>
</html>