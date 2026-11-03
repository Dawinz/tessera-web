<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info where store='half_out'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['month']; 
			$qty =$get13['question']; 
			$to_be =$get13['to_be']; 
			$paid =$get13['paid']; 
			$code =$get13['code']; 

     
 $select_manifest=mysqli_query($db,"SELECT SUM(q_left) AS total_sum FROM  manifest    where code='$code'");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
   	WHILE($get_manifest=mysqli_fetch_array($select_manifest)){
    	 
    	    $total_sum =$get_manifest['total_sum']; 
	}}

echo "$code($total_sum - $qty)--$select_num_manifest<br> ";
if(($qty == $total_sum) OR ($total_sum > $qty)){
	    		 mysqli_query($db,"UPDATE parcel_info  SET  store ='out' WHERE  code ='$code' ");

}

    }}

/*
$select1=mysqli_query($db,"SELECT *  FROM  parcel_info where paid_remain='0' and to_be_remain='0'  and quantity_remain='0' limit  100 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['month']; 
			$qty =$get13['question']; 
			$to_be =$get13['to_be']; 
			$paid =$get13['paid']; 
			$code =$get13['code']; 

    		 $insert=mysqli_query($db,"UPDATE parcel_info  SET  paid_remain ='$paid',to_be_remain ='$to_be',quantity_remain ='$qty' WHERE  code ='$code' ");
        
    }}
             */
 

    /* 
 $select_manifest=mysqli_query($db,"SELECT *  FROM  manifest    ");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
   	WHILE($get_manifest=mysqli_fetch_array($select_manifest)){
    	    $id =$get_manifest['id']; 
    	    $code =$get_manifest['code']; 
    	    $qty  =$get_manifest['quantity_insert']; 
    	    $to_be  =$get_manifest['to_be']; 
    	    $paid  =$get_manifest['paid']; 
		

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info where code='$code'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $scanned=$get13['scanned'];  
		  $amount=$get13['amount'];  
		  $paid_remain=$get13['paid_remain'];  
		  $to_be_remain=$get13['to_be_remain'];  
		  $quantity_remain=$get13['quantity_remain'];  
			$code =$get13['code']; 
        
    }}

$parcel_to_be = $to_be_remain - $to_be;
$parcel_paid = $paid_remain - $paid;
$parcel_scan =   $scanned - $qty;
$parcel_total_amount = $parcel_paid + $parcel_to_be;
$total_amount = $to_be + $paid;
if($parcel_scan ==0){
    echo "$id =0 <br>";
$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$parcel_scan',quantity_remain='$parcel_scan',store='out',paid_remain='$parcel_paid',to_be_remain='$parcel_to_be' WHERE code ='$code'");
}else{
        echo "not zero<br>";

$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$parcel_scan',quantity_remain='$parcel_scan',store='half_out',paid_remain='$parcel_paid',to_be_remain='$parcel_to_be' WHERE code ='$code'");

}

    }}
*/
?>