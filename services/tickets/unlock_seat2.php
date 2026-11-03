<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

$id = $_POST['unlock'];
include 'connect.php';
$insert=mysqli_query($db,"DELETE from booking WHERE " . tessera_sql_tenant_eq() . " AND (id = '$id')");

?>