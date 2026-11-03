<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
session_start();

$select1=mysqli_query($db,"SELECT *  FROM  expenses ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['code']; 
		$code_plus = $code_plus + 1;

		
	}
}
@$title = $_POST['title'];
@$date = $_POST['date'];
@$v_no = $_POST['v_no'];
@$amount_remain = $_POST['amount_remain'];
@$cash_hand = $_POST['cash_hand'];
@$detail = $_POST['detail'];
@$detail = base64_encode($detail);
@$amount = $_POST['amount'];
$amount = preg_replace('/[^0-9.]+/', '', $amount);
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$date_time_insert = date(' h:i A', time());

$select2=mysqli_query($db,"SELECT *  FROM  expenses WHERE stock_no='$v_no'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
	if($amount_remain < $amount){
		$balance = $amount - $amount_remain;
			$amount_remain_list =   $amount_remain - $amount;
		  $insert=mysqli_query($db,"INSERT INTO expenses VALUES('','$title','$date','$detail','$balance','$balance','".$_SESSION['username']."','$date_time_insert','$date_insert','$code_plus','$v_no','','')");
   $update=mysqli_query($db,"UPDATE office_expenses SET amount_remain='$amount_remain_list' WHERE stock_no ='$v_no'");
   $update2=mysqli_query($db,"UPDATE expenses SET amount_remain='$amount_remain_list' WHERE stock_no ='$v_no' and stock_amount!='0'");
	}else{
		$amount_remain_list = $amount_remain - $amount;
		  $insert=mysqli_query($db,"INSERT INTO expenses VALUES('','$title','$date','$detail','$amount','','".$_SESSION['username']."','$date_time_insert','$date_insert','$code_plus','$v_no','','')");
   $update=mysqli_query($db,"UPDATE office_expenses SET amount_remain='$amount_remain_list' WHERE stock_no ='$v_no'");
   $update2=mysqli_query($db,"UPDATE expenses SET amount_remain='$amount_remain_list' WHERE stock_no ='$v_no' and stock_amount!='0'");	
	}


}else{
	$amount_remain_list = $cash_hand - $amount;
		  $insert=mysqli_query($db,"INSERT INTO expenses VALUES('','$title','$date','$detail','$amount','','".$_SESSION['username']."','$date_time_insert','$date_insert','$code_plus','$v_no','$cash_hand','$amount_remain_list')");
   $update=mysqli_query($db,"UPDATE office_expenses SET amount_remain='$amount_remain_list' WHERE stock_no ='$v_no'");

}
echo "$code_plus";
	
    	  

?>