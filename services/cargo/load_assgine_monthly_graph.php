<?php
include 'connect.php';
session_start();
$username=$_SESSION['username'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$month = date('m-Y', time());
$sum_paid = 0;
$sum_to_be = 0;

?>
<div class="" id="chart_2"></div>
		
		
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

  <script src="/cargo/assets/libs/apexcharts/apexcharts.min.js"></script>
  <script>
   var options = {
          series: [{
          name: 'Paid',
          data: [
	   <?php
		  $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
	   	WHILE($get132=mysqli_fetch_array($select12)){
			$sum_paid = 0;
			
			
$route_full_name =$get132['route_full_name']; 

		  $select12_total=mysqli_query($db,"SELECT *  FROM  monthly_charges where location='$route_full_name' and date_insert ='$month'");
$select_num12_total=mysqli_num_rows($select12_total);
if($select_num12_total !=0){
	   	WHILE($get132_total=mysqli_fetch_array($select12_total)){
$paid =$get132_total['paid']; 
 $sum_paid += $paid;
 
}


}
 echo "'$sum_paid',";


}
 
}
		  ?>
		  
		  ],
		  
        }, {
          name: 'To be paid',
          data: [
 <?php
		  $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
	   	WHILE($get132=mysqli_fetch_array($select12)){
			$sum_to_be = 0;

$route_full_name =$get132['route_full_name']; 

		  $select12_total=mysqli_query($db,"SELECT *  FROM  monthly_charges where location='$route_full_name' and date_insert ='$month'");
$select_num12_total=mysqli_num_rows($select12_total);
if($select_num12_total !=0){
	   	WHILE($get132_total=mysqli_fetch_array($select12_total)){
$to_be =$get132_total['to_be']; 
			$sum_to_be += $to_be;

}
}

echo "'$sum_to_be',";
}

}
		  ?>
		  ]
        }, {
          name: 'Total amount',
          data: [
	     
		  <?php
		  $select1233=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc");
$select_num123=mysqli_num_rows($select1233);
if($select_num123 !=0){
	   	WHILE($get1323=mysqli_fetch_array($select1233)){
			$sum_to_be2 = 0;
	$sum_paid2 = 0;
$route_full_name =$get1323['route_full_name']; 

		  $select12_total3=mysqli_query($db,"SELECT *  FROM  monthly_charges where location='$route_full_name' and date_insert ='$month'");
$select_num12_total3=mysqli_num_rows($select12_total3);
if($select_num12_total3 !=0){
	   	WHILE($get132_total3=mysqli_fetch_array($select12_total3)){
$to_be_total =$get132_total3['to_be']; 
$paid_total =$get132_total3['paid']; 
$sum_to_be2 += $to_be_total;
$sum_paid2+= $paid_total;

}
}
$total_sum = $sum_paid2 + $sum_to_be2;

echo "'$total_sum',";
}

}
		  ?>
		  
		  ]
		  
        },
		
		],
          chart: {
          type: 'bar',
          height: 320,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        plotOptions: {
          bar: {
            horizontal: true,
            borderRadius: 4,
            dataLabels: {
          total: {
                enabled: true,
                offsetX: 0,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
		
       xaxis: {
          categories: [
		  <?php
		  $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username' order by preference desc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_name =$get132['route_name']; 
$route_name = base64_decode($route_name);
echo "'$route_name',";
}
}
		  ?>
		  
		  ],
          labels: {
            formatter: function (val) {
  return " Tsh " + val.toLocaleString()+ " "
  }
          }
        },
        legend: {
          position: 'right',
          offsetY: 40
        },
        fill: {
          opacity: 1
        }, tooltip: {
          y: {
            formatter: function (val) {
  return "Tsh " + val.toLocaleString()+ " "
            }
          }
        },
        };

        var chart = new ApexCharts(document.querySelector("#chart_2"), options);
        chart.render();
      
      
  </script>