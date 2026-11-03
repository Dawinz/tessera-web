<?php
session_start();
?>
<?php
include 'connect.php';

?>
<?php
include 'connect.php';
$code =$_POST['code'];
$date_insert = $_POST['date_insert'];
$choosen = $_POST['choosen'];
////////////////////////////////
//////////////////////////////

	

$select23=mysqli_query($db,"SELECT * FROM `manifest` WHERE code='$code' and trip_date='$date_insert' and bus='$choosen' ");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
  echo "inside";
}else{

$choosen = $_POST['choosen'];

$to_pay_amm = 0;
$number=0;
$q_left = 0;
$due_topaid = 0;

$scanned = $_POST['scanned'];
$amount = $_POST['amount'];
$number= $_POST['number'];

$due_topaid = $_POST['due_topaid'];
$to_pay_amm = preg_replace('/[^0-9.]+/', '', $_POST['to_pay_amm']);
date_default_timezone_set('Africa/Dar_es_Salaam');

	

$select23=mysqli_query($db,"SELECT * FROM `manifest` WHERE code='$code' ORDER BY id DESC LIMIT 1 ");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
    	    $q_left_manifest =$get123['q_left']; 



		
	}
}

//////////////////////////////////////
////////////////////////////////////
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
		   $due= $get13['s_surname'];
		   $paid= $get13['paid'];	  
		   $to_be= $get13['s_surname'];
		   $to_be_real=$get13['to_be'];
		   $receivers_name=$get13['r_name'];
		   $username=$get13['username'];
		   $username_uploader=$get13['username'];
		   $detail=$get13['detail'];
          $due_to_pay_sec=$get13['answer']; 
		  $due_paid_s_sec=$get13['payment_status']; 
		  $route=$get13['account']; 
		   $loan=$get13['loan']; 
		  $loan_update=$get13['loan_update']; 
		  $parcel_date=$get13['date_insert']; 
		  $weight_name=$get13['weight_name']; 
		  $weight_value=$get13['weight_value']; 
		  $lugg_value=$get13['lugg_value']; 
	}
}
$select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    
		  $q_left=$get13['q_left'];  


	}
}


////////////////due area//////////////////
$due_paid_first =  $paid - $due_topaid;
$due_to_paid_f = $to_be_real - $to_pay_amm;
$amount_due_payment = $due_topaid + $to_pay_amm;

$second_paid_due = $due_paid_s_sec - $due_topaid;
$second_topay_due = $due_to_pay_sec - $to_pay_amm;
$amount_total_due_payment = $due_paid_s_sec + $second_paid_due;
//////////////////////////////////////////
////////////////////////////////////////////
//////////////////////////////////////////////
///////////////////// SCANED AREA/////////////
//////////////////////////////////////////////
@$quantity_scanned= $quantity - $number;
@$entire_remain_due = $amount_remain + $to_pay_amm;
@$entire_remain_due2 = $amount_remain - $to_pay_amm;
$quantity_remained_scanned = $s_q - $number;
$quantity_r = $s_q + $number;
$amount_to_be = $amount - $to_pay_amm;
$amount_left_sch = $amount_remain - $to_pay_amm;
$quantity_scanned2= $s_q - $number;
$quantity_rn= $q_left + $number;
$entire_remain = $to_be_real + $to_pay_amm;
$amr= $to_pay_amm + $due_topaid;
$due_reduce = $amount_remain - $amr;
$due_reduce_topaid = $due_to_pay_sec - $to_pay_amm;
$due_reduce_paid = $due_paid_s_sec - $due_topaid;
$total_due_reduced2 = $due_reduce_topaid + $due_reduce_paid;
/////////                 /////////////
///////// SELECTION AREAT/////////////
////////                 ////////////   
	
