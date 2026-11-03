<?php
include '../connect.php';
$trip="Tunduma to Lubumbashi";
$update=mysqli_query($db,"UPDATE  manifest SET parcel_route ='$trip' ");
		

?>