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

@$quantity_scanned= $quantity - $number;
@$entire_remain_due = $amount_remain + $to_pay_amm;
$quantity_scanned2= $s_q - $number;
   $select1=mysqli_query($db,"SELECT *  FROM parcel_info where code ='$code' and amount_remain='0'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 ==0){
	if($status == "Due payment"){
		if($quantity_scanned == 0){
				
	
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned2',store='out',amount_remain='$entire_remain_due',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");
echo "$quantity_scanned ";	

		}else{
				$quantity_scanned= $s_q - $number;
			$quantity_scanned= $quantity - $number;
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned2',store='half_out',amount_remain='$entire_remain_due',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");
echo "$quantity_scanned";	

		}

	}else if($status == "To be Payed"){
			$quantity_scanned=  $s_q - $number;
	$entire_remain = $to_be_real + $to_pay_amm;
	if($quantity_scanned == 0){
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$entire_remain',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");

	}else{
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$entire_remain ',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");

	}
	
	}else if($status=="paid"){
		if($quantity_scanned == 0){
			$quantity_scanned=  $s_q - $number;
	$entire_remain = $paid + $to_pay_amm;
			$insert=mysqli_query($db,"UPDATE parcel_info SET amount_remain='$paid',scanned='$quantity_scanned2',store='out',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
echo "$to_pay_amm ";	

		}else{
			$quantity_scanned= $quantity - $number;
			$insert=mysqli_query($db,"UPDATE parcel_info SET amount_remain='$paid',scanned='$quantity_scanned2',store='half_out',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
echo "$to_pay_amm ";	

		}

	}
	
echo "$s_q";

}else{
	
@$amount_paid = $amount - $paid;
		if($status == "Due payment"){
						$amount_to_be = $to_be - $to_pay_amm;
	if($quantity_scanned == 0){
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");
echo "$to_pay_amm ";	

		}else{
			$quantity_scanned= $quantity - $number;
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");
echo "$to_pay_amm ";	

		}
		}
		else if($status == "To be Payed"){
			$amount_to_be = $amount - $to_pay_amm;
		if($quantity_scanned == 0){
			
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");
echo "$amount_to_be ";	

		}else{
			$quantity_scanned= $quantity - $number;
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert',to_be='$to_pay_amm' WHERE code ='$code'");
echo "$amount_to_be";	

		}

		}
		else if($status=="paid"){
		if($quantity_scanned == 0){
			
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
echo "amount_paid";	

		}else{
			$quantity_scanned= $quantity - $number;
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
echo "$amount_paid ";	

		}
			
		}

}
}

?>