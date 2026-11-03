<?php
include 'connect.php';
$insert=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$total_to_paid_see',	paid='$total_paid_see',due='$total_all_add'  WHERE Name='$bus' and Route='$trip_date'");



?>