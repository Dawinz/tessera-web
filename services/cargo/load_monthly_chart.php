	<?php
							$route = $_GET['route'];
						$route = base64_decode($route);
						?>
							    <?php
								
								
								
								function paid($route) {
 
 
 include'connect.php';
$sum_paid = 0;
$select124=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE   location='$route' group by date_insert ORDER BY sort Desc limit 24  ");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
    	    $date_insert=$get1324['date_insert']; 
	

	$sum_paid = 0;
$select_paid=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE   location='$route' and date_insert='$date_insert'  ");
$select_num_paid=mysqli_num_rows($select_paid);
if($select_num_paid !=0){
   	WHILE($get_paid=mysqli_fetch_array($select_paid)){
    	    $paid=$get_paid['paid']; 

$sum_paid += $paid;
}
 
 echo "$sum_paid,";
}
	

}
 

}
}					function to_be($route) {
 
 
 include'connect.php';

$select124=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE   location='$route' group by date_insert ORDER BY sort Desc limit 24  ");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
    	    $date_insert=$get1324['date_insert']; 
	

	$sum_to_be = 0;
$select_paid=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE   location='$route' and date_insert='$date_insert'  ");
$select_num_paid=mysqli_num_rows($select_paid);
if($select_num_paid !=0){
   	WHILE($get_paid=mysqli_fetch_array($select_paid)){
    	    $to_be2=$get_paid['to_be']; 
	$sum_to_be += $to_be2;
}
echo "$sum_to_be,";
}
	

}
 

}
}	


function total($route) {
 
 
 include'connect.php';

$select124=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE   location='$route' group by date_insert ORDER BY sort Desc limit 24  ");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
    	    $date_insert=$get1324['date_insert']; 
	

	$sum_to_be = 0;
	$sum_paid = 0;
$select_paid=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE   location='$route' and date_insert='$date_insert'  ");
$select_num_paid=mysqli_num_rows($select_paid);
if($select_num_paid !=0){
   	WHILE($get_paid=mysqli_fetch_array($select_paid)){
    	    $to_be2=$get_paid['to_be']; 
    	     $paid=$get_paid['paid']; 
$sum_paid += $paid;
	$sum_to_be += $to_be2;
}

$total = $sum_paid+$sum_to_be;
echo "$total,";

}
	

}
 

}
}					






	function days($route) {
 
 
 include'connect.php';
$sum_paid = 0;
$select124=mysqli_query($db,"SELECT *  FROM  monthly_charges WHERE   location='$route' group by date_insert ORDER BY sort Desc limit 24  ");
$select_num124=mysqli_num_rows($select124);
if($select_num124 !=0){
   	WHILE($get1324=mysqli_fetch_array($select124)){
    	    $date_insert=$get1324['date_insert']; 
	

echo "'$date_insert',";
}
 

}
}
								




?>

		
		<div class="apex-charts mt-1" id="chart"></div>
		
		
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

  <script src="/cargo/assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
 var options = {
          series: [{
          name: 'Paid amount',
          data: [
	
		<?php
		paid($route);
		?>
		  ]
        }, {
          name: 'To be paid',
          data: [
<?php
to_be($route);

?>
		  ]
        }, {
          name: 'Total charge',
          data: [
	 

<?php
total($route);
?>
		  ]
        }],
          chart: {
          type: 'bar',
          height: 450
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: [
<?php
days($route);
?>

		  ],
        },
        yaxis: {
          title: {
            text: '<?php echo $route;?>Tsh (Millions)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "Tsh " + val.toLocaleString()+ " "
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      
      
</script>