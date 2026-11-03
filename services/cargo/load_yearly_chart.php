	    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="js/vendor/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawStacked);

function drawStacked() {
      var data = google.visualization.arrayToDataTable([
        ['Status', 'Payed', 'To be paid','Total amount'],
   
     <?php
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');

// First day of the month.
$date1 = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$date2 =  date('m/t/Y', strtotime($query_date));



		
		$select2=mysqli_query($db,"SELECT *  FROM  parcel_info  GROUP BY (month) ORDER BY id desc ");
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
			
				echo " ['$month', $paid_view, $to_be_view ,$amount], ";
			
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
        title: 'Total charges',
        chartArea: {width: '50%'},
        isStacked: true,
        hAxis: {
          title: 'Total Income',
          minValue: 0,
        },
        vAxis: {
          title: 'Month'
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
      chart.draw(data, options);
    }
  </script>
<div id="chart_div2"style="width: 900px; height: 800px;"></div>

  