<?php
							$route = $_GET['route'];
						$route = base64_decode($route);
						?>
		<div class="apex-charts mt-1" id="daily"></div>
		
		
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

  <script src="/cargo/assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
  var options = {
          series: [{
            name: "Paid amount",
            data: [
				  		    <?php

include'connect.php';

$select124=mysqli_query($db,"SELECT date_insert,username  FROM  daily_charges WHERE   location='$route' ORDER BY id Desc limit 30");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
			    $date_insert =$get1324['date_insert']; 
			    $username_check=$get1324['username']; 
		$sum_paid = 0;
$select_username=mysqli_query($db,"SELECT paid  FROM  daily_charges WHERE   location='$route'  and date_insert ='$date_insert' and username='$username_check'");
$select_num_username=mysqli_num_rows($select_username);
if($select_num_username !=0){
   	WHILE($get_username=mysqli_fetch_array($select_username)){
    	    $paid_username=$get_username['paid']; 
$sum_paid += $paid_username;

}
 
}
echo "$sum_paid,";

}
 
}
?>	
			]
          },
          {
            name: "To be paid",
            data: [
 <?php

include'connect.php';

$select124=mysqli_query($db,"SELECT date_insert,username  FROM  daily_charges WHERE   location='$route' ORDER BY id Desc limit 30");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
			    $date_insert =$get1324['date_insert']; 
			    $username_check=$get1324['username']; 
		$sum_to_be = 0;
			

$select_username=mysqli_query($db,"SELECT to_be  FROM  daily_charges WHERE   location='$route'  and date_insert ='$date_insert' and username='$username_check'");
$select_num_username=mysqli_num_rows($select_username);
if($select_num_username !=0){
   	WHILE($get_username=mysqli_fetch_array($select_username)){
    	    $to_be_username=$get_username['to_be']; 
$sum_to_be += $to_be_username;

}
 
}
echo "$sum_to_be,";

}
 
}
?>	
			]
          },
          {
            name: 'Total amount',
            data: [
 <?php

include'connect.php';

$select124=mysqli_query($db,"SELECT date_insert,username  FROM  daily_charges WHERE   location='$route' ORDER BY id Desc limit 30");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
			    $date_insert =$get1324['date_insert']; 
			    $username_check=$get1324['username']; 
		$sum_to_be = 0;
			$sum_paid = 0;
$select_username=mysqli_query($db,"SELECT to_be,paid  FROM  daily_charges WHERE   location='$route'  and date_insert ='$date_insert' and username='$username_check'");
$select_num_username=mysqli_num_rows($select_username);
if($select_num_username !=0){
   	WHILE($get_username=mysqli_fetch_array($select_username)){
    	    $to_be_username=$get_username['to_be']; 
						$paid_username=$get_username['paid']; 
						$sum_paid += $paid_username;

$sum_to_be += $to_be_username;

}
 
}
$total= $sum_to_be + $sum_paid;
echo "$total,";

}
 
}
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
          text: 'Daily Charges - <?php echo $route;?>',
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
                           return "Tsh " + val.toLocaleString()+ " "

                }
              }
            },
            {
              title: {
                formatter: function (val) {
                              return "Tsh " + val.toLocaleString()+ " "

                }
              }
            },
            {
              title: {
                formatter: function (val) {
              return "Tsh " + val.toLocaleString()+ " "
                }
              }
            }
          ]
        },
        grid: {
          borderColor: '#f1f1f1',
        }
        };

        var chart = new ApexCharts(document.querySelector("#daily"), options);
        chart.render();
      
      
</script>