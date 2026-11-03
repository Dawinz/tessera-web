<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
							$insert2=mysqli_query($db,"UPDATE parcel_info SET store='out' WHERE code = '67578'");
							echo "updated";

?>