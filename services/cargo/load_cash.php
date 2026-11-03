                       

					   <div class="past-day-statis">
                           
                        </div>
						<div class="dash-widget-visits"></div>
                       <div class="card" >
                            <div class="past-statistic-ctn"style="margin:5px;">
							<h5>Cash Reciepts</h5>
                               <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><span class="counter">
			<?php
			include 'connect.php';
							$route = $_GET['route'];
						$route = base64_decode($route);						date_default_timezone_set('Africa/Dar_es_Salaam');

$month = date('m-Y');




$select1=mysqli_query($db,"SELECT *  FROM  monthly_charges where date_insert = '$month'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    

			$no_paid =$get13['no_paid'];
		
		
			echo $no_paid;
}}else{
	echo "0";
}

				?>
								</span></h4>
                                <p>Total monthly Cash Paid Reciepts</p>
                            </div>
                            <div class="past-statistic-graph">
                                <div class="sparkline-bar-stats4">

								</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="past-statistic-ctn"style="margin:5px;">
														<h5>To be paid Reciepts</h5>

                                <h3><span class="counter">
			<?php
			include 'connect.php';
							$route = $_GET['route'];
						$route = base64_decode($route);						date_default_timezone_set('Africa/Dar_es_Salaam');

$month = date('m-Y');




$select1=mysqli_query($db,"SELECT *  FROM  monthly_charges where date_insert = '$month'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    

			$no_to_be =$get13['no_to_be'];
		
		
			echo $no_to_be;
}}else{
	echo "0";
}

				?>
								</span></h3>
                                <p>Total monthly to be paid receipts</p>
                            </div>
                            <div class="past-statistic-graph">
                                                         <div class="sparkline-bar-stats4">
	
								</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="past-statistic-ctn"style="margin:5px;">
														<h5>Total Due Paid Reciepts</h5>

                                <h3><span class="counter">
	<?php
			include 'connect.php';
							$route = $_GET['route'];
						$route = base64_decode($route);						date_default_timezone_set('Africa/Dar_es_Salaam');

$month = date('m-Y');




$select1=mysqli_query($db,"SELECT *  FROM  monthly_charges where date_insert = '$month'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    

			$no_due =$get13['no_due'];
		
		
			echo $no_due;
}}else{
	echo "0";
}

				?>
								</span></h3>
                                <p>Due paid Reciepts</p>
                            </div>
                            <div class="past-statistic-graph">
                                                           <div class="sparkline-bar-stats4">
			
								</div>
                            </div>
                        </div>
                  
					 <script src="js/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="js/jvectormap/jvectormap-active.js"></script>
					 <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>