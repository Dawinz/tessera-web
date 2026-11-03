<?php
include 'connect.php';
$select1730=mysqli_query($db,"SELECT *  FROM  parcel_info Where bus='9011AA25' and trip_date='2025-10-06' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $code= $get13730['code'];
 $question= $get13730['question'];


$select=mysqli_query($db,"SELECT *  FROM  manifest Where code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){

}else{
	echo "$code <br>";
	    $insert=mysqli_query($db,"UPDATE parcel_info  SET store='store',scanned='$question' WHERE   code='$code' "); 

}

}
}
?>