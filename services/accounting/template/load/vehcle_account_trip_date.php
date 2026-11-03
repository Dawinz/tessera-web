<?php

use BcMath\Number;

include '../connect/connect.php';
$vehicle = $_POST['vehicle'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$symbol = $_POST['symbol'];
$selected_currency = $_POST['selected_currency'];

$grand_total_income = 0;
$grand_total_expenses = 0;
$grand_total_final = 0;

?>
   <table style="margin-bottom:100px; border:none;"id="order-listing" class="table" cellspacing="0" width="100%">
<thead>
<tr class="bg-primary text-white" style="font-color:grey;">
  
<td>Trip Date</td><td>income </td><td>Expenses </td><td>Total </td>
 
</tr>

</thead>
<body>


<?php
$select_status=mysqli_query($db,"SELECT trip_date,route_name FROM   vehicle_account   where trip_date >= '$start_date' AND trip_date < '$end_date' and bus='$vehicle'  group by trip_date");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   

    	    $trip_date =$get_status['trip_date']; 
    	    $route_name =$get_status['route_name']; 
           
       
            ?>
            <tr class="bg-primary text-white" style="font-color:grey;">
  
<td><a href="manifest_view.php?route_name=<?php echo $route_name;?>&bus=<?php echo $vehicle;?>&trip_date=<?php echo $trip_date?>&checked=&main_page=manifest.php"><?php echo $trip_date;?></a></td><td> </td><td> </td><td> </td>
 
</tr>
            <?php
$total_expenses =0; 
$total_income =0; 
$select=mysqli_query($db,"SELECT expenses,current_route,income FROM   vehicle_account   where trip_date = '$trip_date' and bus='$vehicle' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $expenses =$get['expenses']; 
    	    $income =$get['income']; 
    	    $current_route =$get['current_route']; 


$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}

 if($selected_currency == $currency_curent){
$total_expenses += $expenses;
$total_income+= $income;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_expenses += $expenses*$currency_value;
$total_income+= $income*$currency_value;
}


    }}
  $grand_total = $total_income-$total_expenses;
  $grand_total_income +=$total_income;
  $grand_total_expenses +=$total_expenses;
  $grand_total_final +=$grand_total;
?>
<tr >
<td></td><td><?php echo number_format($total_income);?></td><td><?php echo number_format($total_expenses);?> </td><td><?php echo number_format($grand_total ) ;?> </td>
 
</tr>
<?php
    }}
?>
<tr style="border-top:2px solid black;border-bottom:2px solid black;">
<td></td><td><?php echo number_format($grand_total_income);?></td><td><?php echo number_format($grand_total_expenses);?> </td><td><?php echo number_format($grand_total_final ) ;?> </td>
 
</tr>
</body>
   </table>