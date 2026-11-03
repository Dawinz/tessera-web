<?php
session_start();
$user_charge=$_SESSION['username']
?>
<?php
include'connect.php';
$loan=0;
$firstname = $_POST['firstname'];
$s_no = $_POST['s_no'];
$r_firstname = $_POST['r_firstname'];
$r_no = $_POST['r_no'];
$trip = $_POST['trip'];
$amount= $_POST['amount'];
$loan= @$_POST['loan'];
$loan = preg_replace('/[^0-9.]+/', '', $loan);
$amount = preg_replace('/[^0-9.]+/', '', $amount);
$status_em = $_POST['status_em'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);


$question = $_POST['question'];
$due_amount2 = $_POST['due_amount2'];
$due_amount2 = preg_replace('/[^0-9.]+/', '', $due_amount2);

echo $due_amount2;

$id_update = $_POST['id_update'];

$select12=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE id='$id_update' ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get12=mysqli_fetch_array($select12)){
    	   
 $code=$get12['code']; 
 $r_name=$get12['r_name'];
 $quantity=$get12['question'];
 $id=$get12['id'];
 $username=$get12['username'];
 $amount_orginal=$get12['amount']; 
 $uploader=$get12['uploader']; 
 $sender_name=$get12['s_name']; 
 $s_no_original=$get12['s_no'];
 $r_no_original=$get12['r_no'];
 $trip_orginal=$get12['account']; 
 $status=$get12['status'];  
 $due_org=$get12['due']; 
 $to_be_org=$get12['to_be']; 
 $paid_org=$get12['paid']; 
 $date_insert_org=$get12['date_insert']; 
 $month_org=$get12['month']; 
	
$detail_org = $_POST['detail'];
$detail_org = base64_decode($detail_org);
		
	}
}

/////
////////////////
//code//
//////////



date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date(' h:i A', time());
$month = date('m-Y', time());

$select=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num ==0){
	$a=array();


if($trip==$trip_orginal){	
	
}else{
	$push_firstname = " <strong>Trip location</strong><br> from $trip_orginal to  $trip,";
array_push($a,"".$push_firstname."",);
 
}if($firstname==$sender_name){
	
}else{
	$push_firstname = "<strong>Sender name</strong> <br> $sender_name to  $firstname ,";
array_push($a,"".$push_firstname."",);
 
}
if($r_name==$r_firstname){
	
}else{
	$push_firstname = "<strong>Reciever name</strong> <br> $r_firstname to  $r_name,";
array_push($a,"".$push_firstname."",);

}
if($s_no==$s_no_original){
	
}else{
	$push_firstname = "<strong>Sender no</strong> <br> $s_no_original to  $s_no,";
array_push($a,"".$push_firstname."",);

}if($r_no==$r_no_original){
	
}else{
	$push_firstname = "<strong>Reciever no</strong> <br> $r_no_original to  $r_no,";
array_push($a,"".$push_firstname."",);

}if($trip_orginal==$trip){
	
}else{
	$push_firstname = "<strong>Route</strong> <br> $trip_orginal to  $trip,";
array_push($a,"".$push_firstname."",);

}if($status_em==$status){
	
}else{
	
if($status_em=="To be Payed"){
	
	
	
	
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
$new_paid = $paid - $paid_org;
		
if($status=="Due payment"){
	$new_to_paid = $to_be - $to_be_org + $amount_orginal;

		   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip'");

}else{
	$new_to_paid = $to_be + $amount_orginal;

			   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip'");

}

	}
}
	
$select134=mysqli_query($db,"SELECT to_be,paid   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be']; 
					$paid1 =$get1334['paid']; 

$new_paid1= $paid1 - $paid_org;
		
if($status=="Due payment"){
	$new_to_paid1 =  $to_be1 - $to_be_org + $amount_orginal;
$insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1' WHERE date_insert = '$date_insert' and location='$trip'");

}else{
	$new_to_paid1 = $to_be1 + $paid_org;
	$new_paid1= $paid1 - $paid_org;
$insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1' WHERE date_insert = '$date_insert' and location='$trip'");

}


	}
}
$select1346=mysqli_query($db,"SELECT to_be,paid,no_to_be,no_paid    FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' ");
$select_num1346=mysqli_num_rows($select1346);
if($select_num1346 !=0){
   	WHILE($get13346=mysqli_fetch_array($select1346)){
    	 
					$to_be2 =$get13346['to_be']; 
					$paid2 =$get13346['paid']; 
					$no_to_be =$get13346['no_to_be'];
					$no_paid =$get13346['no_paid']; 
					
				
$new_to_paid2 = $to_be2 + $amount_orginal;
$new_paid2= $paid2 - $paid_org;
		
if($status=="Due payment"){
	$new_to_paid2 = $to_be2 - $to_be_org + $amount_orginal;
$insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2' WHERE date_insert = '$month' and location='$trip'");
	
}else{
	$new_paid2= $paid2 - $paid_org;
$insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2' WHERE date_insert = '$month' and location='$trip'");
	
}


	}
}

}if($status_em=="paid"){
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
$new_to_paid = $to_be - $amount_orginal;

		
if($status=="Due payment"){
	$new_paid = $paid - $paid_org + $amount_orginal;
		   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip'");

}else{
	$new_paid = $paid + $amount_orginal;
		   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip'");

}


	}
}	
	$select134=mysqli_query($db,"SELECT to_be,paid   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be']; 
					$paid1 =$get1334['paid']; 
$new_to_paid1 = $to_be1 - $amount_orginal;

		
		if($status=="Due payment"){
				$new_paid1 = $paid1 - $paid_org + $amount_orginal;
				   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1' WHERE date_insert = '$date_insert' and  location='$trip'");

		}else{
					$new_paid1 = $paid1 + $amount_orginal;

		   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1' WHERE date_insert = '$date_insert' and  location='$trip'");
		
		}
		



	}
}	
$select1345=mysqli_query($db,"SELECT to_be,paid   FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$to_be2 =$get13345['to_be']; 
					$paid2 =$get13345['paid']; 
$new_to_paid2 = $to_be2 - $amount_orginal;
$new_paid2 = $paid2 + $amount_orginal;
		
if($status=="Due payment"){
	$new_to_paid2 = $to_be2 - $to_be_org;
$new_paid2 = $paid2 + $paid_org;
	$new_paid2 = $paid2 - $paid_org + $amount_orginal;
$insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2' WHERE date_insert = '$month' and  location='$trip'");

}else{
$insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2' WHERE date_insert = '$month' and  location='$trip'");

}


	}
}	
}if($status_em=="Due payment"){
	$due_total= $amount-$due_amount2;
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
$new_to_paid = $to_be - $to_be_org;
$new_paid = $paid - $paid_org;
$new_to_paid_total = $new_to_paid + $due_total;
$new_paid_total = $new_paid + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid_total',paid='$new_paid_total' WHERE location='$trip'");


	}
}	

