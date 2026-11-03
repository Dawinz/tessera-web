<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
$user_charge=$_SESSION['username']
?>
<?php
include'connect.php';
$firstname = $_POST['firstname'];
$s_no = $_POST['s_no'];
$r_firstname = $_POST['r_firstname'];
$r_no = $_POST['r_no'];
$trip = $_POST['trip'];
$amount= $_POST['amount'];
$status_em = $_POST['status_em'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);

$status_em = $_POST['status_em'];
$question = $_POST['question'];
$due_amount2 = $_POST['due_amount2'];
$id_update = $_POST['id_update'];

$select12=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE id='$id_update' ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get12=mysqli_fetch_array($select12)){
    	    $due=$get12['due']; 
 $code=$get12['code']; 
 $r_name=$get12['r_name'];
 $quantity=$get12['question'];
 $id=$get12['id'];
 $username=$get12['username'];
 $amount=$get12['amount']; 
 $uploader=$get12['uploader']; 
	

		
	}
}
/////
////////////////
//code//
//////////

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$date_time_insert = date(' h:i A', time());


$select=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num ==0){



if($status_em == "Due payment"){
	$due_total= $amount-$due_amount2;
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='$due_amount2',s_surname='$due_total',to_be='$due_total',paid='$due_amount2',answer='$due_total',payment_status='$due_amount2',r_surname='Edited by $user_charge',updated='changed'  WHERE id ='$id_update'");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,topay,amount,uploader,date,username,notification) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change')");

}else if ($status_em == "To be Payed"){
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='',s_surname='',to_be='$amount',paid='0',r_surname='Edited by $user_charge',answer='0',payment_status='0',updated='changed'  WHERE id ='$id_update'");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,topay,amount,uploader,date,username,notification) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change')");
}else if ($status_em == "paid"){
	   $insert=mysqli_query($db,"UPDATE parcel_info SET s_name='$firstname',s_no='$s_no',r_name='$r_firstname',r_no='$r_no',account='$trip',detail='$detail',status='$status_em',amount = '$amount',question = '$question',scanned='$question',edited='".$_SESSION['username']."',due='',s_surname='',to_be='0',paid='$amount',r_surname='Edited by $user_charge',answer='0',payment_status='0',updated='changed'  WHERE id ='$id_update'");
$insert=mysqli_query($db,"INSERT INTO notification 
(status,deleted,deleted_date,s_name,r_name,s_id,code,quantity,paid,amount,uploader,date,username,notification) VALUES 
('Updated','".$_SESSION['username']."','$date_insert at $date_time_insert','$firstname ','$r_name','$id','$code','$quantity','$amount','$amount','$uploader','$date_insert','$username','Change')");
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