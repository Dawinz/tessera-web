<?php
include'connect.php';
$insert2=mysqli_query($db,"UPDATE parcel_info SET amount_remain='0' WHERE code = '58395'");
echo "updated";

?>