echo"$to_pay_amm $due_topaid";
   $select1=mysqli_query($db,"SELECT *  FROM parcel_info where code ='$code' and amount_remain='0'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 ==0){
	if($status == "Due payment"){
		if($quantity_remained_scanned == 0){
				
	
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned2',store='out',amount_remain='$total_due_reduced2',bus='$choosen',trip_date='$date_insert',payment_status='$second_paid_due',answer='$second_topay_due'  WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount','$date_insert','$choosen','$due_topaid','$to_pay_amm','$status','".$_SESSION['username']."','$quantity_rn','$quantity','$detail','$q_left_manifest','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");
 
 
 
echo $q_left + $number;


		}else{
				$quantity_scanned= $s_q - $number;
	
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned2',store='half_out',amount_remain='$total_due_reduced2',bus='$choosen',trip_date='$date_insert',payment_status='',payment_status='$second_paid_due',answer='$second_topay_due'  WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount_total_due_payment','$date_insert','$choosen','$due_topaid','$to_pay_amm','$status','".$_SESSION['username']."','$quantity_rn','$quantity','$detail','$q_left_manifest','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");

echo $q_left + $number;

		}

	}else if($status == "To be Payed"){
	
	if($quantity_remained_scanned  == 0){
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned2',store='out',amount_remain='$entire_remain_due2',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','','$to_pay_amm','$status','".$_SESSION['username']."','$quantity_rn','$quantity','$detail','$q_left_manifest','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");

	}else{
			$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned2',store='half_out',amount_remain='$entire_remain_due2',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','','$to_pay_amm','$status','".$_SESSION['username']."','$quantity_rn','$quantity','$detail','$q_left_manifest','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");

	}
	
	}else if($status=="paid"){
		if($quantity_remained_scanned == 0){
			$quantity_scanned=  $s_q - $number;
	$entire_remain = $paid + $to_pay_amm;
			$insert=mysqli_query($db,"UPDATE parcel_info SET amount_remain='$entire_remain_due2',scanned='$quantity_scanned2',store='out',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','$to_pay_amm','','$status','".$_SESSION['username']."','$quantity_rn','$quantity','$detail','$q_left_manifest','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");



		}else{
			$quantity_scanned= $quantity - $number;
			 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','$to_pay_amm','','$status','".$_SESSION['username']."','$quantity_rn','$quantity','$detail','$q_left_manifest','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");

			$insert=mysqli_query($db,"UPDATE parcel_info SET amount_remain='$entire_remain_due2',scanned='$quantity_scanned2',store='half_out',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");


		}

	}
	


}else{
	//////////////////////////////////////////
	/////////////////////////////////////////
	//////////////////////////////////////
	/////////////////////////////////////////
@$amount_paid = $amount - $paid;
		if($status == "Due payment"){
						$amount_to_be = $to_be - $to_pay_amm;
                         $amount_paid_due2 = $paid - $due_topaid;
						 $total_amount_due = $amount_to_be + $amount_paid_due2;
	if($quantity_scanned == 0){
		
			if($loan_update != ""){
							$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount','$date_insert','$choosen','$paid','$to_be_real','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");
			}else{
						$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount','$date_insert','$choosen','$paid','$to_be_real','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','$loan','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");	
			}
		




		}else{
			$quantity_scanned= $quantity - $number;
			
			if($loan_update != ""){
					$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$total_amount_due',bus='$choosen',trip_date='$date_insert',due='$to_pay_amm',payment_status='$due_paid_first',answer='$due_to_paid_f' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount_due_payment','$date_insert','$choosen','$due_topaid','$to_pay_amm','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");		
			}else{
					$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$total_amount_due',bus='$choosen',trip_date='$date_insert',due='$to_pay_amm',payment_status='$due_paid_first',answer='$due_to_paid_f' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount_due_payment','$date_insert','$choosen','$due_topaid','$to_pay_amm','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','$loan','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");		
			}
			
			



		}
		}
		else if($status == "To be Payed"){
		if($quantity_scanned == 0){
			if($loan_update != ""){
				
							$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_be_real','$date_insert','$choosen','','$to_be_real','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");
			}else{
						$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_be_real','$date_insert','$choosen','','$to_be_real','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','$loan','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");	
			}



		}else{
			$quantity_scanned= $quantity - $number;
			
			if($loan_update != ""){
							$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','','$to_pay_amm','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");
			}else{
						$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','','$to_pay_amm','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','$loan','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");	
			}




		}

		}
		else if($status=="paid"){
		if($quantity_scanned == 0){
				if($loan_update != ""){
							$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount','$date_insert','$choosen','$amount','','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");	
				}else{
						$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$amount','$date_insert','$choosen','$amount','','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','$loan','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");		
				}




		}else{
			$quantity_scanned= $quantity - $number;
				if($loan_update != ""){
					$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','$to_pay_amm','','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");
				}else{
				$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$quantity_scanned',store='half_out',amount_remain='$amount_to_be',bus='$choosen',trip_date='$date_insert' WHERE code ='$code'");
 $insert=mysqli_query($db,"INSERT INTO manifest VALUES('','$code','$receivers_name','$to_pay_amm','$date_insert','$choosen','$to_pay_amm','','$status','".$_SESSION['username']."','$number','$quantity','$detail','','$username','','','','','','$route','$loan','$parcel_date','$number','$weight_name','$weight_value','$lugg_value')");	
				}



		}
			
		}

}
	


