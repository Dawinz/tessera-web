<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
?>
<?php


include'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  office_expenses ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $code_plus =$get13['stock_no']; 
		$code_plus = $code_plus + 1;

		
	}
}
$select12=mysqli_query($db,"SELECT *  FROM  expenses ORDER BY id DESC LIMIT 1 ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
    	    $id =$get132['id']; 
			$amount_curr =$get132['amount_curr']; 
			$approved =$get132['approved'];
			$date =$get132['date'];
			$detail =$get132['detail']; 
			$amount =$get132['amount']; 
			$username =$get132['username']; 
			$time =$get132['time']; 
			$stock_no =$get132['stock_no'];
			$stock_amount =$get132['stock_amount']; 
			$amount_remain =$get132['amount_remain']; 
			$v_no =$get132['code']; 
			$v_no = $v_no + 1;

		
	}
}
$title = $_POST['title'];
$time = $_POST['time'];
$amount = $_POST['amount'];
$amount  = preg_replace('/[^0-9.]+/', '', $amount);
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$date_time_insert = date(' h:i A', time());
$currend_ammont = $amount - $amount_curr;


if($amount_curr==0){
		  $insert=mysqli_query($db,"INSERT INTO office_expenses VALUES('','$amount','".$_SESSION['username']."','$date_insert','$date_time_insert','$title','$time','$code_plus','','open')");

}else{
		  $insert=mysqli_query($db,"INSERT INTO office_expenses VALUES('','$amount','".$_SESSION['username']."','$date_insert','$date_time_insert','$title','$time','$code_plus','$currend_ammont','open')");
   $update2=mysqli_query($db,"UPDATE expenses SET amount_remain='' WHERE id='$id'");
 $insert=mysqli_query($db,"INSERT INTO expenses VALUES('','$approved','re','$detail','$amount_curr','','".$_SESSION['username']."','$date_time_insert','$date_insert','$v_no','$code_plus','$amount','$currend_ammont')");
   $update12=mysqli_query($db,"UPDATE office_expenses SET amount_remain='' WHERE id='$stock_no'");
}

echo "";

	
    	  

?>