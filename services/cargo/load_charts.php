    <div class="notika-status-area">
        <div class="container">
            <div class="row">
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



									$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
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
                        <div class="sparkline-bar-stats1">
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



									$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
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



									$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where   date_insert >= ' $date1' and date_insert <= ' $date2' ");
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
                            <h2>Tsh <span class="counter">
								<?php
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
				$to_be=$data['to_be'];
				echo number_format($to_be,2);
			
		}}else{
			echo "0.00";
		}
							?>
							</span></h2>
                            <p>Total Monthly To-be-paid Ammount</p>
                        </div>
                        <div class="sparkline-bar-stats3">
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
                            <h2>reciepts <span class="counter"><?php
									$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where   date_insert >= ' $date1' and date_insert <= ' $date2'");
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