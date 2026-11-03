	  <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
  <script type="text/javascript" src="jquery.lazyload-any.js"></script>   
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Paid', 'To-be-paid', 'Total'],
	<?php
	include 'connect.php';

	date_default_timezone_set('Africa/Dar_es_Salaam');
	$month = date('m/Y', time());
	$year = date('Y', time());
	$month_one = date('m', time());
	$month_name = date("F", mktime(0, 0, 0, $month_one, 10));
								$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   month ='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
			
		}}else{
			echo "Nothing";
		}	
								$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where    month ='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid_view=$data['paid'];
			
		}}else{
			echo "Nothing";
		}	
					$sql = $db->query("SELECT SUM(to_be) AS to_be FROM parcel_info  where    month ='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_view=$data['to_be'];
				echo " [' $month_name $year',$paid_view   , $to_be_view, $amount,],";
			
		}}else{
			echo "Nothing";
		}	

	
	$x = 1;
 
while($x <= 5) {
  $month =  date('m/Y',strtotime('first day of -'.$x.' month'));
  $year =  date('Y',strtotime('first day of -'.$x.' month'));
  $month_name =  date('m',strtotime('first day of -'.$x.' month'));
  $month_name = date("F", mktime(0, 0, 0, $month_name, 10));

  
  
  		$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where   month ='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
			
		}}else{
			echo "Nothing";
		}	
								$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where    month ='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid_view=$data['paid'];
			
		}}else{
			echo "Nothing";
		}	
					$sql = $db->query("SELECT SUM(to_be) AS to_be FROM parcel_info  where    month ='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_view=$data['to_be'];
				echo " [' $month_name $year',$paid_view   , $to_be_view, $amount,],";
			
		}}else{
			echo "Nothing";
		}	

  $x++;
}
	

// e.g. "Jan"


			?>
			        ]);

        var options = {
          title : 'Monthly Luggage Charges',
          vAxis: {title: 'Tsh'},
          hAxis: {title: 'Month'},
          seriesType: 'bars',
          series: {5: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	<div id="chart_div"style="width: 800px; height: 350px;"></div>
	  <script>

  $('.lazyload').lazyload({threshold: 200});
  </script>