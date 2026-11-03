<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM  notification where notification='delete' and status='not deleted'");
$select_num2=mysqli_num_rows($select2);
echo $select_num2;

?>