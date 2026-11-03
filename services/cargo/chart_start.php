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
<style>
body{
	background:white;
}

</style>                         		

									<div class="notika-status-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30">
					    <div class="website-traffic-ctn">
                            <h2>Tsh <span class="counter">
							<?php
							include 'connect.php';
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

                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30 dk-res-mg-t-30">
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

                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30 dk-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2>reciepts <span class="counter">
							<?php
									$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where   date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
				?>
							</span></h2>
                            <p>Total Monthly recipts quantity</p>
                        </div>
						</div>
                    </div>
                </div>
            </div>
        </div>	