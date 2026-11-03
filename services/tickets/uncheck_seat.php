<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

include 'connect.php';
$id = $_POST['seat_id'];
$insert=mysqli_query($db,"DELETE from booking WHERE " . tessera_sql_tenant_eq() . " AND (id = '$id')");
?>