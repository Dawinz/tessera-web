<?php




$amount_array=array();
$to_be_array=array();
$paid_array=array();



include '../connect.php';

$select222=mysqli_query($db,"SELECT amount,paid,to_be  FROM  manifest  where bus ='0985AJ05' and trip_date ='2023-11-15'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $amount =$get122['amount']; 
			$paid =$get122['paid']; 
			$to_be =$get122['to_be']; 
array_push($amount_array, $amount);
array_push($to_be_array, $to_be);
array_push($paid_array, $paid);
		
	}
}
echo "sum(a) = " . array_sum($amount_array);
echo "<br>sum(a) = " . array_sum($to_be_array);
echo "<br>sum(a) = " . array_sum($paid_array);
?>