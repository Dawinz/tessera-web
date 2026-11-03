<?php
include 'connect.php';
$select1730=mysqli_query($db,"SELECT *  FROM  parcel_info Where bus='9462AB05' and trip_date='2025-10-03' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $code= $get13730['code'];

echo "$code <br>";

}
}
?>