<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$code =$_POST['code'];
$scanned = $_POST['scanned'];
$amount = $_POST['amount'];
$number= $_POST['number'];
$choosen = $_POST['choosen'];

if($scanned  == 0){
	echo "exist";
}else{
$time_remain = $_POST['time_remain'];
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    
		  $scanned=$get13['scanned'];  
		  $amount=$get13['amount'];  
		  $s_q= $get13['scanned'];
		  $quantity= $get13['question']; 
		  $amount_remain= $get13['amount_remain'];
		  $status= $get13['status'];
		   $due= $get13['s_surname'];
	}
}
if($status == "Due payment"){
$quantity_scanned= $scanned - $number;
$adding= $scanned + $number;
		@$scanned_remain = $due / $quantity;
		@$entire_remain = $scanned_remain * $number;
		$totol_remain=  $amount_remain - $entire_remain;
$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$adding',store='out',bus='$choosen' WHERE code ='$code'");
echo "$number";	
}else{
$quantity_scanned= $scanned - $number;
$adding= $scanned + $number;
		@$scanned_remain = $amount / $quantity;
		@$entire_remain = $scanned_remain * $number;
		$totol_remain=  $amount_remain - $entire_remain;
$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$adding',store='out',bus='$choosen' WHERE code ='$code'");
echo "$number";	
}

}

?>