$select134=mysqli_query($db,"SELECT to_be,paid   FROM   daily_charges  WHERE date_insert = '$date_insert' and  location='$trip'  ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be']; 
					$paid1 =$get1334['paid']; 
$new_to_paid1 = $to_be1 - $to_be_org;
$new_paid1 = $paid1 - $paid_org;
$new_to_paid_total1 = $new_to_paid1 + $due_total;
$new_paid_total1 = $new_paid1 + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid_total1',paid='$new_paid_total1' WHERE date_insert = '$date_insert' and location='$trip'");


	}
}
$select1345=mysqli_query($db,"SELECT to_be,paid   FROM   monthly_charges  WHERE date_insert = '$month' and  location='$trip'  ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$to_be2 =$get13345['to_be']; 
					$paid2 =$get13345['paid']; 
$new_to_paid2 = $to_be2 - $to_be_org;
$new_paid2 = $paid2 - $paid_org;
$new_to_paid_total2 = $new_to_paid2 + $due_total;
$new_paid_total2 = $new_paid2 + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid_total2',paid='$new_paid_total2' WHERE date_insert = '$month' and location='$trip'");


	}
}	
}	
	$push_firstname = "<strong>Payment status</strong> <br>$status_em to  $status,";
array_push($a,"".$push_firstname."",);





}if($amount_orginal==$amount){
	
}else{
	
	if($status_em=="To be Payed"){
	
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
			
$old_to_paid = $to_be - $amount_orginal;
$new_am_to_be = $old_to_paid + $amount;

		

	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_am_to_be'WHERE location='$trip'");


	}
}	

$select135=mysqli_query($db,"SELECT to_be   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	 
					$to_be2 =$get1335['to_be']; 
			
$old_to_paid2 = $to_be2 - $amount_orginal;
$new_am_to_be2 = $old_to_paid2 + $amount;

		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_am_to_be2' WHERE date_insert = '$date_insert' and location='$trip'");


	}
}
$select1356=mysqli_query($db,"SELECT to_be   FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' ");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
    	 
					$to_be3 =$get13356['to_be']; 
			
