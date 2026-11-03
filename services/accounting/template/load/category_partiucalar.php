<?php
$route_name = $_POST['route_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$selected_currency = $_POST['selected_currency'];
$category_query_function = $_POST['category_query_function'];
$checked = "main_page=manifest.php";
include '../connect/connect.php';

?>

            <table  class="table" id="order-listing">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">No</th>
                      <th class="wd-20p">Trip date</th>                     
                      <th class="wd-20p">Bus</th>                     
					 	<th class="wd-20p">Amount</th>


                  </tr>
              </thead>
              <tbody>
<?php
$total_expenses = 0;
$grand_total_expenses = 0;
$count = 0;
$select_status=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id asc )  row_num,expenses,current_route,trip_date FROM   vehicle_account   where trip_date >= '$start_date' AND trip_date < '$end_date' and route_name='$route_name' $category_query_function and expenses!='0'group by trip_date");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   
    	    $row_num =$get_status['row_num']; 
    	    $expenses =$get_status['expenses']; 
    	    $current_route =$get_status['current_route']; 
    	    $trip_date =$get_status['trip_date']; 
$count ++;
?>


<?php
$total_expenses_sum = 0;

$select=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id asc )  row_num,expenses,trip_date,bus,date_insert,time_insert,fullname_user FROM   vehicle_account   where trip_date = '$trip_date' and route_name='$route_name' $category_query_function  and expenses!='0' group by bus");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $row_num_trip =$get['row_num']; 
    	    $bus =$get['bus']; 
    	    $expenses =$get['expenses']; 
    	    $date_insert =$get['time_insert']; 
    	    $date_insert =$get['date_insert']; 
    	    $trip_date2 =$get['trip_date']; 
    	    $fullname_user =$get['fullname_user']; 
            $bus_total_income =  sum_row_bus($route_name,$trip_date,$category_query_function,$db,$selected_currency,$bus);
$total_expenses_sum +=$bus_total_income ;
?>

<tr >
    <td><?php echo "($count)";?></td>
    <td><?php echo ($row_num_trip ==1) ? "$trip_date " : "";?></td>
    <td><u><a style="color:black;"href="manifest_view.php?route_name=<?php echo $route_name;?>&bus=<?php echo $bus;?>&trip_date=<?php echo $trip_date;?>&checked= <?php echo $checked;?>"><?php echo $bus;?></a></ul></td>
        <td><?php echo number_format($bus_total_income);?></td>

    
</tr>
<?php

    }}

    $grand_total_expenses +=$total_expenses_sum;
?>
<tr>
    <td></td>
    <td></td>
    <td><strong>Total Ammount</strong></td>
    <td><strong><?php echo number_format($total_expenses_sum,2);?></strong></td>


</tr>
<?php
    }}




?>
<tr style="border-top:2px solid black;border-bottom:2px solid black;">
    <td></td>
    <td></td>
    <td><strong>Grand Total</strong></td>
    <td><strong><?php echo number_format($grand_total_expenses);?></strong></td>  
    

</tr>
              
              </tbody>

            </table>
             <?php
        
   
        function sum_row_bus($route_name,$trip_date,$category_query_function,$db,$selected_currency,$bus){
           
 	$total_expenses =0; 
$select_status=mysqli_query($db,"SELECT expenses,current_route FROM   vehicle_account   where trip_date = '$trip_date' and bus='$bus'and route_name='$route_name' $category_query_function and  expenses!='0'");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   
    	    $expenses =$get_status['expenses']; 
    	    $current_route =$get_status['current_route']; 

    

$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}

 if($selected_currency == $currency_curent){
$total_expenses += $expenses;

}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_expenses += $expenses*$currency_value;
}



    }}

return $total_expenses;

       }
        
        ?>
  
