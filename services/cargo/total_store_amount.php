<?php

use BcMath\Number;

include 'connect.php';
$route_name = $_POST['route_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$store_status = $_POST['store'];
$order_by = "ORDER BY date_insert desc,id desc";
$routes = [];
$values = [];
 ($store_status=="") ?$store_query = "store!='out' and": $store_query = " store='$store_status' and";

if($start_date!=""){
	$start_date = "date_insert >= '$start_date' and";
	$order_by = "ORDER BY date_insert asc ,id asc";
}
if($end_date!=""){
	$end_date = "date_insert <= '$end_date' and";
	$order_by = "ORDER BY date_insert asc,id asc";
}



	$sum_to_be=0;
	$sum_paid=0;
			
$select2=mysqli_query($db,"SELECT store,paid_remain,to_be_remain,account FROM  parcel_info WHERE ".$start_date." ".$end_date."  ".$route_name ."  ".$store_query." 1");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
		
    	    $paid_remain =$get12['paid_remain'];
    	    $to_be_remain =$get12['to_be_remain'];
    	   
    	    $account =$get12['account'];




$values[] = [
    "route_name" => $account,
    "paid" => $paid_remain,
    "to_be" => $to_be_remain
];
        
		

if (in_array($account, $routes)) {
 
} else {

   $routes[] = $account;


}

		}
}

$total_left = $sum_paid +$sum_to_be;

?>
<?php
foreach ($routes as $route_name) {
   $total_to_be_paid = 0;
   $total_paid = 0;
foreach ($values as $row) {
    //echo" ".$route_name." ". $row["route_name"] . "<br>";

    if($route_name ==$row["route_name"] ){
   $total_to_be_paid +=$row["to_be"]; 
   $total_paid +=$row["paid"]; 
    }
}
?>

 <tr id="total_amount_tr">
        <td><?php  //echo implode(', ', $values);?></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo $route_name;?></td>
        <td>Paid</td>
        <td><strong><?php echo number_format($total_paid); ?></strong></td>
        <td>To be paid</td>
        <td><strong><?php echo number_format($total_to_be_paid)?></strong></td>
        <td>Total</td>
        <td><strong><?php echo number_format($total_to_be_paid+$total_paid)?></strong></td>
   
      
    </tr>
<?php

}

?>
 
<?php


?>