$select13=mysqli_query($db,"SELECT to_pay,paid,due   FROM  vehicle_name  WHERE date ='$date_insert' and Name='$choosen' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_pay_vehicle =$get133['to_pay']; 
					$paid_vehicle =$get133['paid']; 
					$due_vehicle =$get133['due']; 
}
}	


if($status=="To be Payed"){
	
	
	if($quantity_remained_scanned ==0){
			$total_due = $due_vehicle+$to_pay_amm;
	$update_vehicle_topay = $to_pay_vehicle + $to_pay_amm;
$insert3=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$update_vehicle_topay',due='$total_due' WHERE date ='$date_insert' and Name='$choosen'");
echo "$update_vehicle_topay $total_due --- $to_pay_amm";
	}else{
				$total_due = $due_vehicle+$to_pay_amm;
	$update_vehicle_topay = $to_pay_vehicle + $to_pay_amm;
$insert3=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$update_vehicle_topay',due='$total_due' WHERE date ='$date_insert' and Name='$choosen'");
	echo "<br>$update_vehicle_topay $total_due --- $to_pay_amm";

	}

}
if($status=="paid"){
	$total_due = $due_vehicle+$to_pay_amm;
	$update_vehicle_paid = $paid_vehicle + $to_pay_amm;
			$insert3=mysqli_query($db,"UPDATE vehicle_name SET paid='$update_vehicle_paid',due='$total_due' WHERE date ='$date_insert' and Name='$choosen'");

}
if($status=="Due payment"){


if($quantity_remained_scanned ==0){
	
	
	if($quantity==1){
			$update_vehicle_paid = $paid_vehicle + $paid;
$update_vehicle_topay = $to_pay_vehicle + $to_be_real;
$total_due=$due_vehicle+$paid+$to_be_real;
		$insert3=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$update_vehicle_topay',paid='$update_vehicle_paid',due='$total_due' WHERE date ='$date_insert' and Name='$choosen'");
echo" yess!! $paid $to_be_real -- $update_vehicle_paid $update_vehicle_topay";
	}else{
			$update_vehicle_paid = $paid_vehicle + $due_topaid;
$update_vehicle_topay = $to_pay_vehicle + $to_pay_amm;
$total_due=$due_vehicle+$due_topaid+$to_pay_amm;
		$insert3=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$update_vehicle_topay',paid='$update_vehicle_paid',due='$total_due' WHERE date ='$date_insert' and Name='$choosen'");
echo" $due_topaid $to_pay_amm -- $update_vehicle_paid $update_vehicle_topay";
	}

}else{
	$update_vehicle_paid = $paid_vehicle + $due_topaid;
$update_vehicle_topay = $to_pay_vehicle + $to_pay_amm;
$total_due=$due_vehicle+$due_topaid+$paid;
		$insert3=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$update_vehicle_topay',paid='$update_vehicle_paid',due='$total_due' WHERE date ='$date_insert' and Name='$choosen'");
echo"$update_vehicle_paid $update_vehicle_topay   ($due_topaid, $to_pay_amm)";
}

		

}




if($status=="To be Payed"){
		$select13=mysqli_query($db,"SELECT to_be   FROM  store  WHERE location='$route'  and username='$username_uploader'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 $to_pay_amm = preg_replace('/[^0-9.]+/', '', $_POST['to_pay_amm']);
					$to_be_store =$get133['to_be']; 
					
$new_to_paid = $to_be_store - $to_pay_amm;


		


	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid' WHERE location='$route'  and username='$username_uploader'");


	}
}	
}
if($status=="paid"){
$select13=mysqli_query($db,"SELECT paid   FROM  store  WHERE location='$route' and username='$username_uploader' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$paid_store =$get133['paid']; 

$new_paid = $paid_store - $to_pay_amm;
	

	   $insert=mysqli_query($db,"UPDATE store SET paid='$new_paid' WHERE location='$route' and username='$username_uploader' ");


	}
}
}
if($status=="Due payment"){
	
			$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$route' and username='$username_uploader' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
$to_be_store =$get133['to_be']; 
$paid_store =$get133['paid']; 
$new_to_paid = $to_be_store - $to_pay_amm;
$new_paid = $paid_store - $due_topaid;

		


	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$route'  and username='$username_uploader'");

	}
}
}


$select_date=mysqli_query($db,"SELECT parcel_date   FROM  manifest_dates   WHERE  bus='$choosen' and parcel_date='$parcel_date' and route='$route' ");
$select_num_date=mysqli_num_rows($select_date);
if($select_num_date ==0){

$sqlQ = "INSERT INTO  manifest_dates  (trip_date,bus,parcel_date,route) VALUES (?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssss", $date_insert, $choosen,$parcel_date,$route); 
                $insert = $stmt->execute();	

}



}
}
?>