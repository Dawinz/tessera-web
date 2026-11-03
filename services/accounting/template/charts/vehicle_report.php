<?php
include'../connect/connect.php';
include '../search/includes/total_sales.php';
$selected_currency= $_POST['selected_currency'];
$start_date= $_POST['start_date'];
$end_date= $_POST['end_date'];
$selected_currency= $_POST['selected_currency'];
$route_name= $_POST['route_name'];
$symbol ="";
if($route_name!=""){
$route_name = " route_name ='$route_name' and ";
}
if($start_date!=""){
	$start_date =  date("Y-m-d", strtotime($_POST['start_date']));
$start_date = " trip_date >='$start_date' and ";
}
if($end_date!=""){
	$end_date =  date("Y-m-d", strtotime($_POST['end_date']));
$end_date = " trip_date <='$end_date' and ";
}
//////////

?>
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Bus', 'Income', 'Expenses', 'Balance'],
        <?php
        $select_order_bus=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by bus asc )
 row_num, bus FROM   manifest   where ".$start_date." ".$end_date." ".$route_name."   1 group by bus");
$select_num_order_bus=mysqli_num_rows($select_order_bus);
if($select_num_order_bus!=0){
   	WHILE($get_order_bus=mysqli_fetch_array($select_order_bus)){
    	    $row_num =$get_order_bus['row_num']; 
    	    $bus =$get_order_bus['bus']; 

?>
['<?php echo $bus;?>',
 <?php echo sumTwoNumbers($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);?>,
 <?php expensesReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);?>,

  <?php balance($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name);?>,
],
<?php


    }}
        ?>
        ]);

        var options = {
          
          bars: 'vertical',
        direction:-1,
          
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
  
    <div id="barchart_material" style=" height: 500px;"></div>
  