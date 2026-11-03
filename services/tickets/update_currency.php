<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$currecy_name = $_POST['currecy_name'];
$Country = $_POST['Country'];
$fair = $_POST['fair'];
$fair  = preg_replace('/[^0-9.]+/', '', $fair);
$initial = $_POST['initial'];
$currency_status = $_POST['currency_status'];
$id_no = $_POST['id_no'];

$select1=mysqli_query($db,"SELECT *  FROM  currency WHERE id='$id_no' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status']; 

}
if($status == $currency_status){
$update=mysqli_query($db,"UPDATE  currency SET name='$currecy_name',status='$currency_status',rate='$fair',initial='$initial',country='$Country' WHERE id='$id_no'");
echo "succesfull";
}else{
	echo "change";
}

   	}else{


	}



 
  ?>
