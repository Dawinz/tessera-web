<?php
include 'connect.php';
$code =$_POST['code'];
$scanned = $_POST['scanned'];
$amount = $_POST['amount'];
$number= $_POST['number'];
$choosen = $_POST['choosen'];
$to_pay_amm = $_POST['to_pay_amm'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('m/d/Y', time());
if($scanned  == 0){
	echo "exist";
}else{
$time_remain = $_POST['time_remain'];
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    
		  $scanned=$get13['scanned'];  
		  $amount=$get13['amount'];  
		  $s_q= $get13['scanned'];
		  $quantity= $get13['question']; 
		  $amount_remain= $get13['amount_remain'];
		  $status= $get13['status'];
		   $status= $get13['status'];
		   $due= $get13['s_surname'];
		   $paid= $get13['paid'];	  
		   $to_be= $get13['s_surname'];
		   $to_be_real=$get13['to_be'];
		
	}
}
   $select1=mysqli_query($db,"SELECT *  FROM parcel_info where code ='$code' and amount_remain='0'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 ==0){
	if($status == "Due payment"){
		$quantity_scanned=  $s_q - $number;
	$scanned=$quantity - $s_q ;
	@$entire_remain = $amount / $quantity * $number + $amount_remain;
	@$to_be = $to_be / $quantity * $number + $to_be_real;
	
	$insert=mysqli_query($db,"UPDATE parcel_info SET to_be='$to_be',scanned='$quantity_scanned',store='out',amount_remain='$entire_remain',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");	
	}else if($status == "To be Payed"){
			$quantity_scanned=  $s_q - $number;
	
	@$entire_remain = $amount / $quantity * $number + $amount_remain;
	
	$insert=mysqli_query($db,"UPDATE parcel_info SET to_be='$entire_remain',scanned='$quantity_scanned',store='out',amount_remain='$entire_remain',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
	}else if($status=="paid"){
				$quantity_scanned=  $s_q - $number;
	
	@$entire_remain = $amount / $quantity * $number + $amount_remain;
	
	$insert=mysqli_query($db,"UPDATE parcel_info SET paid='$entire_remain',scanned='$quantity_scanned',store='out',amount_remain='$entire_remain',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
	}
	
echo "$s_q";

}else{
		if($status == "Due payment"){
		$quantity_scanned= $quantity - $number;
		@$scanned_remain = $amount / $quantity;
		@$entire_remain = $scanned_remain * $number;
		$due_remain = $to_be / $quantity;
		$total_duepay= $due_remain * $number;
$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$entire_remain',bus='$choosen',trip_date='$date_insert',to_be='$total_duepay' WHERE code ='$code'");
echo "$entire_remain";	
		}
		else if($status == "To be Payed"){
		$quantity_scanned= $quantity - $number;
		@$scanned_remain = $amount / $quantity;
		@$entire_remain = $scanned_remain * $number;
		$due_remain = $to_be_real / $quantity;
		$total_duepay = $due_remain * $number;
$insert=mysqli_query($db,"UPDATE parcel_info SET to_be='$entire_remain',scanned='$quantity_scanned',store='out',amount_remain='$entire_remain',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
echo "$entire_remain";	
		}else if($status=="paid"){
				$quantity_scanned= $quantity - $number;
		@$scanned_remain = $amount / $quantity;
		@$entire_remain = $scanned_remain * $number;
		$due_remain = $paid / $quantity;
		$total_duepay= $due_remain * $number;
$insert=mysqli_query($db,"UPDATE parcel_info SET paid='$entire_remain',scanned='$quantity_scanned',store='out',amount_remain='$entire_remain',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
echo "$entire_remain";
			
		}

}
}

?>