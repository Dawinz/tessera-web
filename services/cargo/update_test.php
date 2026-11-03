<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';

	   $insert=mysqli_query($db,"UPDATE parcel_info SET answer='2000000',amount_remain ='',payment_status='2760000' WHERE code ='74892'");

?>