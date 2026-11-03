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

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  GROUP BY (month) ORDER BY id desc limit 6");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert']; 
			$month =$get12['month']; 
		 $orgDate = $month;  
    $newDate = date("M/y", strtotime($orgDate));  
		
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
				echo " [' $month',$paid_view   , $to_be_view, $amount,],";
			
		}}else{
			echo "Nothing";
		}	


		
	}
}else{
	echo "no";
}
		
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