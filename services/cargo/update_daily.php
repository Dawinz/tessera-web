<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';

date_default_timezone_set('Africa/Dar_es_Salaam');
$month = date('m-Y', time());

$date_choose = date('Y-m-d', time());
			

	$next_date = date('Y-m-d', strtotime($date_choose. ' + 1 days'));
	$newDate = date("m/d/Y", strtotime($next_date));
	   $insert=mysqli_query($db,"UPDATE date_total SET date='$newDate'");

	?>
<?php

$select2=mysqli_query($db,"SELECT *  FROM  daily_charges  where status='' and month='$month'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $date_insert =$get12['date_insert'];  
    	    $location =$get12['location'];  

		$sql = $db->query("SELECT SUM(paid ) AS  paid,SUM(to_be ) AS  to_be  FROM parcel_info where   account='$location'and date_insert='$date_insert' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$sum_paid=$data['paid'];
				$sum_to_be=$data['to_be'];
			
		}}
			
		
	


$select_paid=mysqli_query($db,"SELECT * FROM parcel_info where  status='paid' and  account='$location'and date_insert='$date_insert' ");
$select_paid=mysqli_num_rows($select_paid);

$select_to_be=mysqli_query($db,"SELECT * FROM    parcel_info where  status='To be Payed' and  account='$location'and date_insert='$date_insert' ");
$select_to_be=mysqli_num_rows($select_to_be);

$select_due=mysqli_query($db,"SELECT * FROM   parcel_info where status='Due payment' and   account='$location'and date_insert='$date_insert'");
$select_due=mysqli_num_rows($select_due);	
	
	

		

	$insert=mysqli_query($db,"UPDATE daily_charges SET
	paid='$sum_paid',
	to_be='$sum_to_be',
	no_paid='$select_paid',
	no_to_be='$select_to_be',
	no_due='$select_due',
	status='read'
	WHERE  date_insert ='$date_insert' and  location='$location'");
	
	}
}



$select1=mysqli_query($db,"SELECT *  FROM  routes  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $location =$get13['route_full_name']; 
 



	$sql = $db->query("SELECT SUM(paid ) AS  paid,SUM(to_be ) AS  to_be  FROM parcel_info where  account='$location' and month='$month' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$sum_paid=$data['paid'];
				$sum_to_be=$data['to_be'];
			
		}}
		
$insert=mysqli_query($db,"UPDATE monthly_charges SET
paid='$sum_paid',
to_be='$sum_to_be'
WHERE  date_insert='$month' and location='$location'");


		$sql = $db->query("SELECT SUM(paid) AS  paid,SUM(to_be ) AS  to_be   FROM parcel_info where  store='store'  and  account='$location'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid =$data['paid'];
				$to_be =$data['to_be'];
				


		}}		
		$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain  FROM parcel_info where status='paid'  and store='half_out' and  account='$location'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_remain =$data['amount_remain'];

				


		}}
		$sql = $db->query("SELECT SUM(amount_remain) AS  amount_remain  FROM parcel_info where status='To be Payed'  and store='half_out' and  account='$location'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_remain_to_be =$data['amount_remain'];
				


		}}
		
		$paid_store = $paid - $amount_remain;
	$to_be_store = $to_be - $amount_remain_to_be;

$insert=mysqli_query($db,"UPDATE store SET
paid='$paid_store',
to_be='$to_be_store'
WHERE    location='$location'");

}}	
?>
	