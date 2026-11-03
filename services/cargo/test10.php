<?php
include'connect.php';
	  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','$middlename','s_no','$r_firstname','$r_surname','$r_no','$trip','$date','$status_em','$detail','$amount','$sr_code','$status_em','No','$question','$answer','store')");
?>