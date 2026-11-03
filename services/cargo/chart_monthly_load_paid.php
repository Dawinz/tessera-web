 
                
                    
                        <div class="sparkline-bar-stats2">
							<?php
							include 'connect.php';
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
					  

    			    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->

    <!-- counterup JS
		============================================ -->
    <script src="js/counterup/jquery.counterup.min.js"></script>
    <script src="js/counterup/waypoints.min.js"></script>
    <script src="js/counterup/counterup-active.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
  
    <!-- sparkline JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>