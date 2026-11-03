<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
session_start();
$v_no = 0;
$select1=mysqli_query($db,"SELECT *  FROM  manifest_exp ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $v_no =$get13['v_no']; 
		$v_no = $v_no + 1;

		
	}
}

@$status2 = $_POST['status2'];
@$trip_date = $_POST['trip_date'];
@$vehicle = $_POST['vehicle'];
@$amount = $_POST['amount'];
@$detail = $_POST['detail'];
@$detail = base64_encode($detail);
$amount = preg_replace('/[^0-9.]+/', '', $amount);
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$date_time_insert = date(' h:i A', time());





$select12=mysqli_query($db,"SELECT *  FROM  expenses_list WHERE name='$status2' ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
    	    $name =$get132['name'];
			$type =$get132['type']; 
		

		
	}
}

								$sql = $db->query("SELECT SUM(paid) AS  paid FROM vehicle_name   where   date >= '$trip_date' and Name='$vehicle'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_paid=$data['paid'];
				
							
			
		}}else{
			
		}

									$sql = $db->query("SELECT SUM(insert_amount) AS  insert_amount FROM manifest_exp   where   trip_date >= '$trip_date' and bus='$vehicle'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_exp=$data['insert_amount'];
				
			
		}}else{
		
		}
$gross_amount = $total_paid - $amount_exp;

$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name WHERE date='$trip_date' and Name='$vehicle'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   $total=$get12['due']; 
		   $to_be=$get12['to_pay']; 
		   $paid=$get12['paid'];
		   
		   $select22=mysqli_query($db,"SELECT *  FROM  manifest_exp WHERE trip_date='$trip_date' and bus='$vehicle'");
$select_num22=mysqli_num_rows($select22);
if($select_num22 !=0){
		  $insert2=mysqli_query($db,"INSERT INTO manifest_exp VALUES('','$vehicle','$trip_date','','$amount','','','','income','$detail','$date_insert','$v_no','".$_SESSION['username']."','$name','$type','$date_time_insert')");

 }else{
	 	  $inser2t=mysqli_query($db,"INSERT INTO manifest_exp VALUES('','$vehicle','$trip_date','$total','$amount','$to_be','$paid','$amount','income','$detail','$date_insert','$v_no','".$_SESSION['username']."','$name','$type','$date_time_insert')");

 }
		   
	echo "$v_no";
}}
	


?>