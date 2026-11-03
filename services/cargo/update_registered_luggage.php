<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$user_charge=$_SESSION['username']
?>
<?php
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
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
$id_update = $_POST['id_update'];
$weight_name = $_POST['weight_name'];
$weight_value = $_POST['weight_value'];
$luggage_value = $_POST['luggage_value'];

$select12=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE id='$id_update' AND " . tessera_sql_tenant_eq() . "");
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
 $print_code=$get12['print_code']; 
	
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

$select=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' AND " . tessera_sql_tenant_eq() . "");
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

$push_firstname = "<strong>Payment status</strong> <br>$status_em to  $status,";
array_push($a,"".$push_firstname."",);
}if($amount_orginal==$amount){
	
	if($status_em =="Due payment"){
		include 'update_amount_due.inc.php';
	}
	
}else{
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
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='$due_amount2',s_surname='$due_total',to_be='$due_total',paid='$due_amount2',answer='$due_total',payment_status='$due_amount2',r_surname='Edited by $user_charge',updated='changed',loan='$loan',paid_remain='$due_amount2' , to_be_remain='$due_total' , quantity_remain='$question',weight_name='$weight_name',weight_value='$weight_value',lugg_value='$luggage_value'  WHERE id ='$id_update' AND " . tessera_sql_tenant_eq() . "");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,topay,amount,uploader,date,username,notification,what_changed) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change','$update_area')");

}else if ($status_em == "To be Payed"){
	
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='',s_surname='',to_be='$amount',paid='0',r_surname='Edited by $user_charge',answer='0',payment_status='0',updated='changed',loan='$loan',to_be_remain='$amount' ,paid_remain='0', quantity_remain='$question',weight_name='$weight_name',weight_value='$weight_value',lugg_value='$luggage_value'  WHERE id ='$id_update' AND " . tessera_sql_tenant_eq() . "");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,topay,amount,uploader,date,username,notification,what_changed) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change','$update_area')");
}else if ($status_em == "paid"){
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='',s_surname='',to_be='0',paid='$amount',r_surname='Edited by $user_charge',answer='0',payment_status='0',updated='changed',loan='$loan',paid_remain='$amount' ,to_be_remain='0', quantity_remain='$question',weight_name='$weight_name',weight_value='$weight_value',lugg_value='$luggage_value' WHERE id ='$id_update' AND " . tessera_sql_tenant_eq() . "");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,paid,amount,uploader,date,username,notification,what_changed) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change','$update_area')");
}


$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE id='$id_update' AND " . tessera_sql_tenant_eq() . "");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['code']; 
	

		
	}
}
echo $print_code;
}else{
	echo "found";
}

?>