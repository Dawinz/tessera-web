<?php
							$route = $_GET['route'];
						$route = base64_decode($route);
						?>
		
		<div class="apex-charts mt-1" id="daily_recipt"></div>
		
		
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

  <script src="/cargo/assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
  var options = {
          series: [{
            name: "Paid receipts",
            data: [
				  		    <?php

include'connect.php';
								$select124=mysqli_query($db,"SELECT *  FROM  daily_charges WHERE   location='$route'  ORDER BY id Desc limit 30");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
    	    $paid=$get1324['no_paid']; 

echo "$paid,";
}}
?>	
			]
          },
          {
            name: "To Be Paid receipts",
            data: [
					  <?php

include'connect.php';
								$select12=mysqli_query($db,"SELECT *  FROM  daily_charges WHERE   location='$route'  ORDER BY id Desc limit 30");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
    	    $to_be2=$get132['no_to_be']; 

echo "$to_be2,";
}}
?>
			]
          },
          {
            name: 'Due receipts',
            data: [
			  <?php

include'connect.php';
								$select1=mysqli_query($db,"SELECT *  FROM  daily_charges WHERE   location='$route'  ORDER BY id Desc limit 30");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){

$no_due = $get13['no_due'];

echo "$no_due,";
}}
?>
			]
          }
        ],
          chart: {
          height: 400,
          type: 'line',
          zoom: {
            enabled: false
          },
        },
        dataLabels: {
          enabled: false
        },
       
        title: {
          text: 'Daily receipts - <?php echo $route;?>',
          align: 'left'
        },
        legend: {
          tooltipHoverFormatter: function(val, opts) {
            return val.toLocaleString()+ ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
          }
        },
        markers: {
          size: 0,
          hover: {
            sizeOffset: 6
          }
        },
        xaxis: {
          categories: [ <?php

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$total_number = date("t", strtotime($date_insert));
function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }

for ($x = 1; $x <= $total_number; $x++) {
$result_no =  addOrdinalNumberSuffix($x);
echo "'$result_no',";

}


	
?>],
        },
        tooltip: {
          y: [
            {
              title: {
                formatter: function (val) {
                           return "receipts " + val.toLocaleString()+ " "

                }
              }
            },
            {
              title: {
                formatter: function (val) {
                              return "receipts " + val.toLocaleString()+ " "

                }
              }
            },
            {
              title: {
                formatter: function (val) {
              return "receipts " + val.toLocaleString()+ " "
                }
              }
            }
          ]
        },
        grid: {
          borderColor: '#f1f1f1',
        }
        };

        var chart = new ApexCharts(document.querySelector("#daily_recipt"), options);
        chart.render();
      
      
</script>