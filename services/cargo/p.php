<?php
session_start();
?>
<?php
include 'connect.php';
$id = $_POST['id'];
$code = $_POST['code'];
$noid = $_POST['noid'];

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$date_time_insert = date(' h:i A', time());

$select2=mysqli_query($db,"SELECT *  FROM  manifest WHERE id='$id' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
 	WHILE($get12=mysqli_fetch_array($select2)){
			$code=$get12['code'];
			$q_left=$get12['q_left'];
		$last=$get12['last_left'];
		$to_be=$get12['to_be'];
		$scanned=$get12['scanned'];
		$paid=$get12['paid'];
		$bus=$get12['bus'];
		$trip_date=$get12['trip_date'];

		
	}
	$select23=mysqli_query($db,"SELECT * FROM parcel_info WHERE code='$code' ");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
    	    $q_left2 =$get123['scanned']; 
$id_parcel =$get123['id']; 
$amount_remain = $get123['amount_remain']; 
$quantity = $get123['question']; 
$status = $get123['status'];
$paid_due = $get123['payment_status']; 
$tobe_due  = $get123['answer'];

		
	}
}
$select233=mysqli_query($db,"SELECT * FROM `manifest` WHERE code='$code' ");
$select_num233=mysqli_num_rows($select233);
if($select_num233 !=0){
	$last_no =  $q_left - $last;
	$edited_results = $last_no + $q_left2;
	$total_amount_remain= $to_be + $amount_remain;
	$total_amount_remain_paid= $paid + $amount_remain;
	
	$total_due_remain_to_be_paid= $to_be + $tobe_due;
	$total_due_remain_paid= $paid + $paid_due;
	$due_amount_remain =  $amount_remain + $paid + $to_be;
	
	$insert2=mysqli_query($db,"UPDATE notification SET status='manifest deleted' ,deleted='".$_SESSION['username']."',deleted_date='$date_insert at $date_time_insert'  WHERE code = '$code' and id='$noid'");
	$insert=mysqli_query($db,"DELETE from manifest WHERE id = '$id'");
	if($select_num233==1){
		if($status=="paid"){
							$insert2=mysqli_query($db,"UPDATE parcel_info SET scanned='$edited_results',amount_remain='$total_amount_remain_paid',store='store'  WHERE code = '$code'");

		}else if($status=="To be Payed"){
						$insert2=mysqli_query($db,"UPDATE parcel_info SET scanned='$edited_results',amount_remain='$total_amount_remain',store='store'  WHERE code = '$code'");
	
		}
		else if($status=="Due payment"){
								$insert2=mysqli_query($db,"UPDATE parcel_info SET amount_remain='0',scanned='$edited_results',payment_status='$total_due_remain_to_be_paid',answer='$total_due_remain_paid',store='store'  WHERE code = '$code'");


	}else{
		if($status=="paid"){
						$insert2=mysqli_query($db,"UPDATE parcel_info SET scanned='$edited_results',amount_remain='$total_amount_remain_paid',store='half_out'  WHERE code = '$code'");
	
		}else if($status=="To be Payed"){
			
									$insert2=mysqli_query($db,"UPDATE parcel_info SET scanned='$edited_results',amount_remain='$total_amount_remain',store='half_out'  WHERE code = '$code'");

		}else if($status=="Due payment"){
	$insert2=mysqli_query($db,"UPDATE parcel_info SET amount_remain='$due_amount_remain',scanned='$edited_results',payment_status='$total_due_remain_to_be_paid',answer='$total_due_remain_paid',store='half_out'  WHERE code = '$code'");

	}
	
	}
	}
echo "deleted";
}else{
	
}

}else{
	echo"found";
}

?>