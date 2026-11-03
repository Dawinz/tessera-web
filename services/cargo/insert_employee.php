<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
?>
<?php

if(isset($_SESSION['username'])){
	

include'connect.php';

$select1=mysqli_query($db,"SELECT MAX(CAST(code AS UNSIGNED)) FROM parcel_info  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['MAX(CAST(code AS UNSIGNED))']; 
		$code_plus = $code_plus + 1;

		
	}
}
include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM  users WHERE username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $fullname =$get12['fullname']; 
	

	
	}
}
$firstname = $_POST['firstname'];
$s_no = $_POST['s_no'];
$r_firstname = $_POST['r_firstname'];
$r_no = $_POST['r_no'];
$trip = $_POST['trip'];
$amount= $_POST['amount'];
$status_em = $_POST['status_em'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);
$question = $_POST['question'];
$answer = $_POST['answer'];
$answer = preg_replace('/[^0-9.]+/', '', $answer);
$amount = preg_replace('/[^0-9.]+/', '', $amount);
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$month = date('m/Y', time());
$date_time_insert = date(' h:i A', time());
//CHECK FOR PAYED STATUS
////////////////////////////
/////////////////////////////
$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE code='$code_plus'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
echo"equal";
}else{
if($status_em=="paid"){
	if($answer==""){
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','$amount','','$month','')");
echo $code_plus;	
}else{
	$due_total= $amount-$answer;
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','$due_total','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','$amount','','$month','')");
echo $code_plus;	
}
}else if($status_em=="To be Payed"){
	if($answer==""){
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','','$amount','$month','')");
echo $code_plus;	
}else{
	$due_total= $amount-$answer;
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','$due_total','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','','$amount','$month','')");
echo $code_plus;	
}	
}else if($status_em=="Due payment"){
	if($answer==""){
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','','','$month','')");
echo $code_plus;	
}else{
	$due_total= $amount-$answer;
	$to_be_paid= $due_total + $answer ;
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','$due_total','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','$answer','No','$question','$due_total','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','$answer','$due_total','$month','')");
echo $code_plus;	
}	
}
}
}else{
	echo "expire";
}

///////////////////////////
//////CLOSING PAY STATUS///
////////////////////////////

 
?>