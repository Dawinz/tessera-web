<?php
$check = $_POST['no'];
$vehicle = $_POST['vehicle'];
$trip_date = $_POST['trip_date'];
echo $trip_date;
include'connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE code= '$check'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	WHILE($get123=mysqli_fetch_array($select1)){
    	    $scanned =$get123['scanned']; 

if($scanned =="0"){
	echo "finish";
}else{
	echo "yes";
}

		
	}
}else{
	echo "no";
}

?> 