$old_to_paid3 = $to_be3 - $amount_orginal;
$new_am_to_be3 = $old_to_paid3 + $amount;

		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_am_to_be3' WHERE date_insert = '$month' and location='$trip'");


	}
}

}
if($status_em=="paid"){
		$select13=mysqli_query($db,"SELECT paid   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
		$paid =$get133['paid']; 
			
$old_paid = $paid - $amount_orginal;
$new_am_paid = $old_paid + $amount;
		

	   $insert=mysqli_query($db,"UPDATE store SET paid='$new_am_paid' WHERE location='$trip'");


	}
}	
$select135=mysqli_query($db,"SELECT paid   FROM daily_charges where date_insert = '$date_insert' and  location='$trip' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	 
		$paid2 =$get1335['paid']; 
			
$old_paid2 = $paid2 - $amount_orginal;
$new_am_paid2 = $old_paid2 + $amount;
		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_am_paid2' WHERE date_insert = '$date_insert' and location='$trip'");


	}
}
$select1356=mysqli_query($db,"SELECT paid   FROM monthly_charges where date_insert = '$month' and  location='$trip' ");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
    	 
		$paid3 =$get13356['paid']; 
			
$old_paid3 = $paid3 - $amount_orginal;
$new_am_paid3 = $old_paid3 + $amount;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_am_paid3' WHERE date_insert = '$month' and location='$trip'");


	}
}	
}	

if($status_em=="Due payment"){
	$due_total= $amount-$due_amount2;
		$select13=mysqli_query($db,"SELECT to_be,paid   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
$new_to_paid = $to_be - $to_be_org;
$new_paid = $paid - $paid_org;
$new_to_paid_total = $new_to_paid + $due_total;
$new_paid_total = $new_paid + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid_total',paid='$new_paid_total' WHERE location='$trip'");


	}
}	
$select135=mysqli_query($db,"SELECT to_be,paid   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip'");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	 
					$to_be2 =$get1335['to_be']; 
					$paid2 =$get1335['paid']; 
$new_to_paid2 = $to_be2 - $to_be_org;
$new_paid2 = $paid2 - $paid_org;
$new_to_paid_total2 = $new_to_paid2 + $due_total;
$new_paid_total2 = $new_paid2 + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid_total2',paid='$new_paid_total2' WHERE date_insert = '$date_insert' and location='$trip'");


	}
}	
$select1356=mysqli_query($db,"SELECT to_be,paid   FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
    	 
					$to_be3 =$get13356['to_be']; 
					$paid3 =$get13356['paid']; 
$new_to_paid3 = $to_be3 - $to_be_org;
$new_paid3 = $paid3 - $paid_org;
$new_to_paid_total3 = $new_to_paid3 + $due_total;
$new_paid_total3 = $new_paid3 + $due_amount2;
		


	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid_total3',paid='$new_paid_total3' WHERE date_insert = '$month' and location='$trip'");


	}
}	


}
	
	$push_firstname = " <strong>Ammount</strong><br> $amount_orginal to  $amount,";
array_push($a,"".$push_firstname."",);

}if($quantity==$question){
	
}else{
	$push_firstname = "<strong>Quantity</strong> <br> $quantity to  $question,";
array_push($a,"".$push_firstname."",);

}if((base64_encode($detail_org))==(base64_decode($detail))){
	
}else{
	
	
	$push_firstname = "<strong>Details</strong> <br>".base64_encode($detail_org)." to ". base64_decode($detail).",";
array_push($a,"".$push_firstname."",);

}
if($due_org==$due_amount2){
	
}else{
	$push_firstname = "<strong>Due Ammout</strong> <br> $due_amount2 to  $due_org,";
array_push($a,"".$push_firstname."",);

}
$update_area =  implode($a);
if($status_em == "Due payment"){
	$due_total= $amount-$due_amount2;
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='$due_amount2',s_surname='$due_total',to_be='$due_total',paid='$due_amount2',answer='$due_total',payment_status='$due_amount2',r_surname='Edited by $user_charge',updated='changed',loan='$loan'  WHERE id ='$id_update'");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,topay,amount,uploader,date,username,notification,what_changed) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change','$update_area')");

}else if ($status_em == "To be Payed"){
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='',s_surname='',to_be='$amount',paid='0',r_surname='Edited by $user_charge',answer='0',payment_status='0',updated='changed',loan='$loan'  WHERE id ='$id_update'");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,topay,amount,uploader,date,username,notification,what_changed) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change','$update_area')");
}else if ($status_em == "paid"){
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='',s_surname='',to_be='0',paid='$amount',r_surname='Edited by $user_charge',answer='0',payment_status='0',updated='changed',loan='$loan'  WHERE id ='$id_update'");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,paid,amount,uploader,date,username,notification,what_changed) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change','$update_area')");
}


$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE id='$id_update' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['code']; 
	

		
	}
}
}else{
	echo "found";
}

?>