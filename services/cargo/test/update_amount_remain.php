<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info where to_be_remain > to_be ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
    	    $to_be_remain =$get13['to_be_remain']; 
    	    $to_be =$get13['to_be']; 
echo " $to_be - $to_be_remain <br>";
	 $insert=mysqli_query($db,"UPDATE parcel_info  SET  to_be_remain ='$to_be' WHERE  id ='$id' ");
    }}

/*
    $select1=mysqli_query($db,"SELECT *  FROM  parcel_info where paid_remain > paid ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
    	    $paid_remain =$get13['paid_remain']; 
    	    $paid =$get13['paid']; 
echo " $paid - $paid_remain <br>";
 $insert=mysqli_query($db,"UPDATE parcel_info  SET  paid_remain ='$paid' WHERE  id ='$id' ");
    }}
 */
    ?>