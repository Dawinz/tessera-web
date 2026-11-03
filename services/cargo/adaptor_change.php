<?php
session_start();
?>
<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert2 = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i:a', time());

include 'connect.php';
$id = $_POST['id'];
echo $id;



$select=mysqli_query($db,"SELECT *  FROM parcel_info WHERE id='$id' ");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $quantity=$get['question'];
			$date=$get['date']; 
			$trip=$get['account'];
			$s_no=$get['s_no']; 
			$s_name=$get['s_name'];
			$s_surname=$get['s_surname'];
			$r_name=$get['r_name'];
			$r_surname=$get['r_surname']; 	
			$r_no=$get['r_no'];
			$cost=$get['amount'];
			$due=$get['due']; 	
			$date_insert=$get['date_insert']; 
			$quantity=$get['question']; 
			$detail=$get['detail']; 
			$code=$get['code']; 
			$paid=$get['paid']; 
			$to_be=$get['to_be']; 
			$id=$get['id']; 
}}


$select2=mysqli_query($db,"SELECT *  FROM id WHERE id2='$id' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 ==0){
	$insert=mysqli_query($db,"INSERT INTO id (code,fullname,paid,tobe,total,date_change,date_insert,time,id2) 
VALUES                                    ('$code','$r_name','$paid','$to_be','$cost','$date_insert','$date_insert2','$date_time_insert','$id')");

$insert2=mysqli_query($db,"UPDATE parcel_info SET open='yes'  WHERE id = '$id'");
}else{
	$insert2=mysqli_query($db,"UPDATE parcel_info SET open=''  WHERE id = '$id'");
 $insert=mysqli_query($db,"DELETE from id WHERE id2 = '$id'");
	
}


?>